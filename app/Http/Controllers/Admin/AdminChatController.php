<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\CustomerSupportMessageSent;
use App\Models\CustomerSupport;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminChatController extends Controller
{
    public function index()
    {
        $adminId = auth()->id();
        $chattedUserIds = CustomerSupport::where(function ($query) use ($adminId) {
            $query->where('sender_id', $adminId)
                ->orWhere('receiver_id', $adminId);
        })
            ->get()
            ->flatMap(function ($msg) use ($adminId) {
                return [$msg->sender_id, $msg->receiver_id];
            })
            ->unique()
            ->filter(fn($id) => $id != $adminId)
            ->values();
        $users = User::whereIn('id', $chattedUserIds)
            ->where('role', '!=', 'admin')
            ->get()
            ->map(function ($user) use ($adminId) {
                $user->unread_count = CustomerSupport::where('sender_id', $user->id)
                    ->where('receiver_id', $adminId)
                    ->whereNull('read_at')
                    ->count();
                $user->last_message = CustomerSupport::where(function ($query) use ($user, $adminId) {
                    $query->where('sender_id', $adminId)->where('receiver_id', $user->id);
                })->orWhere(function ($query) use ($user, $adminId) {
                    $query->where('sender_id', $user->id)->where('receiver_id', $adminId);
                })->latest()->first();

                return $user;
            })
            ->sortByDesc(function ($user) {
                return $user->last_message ? $user->last_message->created_at : null;
            });

        return view('admin.content.chat.index', compact('users'));
    }

    public function chat(User $user)
    {
        $adminId = auth()->id();
        $messages = CustomerSupport::where(function ($query) use ($user, $adminId) {
            $query->where('sender_id', $adminId)
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user, $adminId) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $adminId);
        })->with('sender')->orderBy('created_at')->get();
        CustomerSupport::where('sender_id', $user->id)
            ->where('receiver_id', $adminId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.content.chat.chat', compact('messages', 'user'));
    }

    public function send(Request $request)
    {
        Log::info('=== ADMIN CHAT SEND METHOD CALLED ===');
        Log::info('Request data:', $request->all());

        try {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'message' => 'required|string|max:1000',
            ]);
            Log::info('Admin chat validation passed');
            $message = CustomerSupport::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ]);
            $message->load('sender');
            Log::info('Admin message created successfully:', $message->toArray());
            broadcast(new CustomerSupportMessageSent($message))->toOthers();
            Log::info('Admin event broadcasted successfully');
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_name' => $message->sender->name,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in admin send method:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUnreadCount()
    {
        try {
            $adminId = auth()->id();
            $unreadCount = CustomerSupport::where('receiver_id', $adminId)
                ->whereNull('read_at')
                ->count();

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            $adminId = auth()->id();
            $updated = CustomerSupport::where('receiver_id', $adminId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'updated_count' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
