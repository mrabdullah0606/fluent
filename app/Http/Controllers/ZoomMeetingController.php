<?php

namespace App\Http\Controllers;

use App\Mail\ZoomMeetingInvitationMail;
use App\Models\ZoomMeeting;
use Illuminate\Http\Request;
use App\Services\ZoomService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ZoomMeetingController extends Controller
{
    public function index(Request $request)
    {
        $meetings = ZoomMeeting::latest()
            ->paginate(10);

        return view('teacher.content.zoom.meetings', compact('meetings'));
    }

    // public function getStudents($type)
    // {
    //     $students = \App\Models\User::whereHas('payments', function ($q) use ($type) {
    //         $q->where('teacher_id', auth()->id())
    //             ->where('type', $type)
    //             ->where('status', 'successful');
    //     })->get();

    //     return response()->json($students);
    // }
    public function getSummaries(Request $request)
    {
        $type = $request->input('type');

        $summaries = \DB::table('payments')
            ->where('teacher_id', auth()->id())
            ->where('status', 'successful')
            ->where('type', $type)
            ->pluck('summary')
            ->unique()
            ->values();

        return response()->json($summaries);
    }

    public function getStudentsBySummary(Request $request)
    {
        $summary = $request->input('summary');

        $students = \App\Models\User::whereHas('payments', function ($q) use ($summary) {
            $q->where('teacher_id', auth()->id())
                ->where('status', 'successful')
                ->where('summary', $summary);
        })->get(['id', 'name', 'email']);

        return response()->json($students);
    }



    // public function store(Request $request, ZoomService $zoomService)
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'topic' => 'required|string|max:255',
    //         'start_time' => 'required|date|after:now',
    //         'duration' => 'required|integer|min:1|max:480',
    //         'meeting_type' => 'required|min:1|max:480',
    //     ]);

    //     try {
    //         // Use fixed Zoom host email for all meetings
    //     $zoomHostEmail = config('services.zoom.host_email', 'itsabdullah824@gmail.com');

    //         // Create Zoom meeting
    //         $zoomData = $zoomService->createMeeting(
    //             $zoomHostEmail,
    //             $validated['topic'],
    //             $validated['start_time'],
    //             $validated['duration']
    //         );

    //         if (!$zoomData) {
    //             Log::error('Zoom meeting creation failed', [
    //                 'user_id' => Auth::id(),
    //                 'email' => $zoomHostEmail,
    //                 'topic' => $validated['topic']
    //             ]);

    //             return back()
    //                 ->withInput()
    //                 ->with('error', 'Failed to create Zoom meeting. Please ensure your email is registered with Zoom.');
    //         }

    //         // Save meeting to database
    //         $zoomMeeting = ZoomMeeting::create([
    //             'uuid' => $zoomData['uuid'] ?? null,
    //             'meeting_id' => $zoomData['id'],
    //             'host_id' => $zoomData['host_id'] ?? $zoomHostEmail,
    //             'topic' => $zoomData['topic'],
    //             'start_time' => $zoomData['start_time'],
    //             'duration' => $zoomData['duration'],
    //             'teacher_id' => auth()->user()->id,
    //             'meeting_type' => $request->input('meeting_type'),
    //             'timezone' => $zoomData['timezone'] ?? 'Asia/Karachi',
    //             'join_url' => $zoomData['join_url'],
    //             'start_url' => $zoomData['start_url'] ?? null,
    //             'password' => $zoomData['password'] ?? null,
    //             'raw_response' => $zoomData,
    //         ]);

    //         Log::info('Zoom meeting created successfully', [
    //             'meeting_id' => $zoomMeeting->meeting_id,
    //             'user_id' => Auth::id(),
    //             'topic' => $zoomMeeting->topic
    //         ]);

    //         return redirect()->route('teacher.zoom.meetings.index')
    //             ->with('success', 'Zoom meeting created successfully! You can now share the join link with your students.');
    //     } catch (\Exception $e) {
    //         Log::error('Exception creating Zoom meeting', [
    //             'error' => $e->getMessage(),
    //             'user_id' => Auth::id(),
    //             'topic' => $validated['topic']
    //         ]);

    //         return back()
    //             ->withInput()
    //             ->with('error', 'An error occurred while creating the meeting. Please try again.');
    //     }
    // }
    public function store(Request $request, ZoomService $zoomService)
    {
        // Validate the request
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:480',
            'meeting_type' => 'required',
            'attendees' => 'array', // <-- NEW
            'attendees.*' => 'email', // each attendee must be email
        ]);

        try {
            $zoomHostEmail = config('services.zoom.host_email', 'itsabdullah824@gmail.com');

            $zoomData = $zoomService->createMeeting(
                $zoomHostEmail,
                $validated['topic'],
                $validated['start_time'],
                $validated['duration']
            );

            if (!$zoomData) {
                return back()->with('error', 'Failed to create Zoom meeting.');
            }

            $zoomMeeting = ZoomMeeting::create([
                'uuid' => $zoomData['uuid'] ?? null,
                'meeting_id' => $zoomData['id'],
                'host_id' => $zoomData['host_id'] ?? $zoomHostEmail,
                'topic' => $zoomData['topic'],
                'start_time' => $zoomData['start_time'],
                'duration' => $zoomData['duration'],
                'teacher_id' => auth()->user()->id,
                'meeting_type' => $request->input('meeting_type'),
                'timezone' => $zoomData['timezone'] ?? 'Asia/Karachi',
                'join_url' => $zoomData['join_url'],
                'start_url' => $zoomData['start_url'] ?? null,
                'password' => $zoomData['password'] ?? null,
                'raw_response' => $zoomData,
            ]);

            if ($request->has('attendees')) {
                foreach ($request->attendees as $email) {
                    Mail::to($email)->queue(new ZoomMeetingInvitationMail($zoomMeeting));
                }
            }

            return redirect()->route('teacher.zoom.meetings.index')
                ->with('success', 'Zoom meeting created and emails sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the meeting. Please try again.');
        }
    }

    /**
     * Delete a meeting (optional method for future use)
     */
    public function destroy(ZoomMeeting $meeting)
    {
        // You can implement Zoom API call to delete the meeting here if needed
        $meeting->delete();

        return redirect()->route('teacher.zoom.meetings.index')
            ->with('success', 'Meeting deleted successfully.');
    }

    /**
     * Get meeting details (optional method for future use)
     */
    public function show(ZoomMeeting $meeting)
    {
        return response()->json([
            'meeting' => $meeting,
            'join_url' => $meeting->join_url,
            'meeting_id' => $meeting->meeting_id,
            'password' => $meeting->password
        ]);
    }
}
