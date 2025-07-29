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
        $chattedUserIds = \App\Models\Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->flatMap(function ($msg) use ($userId) {
                return [$msg->sender_id, $msg->receiver_id];
            })
            ->unique()
            ->filter(fn($id) => $id != $userId)
            ->values();

        $users = \App\Models\User::whereIn('id', $chattedUserIds)->get();

        return view('teacher.content.chat.users', compact('users'));
    }


    public function studentChatList()
    {
        $userId = auth()->id();

        $chattedUserIds = \App\Models\Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->flatMap(function ($msg) use ($userId) {
                return [$msg->sender_id, $msg->receiver_id];
            })
            ->unique()
            ->filter(fn($id) => $id != $userId)
            ->values();

        $users = \App\Models\User::whereIn('id', $chattedUserIds)->get();

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

            // Create the message
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

    public function users()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('teacher.content.chat.users', compact('users'));
    }
}
