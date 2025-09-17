<?php

namespace App\Http\View\Composers;

use App\Models\CustomerSupport;
use Illuminate\View\View;
use App\Models\Message;

class NavbarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Regular messages count
            $unreadMessagesCount = Message::where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->count();

            // Admin support messages count (only for admins)
            $unreadSupportCount = 0;
            if ($user->role === 'admin') {
                $unreadSupportCount = CustomerSupport::where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->count();
            }

            $view->with([
                'unreadMessagesCount' => $unreadMessagesCount,
                'unreadSupportCount' => $unreadSupportCount
            ]);
        } else {
            $view->with([
                'unreadMessagesCount' => 0,
                'unreadSupportCount' => 0
            ]);
        }
    }
}
