<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\CustomerSupportMessageSent;
use App\Models\CustomerSupport;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function teacherChatList()
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
        $users = User::whereIn('id', $chattedUserIds)
            ->get()
            ->map(function ($user) use ($userId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->count();
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

        $admin = User::where('role', 'admin')->first();
        $supportUnreadCount = 0;
        $supportLastMessage = null;

        if ($admin) {
            $supportUnreadCount = CustomerSupport::where('sender_id', $admin->id)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->count();
            $supportLastMessage = CustomerSupport::where(function ($query) use ($admin, $userId) {
                $query->where('sender_id', $userId)->where('receiver_id', $admin->id);
            })->orWhere(function ($query) use ($admin, $userId) {
                $query->where('sender_id', $admin->id)->where('receiver_id', $userId);
            })->latest()->first();
        }

        return view('teacher.content.chat.users', compact('users', 'supportUnreadCount', 'supportLastMessage'));
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

        $users = User::whereIn('id', $chattedUserIds)
            ->get()
            ->map(function ($user) use ($userId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->count();
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
        $admin = User::where('role', 'admin')->first();
        $supportUnreadCount = 0;
        $supportLastMessage = null;

        if ($admin) {
            $supportUnreadCount = CustomerSupport::where('sender_id', $admin->id)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->count();
            $supportLastMessage = CustomerSupport::where(function ($query) use ($admin, $userId) {
                $query->where('sender_id', $userId)->where('receiver_id', $admin->id);
            })->orWhere(function ($query) use ($admin, $userId) {
                $query->where('sender_id', $admin->id)->where('receiver_id', $userId);
            })->latest()->first();
        }

        return view('student.content.chat.users', compact('users', 'supportUnreadCount', 'supportLastMessage'));
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
            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ]);
            $message->load('sender');
            Log::info('Message created successfully:', $message->toArray());
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

    public function sendSupport(Request $request)
    {
        Log::info('=== CUSTOMER SUPPORT SEND METHOD CALLED ===');
        Log::info('Request data:', $request->all());

        try {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'message' => 'required|string|max:1000',
            ]);

            Log::info('Customer support validation passed');
            $message = CustomerSupport::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ]);

            $message->load('sender');
            Log::info('Customer support message created successfully:', $message->toArray());
            broadcast(new CustomerSupportMessageSent($message))->toOthers();
            Log::info('Customer support event broadcasted successfully');
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
            Log::error('Error in customer support send method:', [
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

    public function chatWithAdmin()
    {
        $userId = auth()->id();
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return redirect()->back()->with('error', 'Customer support is currently unavailable.');
        }
        $messages = CustomerSupport::where(function ($query) use ($admin, $userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $admin->id);
        })->orWhere(function ($query) use ($admin, $userId) {
            $query->where('sender_id', $admin->id)
                ->where('receiver_id', $userId);
        })->with('sender')->orderBy('created_at')->get();
        CustomerSupport::where('sender_id', $admin->id)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        $role = auth()->user()->role;
        if ($role === 'teacher') {
            return view('teacher.content.chat.admin-chat', compact('messages', 'admin'));
        } elseif ($role === 'student') {
            return view('student.content.chat.admin-chat', compact('messages', 'admin'));
        } else {
            abort(403, 'Unauthorized role.');
        }
    }

    /**
     * Get unread count for customer support messages only
     */
    public function getSupportUnreadCount()
    {
        try {
            $userId = auth()->id();
            $admin = User::where('role', 'admin')->first();
            if (!$admin) {
                return response()->json([
                    'success' => true,
                    'unread_count' => 0
                ]);
            }
            $unreadCount = CustomerSupport::where('sender_id', $admin->id)
                ->where('receiver_id', $userId)
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

    /**
     * Get combined unread count (regular messages + support messages)
     */
    public function getCombinedUnreadCount()
    {
        try {
            $userId = auth()->id();
            $regularUnreadCount = Message::where('receiver_id', $userId)
                ->whereNull('read_at')
                ->count();
            $admin = User::where('role', 'admin')->first();
            $supportUnreadCount = 0;
            if ($admin) {
                $supportUnreadCount = CustomerSupport::where('sender_id', $admin->id)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->count();
            }
            $totalUnreadCount = $regularUnreadCount + $supportUnreadCount;

            return response()->json([
                'success' => true,
                'unread_count' => $totalUnreadCount,
                'regular_count' => $regularUnreadCount,
                'support_count' => $supportUnreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
