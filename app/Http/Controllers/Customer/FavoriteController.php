<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of customer's favorite providers
     */
    public function index()
    {
        $favorites = Favorite::where('customer_id', auth()->id())
                           ->with(['provider.serviceCategories', 'provider.reviews'])
                           ->latest()
                           ->paginate(12);

        return view('customer.favorites.index', compact('favorites'));
    }

    /**
     * Toggle favorite status for a provider
     */
    public function toggle(User $provider)
    {
        // Ensure the user is a provider
        if ($provider->role !== 'provider') {
            return response()->json(['error' => 'Invalid provider'], 400);
        }

        $customerId = auth()->id();

        $favorite = Favorite::where('customer_id', $customerId)
                          ->where('provider_id', $provider->id)
                          ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $isFavorited = false;
            $message = 'Provider removed from favorites';
        } else {
            // Add to favorites
            Favorite::create([
                'customer_id' => $customerId,
                'provider_id' => $provider->id,
            ]);
            $isFavorited = true;
            $message = 'Provider added to favorites';
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
