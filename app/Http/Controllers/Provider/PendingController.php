<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Only providers can access this page
        if ($user->role !== 'provider') {
            return redirect()->route('dashboard');
        }

        // If provider is already approved, redirect to dashboard
        if ($user->provider_status === 'approved') {
            return redirect()->route('provider.dashboard');
        }

        return view('provider.pending');
    }
}
