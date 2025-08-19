<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Chat;

class DashboardController extends Controller
{
    public function index()
    {
        $provider = auth()->user();

        $stats = [
            'total_requests' => ServiceRequest::where('provider_id', $provider->id)->count(),
            'pending_requests' => ServiceRequest::where('provider_id', $provider->id)
                                               ->where('status', 'pending')->count(),
            'accepted_requests' => ServiceRequest::where('provider_id', $provider->id)
                                                ->where('status', 'accepted')->count(),
            'completed_requests' => ServiceRequest::where('provider_id', $provider->id)
                                                 ->where('status', 'completed')->count(),
            'total_bookings' => Booking::where('provider_id', $provider->id)->count(),
            'upcoming_bookings' => Booking::where('provider_id', $provider->id)
                                         ->where('status', 'confirmed')
                                         ->where('scheduled_date', '>', now())
                                         ->count(),
            'completed_bookings' => Booking::where('provider_id', $provider->id)
                                          ->where('status', 'completed')->count(),
            'total_earnings' => Payment::where('provider_id', $provider->id)
                                      ->where('status', 'completed')->sum('amount'),
            'average_rating' => $provider->rating,
            'total_reviews' => $provider->total_reviews,
        ];

        // Recent service requests
        $recent_requests = ServiceRequest::where('provider_id', $provider->id)
                                        ->with(['customer', 'serviceCategory'])
                                        ->latest()
                                        ->take(5)
                                        ->get();

        // Upcoming bookings
        $upcoming_bookings = Booking::where('provider_id', $provider->id)
                                   ->where('status', 'confirmed')
                                   ->where('scheduled_date', '>', now())
                                   ->with(['customer', 'serviceRequest'])
                                   ->orderBy('scheduled_date')
                                   ->take(5)
                                   ->get();

        // Recent reviews
        $recent_reviews = Review::where('provider_id', $provider->id)
                               ->with('customer')
                               ->latest()
                               ->take(5)
                               ->get();

        // Recent chats with unread messages
        $recent_chats = Chat::where('provider_id', $provider->id)
                           ->with(['customer', 'messages' => function($query) {
                               $query->latest()->limit(1);
                           }])
                           ->whereHas('messages', function($query) use ($provider) {
                               $query->where('sender_id', '!=', $provider->id)
                                     ->whereNull('read_at');
                           })
                           ->orderBy('updated_at', 'desc')
                           ->take(5)
                           ->get();

        // Total unread messages count
        $unread_messages_count = Chat::where('provider_id', $provider->id)
                                    ->whereHas('messages', function($query) use ($provider) {
                                        $query->where('sender_id', '!=', $provider->id)
                                              ->whereNull('read_at');
                                    })
                                    ->count();

        return view('provider.dashboard', compact(
            'stats',
            'recent_requests',
            'upcoming_bookings',
            'recent_reviews',
            'recent_chats',
            'unread_messages_count'
        ));
    }

    public function toggleAvailability()
    {
        $provider = auth()->user();

        if ($provider->provider_status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved providers can change availability status.');
        }

        $provider->update([
            'is_available' => !$provider->is_available,
        ]);

        $status = $provider->is_available ? 'online' : 'offline';
        return redirect()->back()->with('success', "You are now {$status}.");
    }
}
