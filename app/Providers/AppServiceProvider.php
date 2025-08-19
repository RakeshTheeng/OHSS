<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ServiceRequest;
use App\Models\Chat;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share pending requests count and unread messages count with provider layout
        View::composer('layouts.provider', function ($view) {
            if (auth()->check() && auth()->user()->role === 'provider') {
                $provider = auth()->user();

                $pending_requests_count = ServiceRequest::where('provider_id', $provider->id)
                                                      ->where('status', 'pending')
                                                      ->count();

                $unread_messages_count = Chat::where('provider_id', $provider->id)
                                            ->whereHas('messages', function($query) use ($provider) {
                                                $query->where('sender_id', '!=', $provider->id)
                                                      ->whereNull('read_at');
                                            })
                                            ->count();

                $view->with([
                    'pending_requests_count' => $pending_requests_count,
                    'unread_messages_count' => $unread_messages_count
                ]);
            }
        });
    }
}
