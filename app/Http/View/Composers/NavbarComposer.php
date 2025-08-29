<?php

namespace App\Http\View\Composers;

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
            $unreadMessagesCount = Message::where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->count();

            $view->with('unreadMessagesCount', $unreadMessagesCount);
        } else {
            $view->with('unreadMessagesCount', 0);
        }
    }
}
