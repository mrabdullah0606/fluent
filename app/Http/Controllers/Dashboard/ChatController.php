<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function teacherChatList()
    {
        $userId = auth()->id();

        // Get all users the teacher has chatted with
        $chattedUserIds = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->flatMap(function ($msg) use ($userId) {
                return [$msg->sender_id, $msg->receiver_id];
            })
            ->unique()
            ->filter(fn($id) => $id != $userId)
            ->values();

        // Get users with their unread message counts
        $users = User::whereIn('id', $chattedUserIds)
            ->get()
            ->map(function ($user) use ($userId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->count();

                // Get last message for preview
                $user->last_message = Message::where(function ($query) use ($user, $userId) {
                    $query->where('sender_id', $userId)->where('receiver_id', $user->id);
                })->orWhere(function ($query) use ($user, $userId) {
                    $query->where('sender_id', $user->id)->where('receiver_id', $userId);
                })->latest()->first();

                return $user;
            })
            ->sortByDesc(function ($user) {
                return $user->last_message ? $user->last_message->created_at : null;
            });

        return view('teacher.content.chat.users', compact('users'));
    }

    public function studentChatList()
    {
        $userId = auth()->id();

        $chattedUserIds = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->flatMap(function ($msg) use ($userId) {
                return [$msg->sender_id, $msg->receiver_id];
            })
            ->unique()
            ->filter(fn($id) => $id != $userId)
            ->values();

        // Get users with their unread message counts
        $users = User::whereIn('id', $chattedUserIds)
            ->get()
            ->map(function ($user) use ($userId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->count();

                // Get last message for preview
                $user->last_message = Message::where(function ($query) use ($user, $userId) {
                    $query->where('sender_id', $userId)->where('receiver_id', $user->id);
                })->orWhere(function ($query) use ($user, $userId) {
                    $query->where('sender_id', $user->id)->where('receiver_id', $userId);
                })->latest()->first();

                return $user;
            })
            ->sortByDesc(function ($user) {
                return $user->last_message ? $user->last_message->created_at : null;
            });

        return view('student.content.chat.users', compact('users'));
    }

    public function index(User $user)
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->with('sender')->orderBy('created_at')->get();

        // Mark messages as read when viewing the chat
        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $role = auth()->user()->role;

        if ($role === 'teacher') {
            return view('teacher.content.chat.index', compact('messages', 'user'));
        } elseif ($role === 'student') {
            return view('student.content.chat.index', compact('messages', 'user'));
        } else {
            abort(403, 'Unauthorized role.');
        }
    }

    public function send(Request $request)
    {
        Log::info('=== CHAT SEND METHOD CALLED ===');
        Log::info('Request data:', $request->all());

        try {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'message' => 'required|string|max:1000',
            ]);

            Log::info('Validation passed');

            // Create the message (read_at will be null by default, marking it as unread)
            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ]);

            // Load the sender relationship
            $message->load('sender');

            Log::info('Message created successfully:', $message->toArray());

            // Broadcast the event immediately (synchronously)
            broadcast(new MessageSent($message))->toOthers();

            Log::info('Event broadcasted successfully');

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
            Log::error('Error in send method:', [
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
            $userId = auth()->id();
            $unreadCount = Message::where('receiver_id', $userId)
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
            $userId = auth()->id();
            $updated = Message::where('receiver_id', $userId)
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
