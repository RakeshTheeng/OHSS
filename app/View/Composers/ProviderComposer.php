<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Chat;
use App\Models\ServiceRequest;

class ProviderComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (auth()->check() && auth()->user()->role === 'provider') {
            $provider = auth()->user();
            
            // Get unread messages count
            $unread_messages_count = Chat::where('provider_id', $provider->id)
                                        ->whereHas('messages', function($query) use ($provider) {
                                            $query->where('sender_id', '!=', $provider->id)
                                                  ->whereNull('read_at');
                                        })
                                        ->count();
            
            // Get pending requests count
            $pending_requests_count = ServiceRequest::where('provider_id', $provider->id)
                                                   ->where('status', 'pending')
                                                   ->count();
            
            $view->with([
                'unread_messages_count' => $unread_messages_count,
                'pending_requests_count' => $pending_requests_count
            ]);
        }
    }
}
