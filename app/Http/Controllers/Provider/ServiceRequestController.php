<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:provider');
    }

    /**
     * Display a listing of service requests for the provider.
     */
    public function index(Request $request)
    {
        $provider = auth()->user();

        $query = ServiceRequest::where('provider_id', $provider->id)
                              ->with(['customer', 'serviceCategory']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $serviceRequests = $query->latest()->paginate(10);

        $stats = [
            'total' => ServiceRequest::where('provider_id', $provider->id)->count(),
            'pending' => ServiceRequest::where('provider_id', $provider->id)->where('status', 'pending')->count(),
            'accepted' => ServiceRequest::where('provider_id', $provider->id)->where('status', 'accepted')->count(),
            'rejected' => ServiceRequest::where('provider_id', $provider->id)->where('status', 'rejected')->count(),
            'completed' => ServiceRequest::where('provider_id', $provider->id)->where('status', 'completed')->count(),
        ];

        return view('provider.service-requests.index', compact('serviceRequests', 'stats'));
    }

    /**
     * Display the specified service request.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $provider = auth()->user();

        // Check if this request belongs to the current provider
        if ($serviceRequest->provider_id !== $provider->id) {
            abort(403, 'Unauthorized access to this service request.');
        }

        $serviceRequest->load(['customer', 'serviceCategory', 'booking', 'chatMessages']);

        return view('provider.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Accept a service request.
     */
    public function accept(Request $request, ServiceRequest $serviceRequest)
    {
        $provider = auth()->user();

        // Check if this request belongs to the current provider
        if ($serviceRequest->provider_id !== $provider->id) {
            abort(403, 'Unauthorized access to this service request.');
        }

        if (!$serviceRequest->canBeAccepted()) {
            return back()->with('error', 'This request cannot be accepted.');
        }

        $request->validate([
            'response' => 'nullable|string|max:500'
        ]);

        $serviceRequest->accept($request->response);

        // Create notification for customer using the new method
        Notification::serviceRequestAccepted($serviceRequest->customer_id, $serviceRequest);

        // Create chat for communication
        $chat = \App\Models\Chat::firstOrCreate([
            'customer_id' => $serviceRequest->customer_id,
            'provider_id' => $provider->id,
            'booking_id' => null, // Will be updated when booking is created
        ]);

        return back()->with('success', 'Service request accepted successfully! The customer has been notified and can now proceed to book the service. A chat has been created for communication.');
    }

    /**
     * Reject a service request.
     */
    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        $provider = auth()->user();

        // Check if this request belongs to the current provider
        if ($serviceRequest->provider_id !== $provider->id) {
            abort(403, 'Unauthorized access to this service request.');
        }

        if (!$serviceRequest->canBeRejected()) {
            return back()->with('error', 'This request cannot be rejected.');
        }

        $request->validate([
            'response' => 'required|string|max:500'
        ]);

        $serviceRequest->reject($request->response);

        // Create notification for customer
        Notification::createForUser(
            $serviceRequest->customer_id,
            'service_request_rejected',
            'Service Request Rejected',
            "Your service request has been rejected by {$provider->name}",
            ['service_request_id' => $serviceRequest->id]
        );

        return back()->with('success', 'Service request rejected.');
    }

    /**
     * Mark a service request as completed
     */
    public function complete(Request $request, ServiceRequest $serviceRequest)
    {
        $provider = auth()->user();

        // Check if this request belongs to the current provider
        if ($serviceRequest->provider_id !== $provider->id) {
            abort(403, 'Unauthorized access to this service request.');
        }

        // Only accepted or in_progress requests can be completed
        if (!in_array($serviceRequest->status, ['accepted', 'in_progress'])) {
            return back()->with('error', 'This request cannot be marked as completed.');
        }

        $request->validate([
            'completion_notes' => 'nullable|string|max:1000'
        ]);

        // Update service request status
        $serviceRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $request->completion_notes,
        ]);

        // If there's a booking, mark it as completed too
        if ($serviceRequest->booking) {
            $serviceRequest->booking->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completion_notes' => $request->completion_notes,
            ]);
        }

        // Create notification for customer
        Notification::createForUser(
            $serviceRequest->customer_id,
            'service_completed',
            'Service Completed',
            "Your service has been completed by {$provider->name}",
            ['service_request_id' => $serviceRequest->id]
        );

        return back()->with('success', 'Service marked as completed successfully! The customer can now leave a review.');

        $serviceRequest->reject($request->response);

        // Create notification for customer
        Notification::createForUser(
            $serviceRequest->customer_id,
            'service_request_rejected',
            'Service Request Rejected',
            "Your service request has been rejected by {$provider->name}",
            ['service_request_id' => $serviceRequest->id]
        );

        return back()->with('success', 'Service request rejected.');
    }
}
