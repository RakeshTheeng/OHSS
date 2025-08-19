<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ProviderAvailability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Display the provider's availability settings
     */
    public function index()
    {
        $provider = auth()->user();

        // Get current availability status
        $availabilityStatus = $provider->getAvailabilityStatus();

        // Get weekly availability schedule
        $weeklySchedule = ProviderAvailability::where('provider_id', $provider->id)
                                            ->orderBy('day_of_week')
                                            ->get()
                                            ->keyBy('day_of_week');

        // Days of the week
        $daysOfWeek = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];

        return view('provider.availability.index', compact('availabilityStatus', 'weeklySchedule', 'daysOfWeek'));
    }

    /**
     * Update availability settings
     */
    public function update(Request $request)
    {
        $provider = auth()->user();

        $request->validate([
            'availability' => 'array',
            'availability.*.is_available' => 'boolean',
            'availability.*.start_time' => 'nullable|date_format:H:i',
            'availability.*.end_time' => 'nullable|date_format:H:i|after:availability.*.start_time',
        ]);

        foreach ($request->availability as $dayOfWeek => $schedule) {
            ProviderAvailability::updateOrCreate(
                [
                    'provider_id' => $provider->id,
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'is_available' => $schedule['is_available'] ?? false,
                    'start_time' => $schedule['start_time'] ?? null,
                    'end_time' => $schedule['end_time'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Availability schedule updated successfully!');
    }

    /**
     * Toggle overall availability status
     */
    public function toggleStatus()
    {
        $provider = auth()->user();

        if ($provider->provider_status !== 'approved') {
            return back()->with('error', 'Only approved providers can change availability status.');
        }

        $provider->update([
            'is_available' => !$provider->is_available,
        ]);

        $status = $provider->is_available ? 'available' : 'unavailable';
        return back()->with('success', "You are now {$status} for new bookings.");
    }
}
