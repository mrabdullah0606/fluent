<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    /**
     * Validate Zoom configuration
     */
    private function validateConfig()
    {
        $clientId = config('services.zoom.client_id');
        $clientSecret = config('services.zoom.client_secret');
        $accountId = config('services.zoom.account_id');

        if (empty($clientId) || empty($clientSecret) || empty($accountId)) {
            Log::error('Zoom configuration missing', [
                'client_id_set' => !empty($clientId),
                'client_secret_set' => !empty($clientSecret),
                'account_id_set' => !empty($accountId),
            ]);

            throw new \Exception('Zoom configuration is incomplete. Please check your environment variables.');
        }

        return compact('clientId', 'clientSecret', 'accountId');
    }

    public function getAccessToken()
    {
        return Cache::remember('zoom_access_token', 3500, function () {
            try {
                $config = $this->validateConfig();

                $response = Http::withBasicAuth($config['clientId'], $config['clientSecret'])
                    ->asForm()
                    ->timeout(30)
                    ->post('https://zoom.us/oauth/token', [
                        'grant_type' => 'account_credentials',
                        'account_id' => $config['accountId'],
                    ]);

                if (!$response->successful()) {
                    Log::error('Zoom Access Token Error', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'headers' => $response->headers(),
                    ]);
                    return null;
                }

                $data = $response->json();

                if (!isset($data['access_token'])) {
                    Log::error('Access token not found in response', ['response' => $data]);
                    return null;
                }

                Log::info('Zoom access token obtained successfully');
                return $data['access_token'];
            } catch (\Exception $e) {
                Log::error('Exception getting Zoom access token', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return null;
            }
        });
    }

    public function createMeeting($hostId, $topic, $startTime, $duration = 30)
    {
        try {
            $token = $this->getAccessToken();

            if (!$token) {
                Log::error('Zoom token is null, cannot create meeting.');
                return null;
            }

            // Ensure start_time is properly formatted
            $formattedStartTime = \Carbon\Carbon::parse($startTime)->toISOString();

            $response = Http::withToken($token)
                ->timeout(30)
                ->post("https://api.zoom.us/v2/users/{$hostId}/meetings", [
                    'topic' => $topic,
                    'type' => 2, // Scheduled meeting
                    'start_time' => $formattedStartTime,
                    'duration' => $duration,
                    'timezone' => 'Asia/Karachi',
                    'settings' => [
                        'join_before_host' => true,
                        'approval_type' => 0, // Automatically approve
                        'mute_upon_entry' => true,
                        'waiting_room' => false,
                        'audio' => 'both',
                        'auto_recording' => 'none',
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('Zoom createMeeting error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'host_id' => $hostId,
                    'topic' => $topic
                ]);
                return null;
            }

            $meetingData = $response->json();

            Log::info('Zoom meeting created successfully', [
                'meeting_id' => $meetingData['id'] ?? 'unknown',
                'topic' => $topic,
                'host_id' => $hostId
            ]);

            return $meetingData;
        } catch (\Exception $e) {
            Log::error('Exception creating Zoom meeting', [
                'error' => $e->getMessage(),
                'host_id' => $hostId,
                'topic' => $topic,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Test Zoom configuration
     */
    public function testConfiguration()
    {
        try {
            $config = $this->validateConfig();
            $token = $this->getAccessToken();

            if (!$token) {
                return [
                    'success' => false,
                    'message' => 'Failed to obtain access token'
                ];
            }

            // Test API call - use a simpler endpoint that requires only meeting:write scope
            $testEmail = config('services.zoom.test_email', 'itsabdullah824@gmail.com');
            $response = Http::withToken($token)
                ->timeout(30)
                ->get("https://api.zoom.us/v2/users/{$testEmail}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Zoom configuration is working correctly',
                    'user_data' => $response->json()
                ];
            } else {
                // If user endpoint fails, try creating a test meeting instead
                $testResponse = Http::withToken($token)
                    ->timeout(30)
                    ->post("https://api.zoom.us/v2/users/{$testEmail}/meetings", [
                        'topic' => 'Configuration Test Meeting',
                        'type' => 2,
                        'start_time' => now()->addHour()->toISOString(),
                        'duration' => 30,
                        'settings' => [
                            'join_before_host' => true
                        ]
                    ]);

                if ($testResponse->successful()) {
                    // Delete the test meeting immediately
                    $meetingData = $testResponse->json();
                    Http::withToken($token)->delete("https://api.zoom.us/v2/meetings/{$meetingData['id']}");

                    return [
                        'success' => true,
                        'message' => 'Zoom configuration is working correctly (meeting creation test passed)',
                        'meeting_test' => true
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Both user and meeting API tests failed. Response: ' . $testResponse->body()
                    ];
                }
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Configuration error: ' . $e->getMessage()
            ];
        }
    }
}
