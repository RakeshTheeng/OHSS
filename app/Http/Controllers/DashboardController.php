<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Redirect based on user role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'provider':
                // Check if provider is approved
                if ($user->provider_status === 'approved') {
                    return redirect()->route('provider.dashboard');
                } else {
                    return redirect()->route('provider.pending');
                }
            case 'customer':
                return redirect()->route('customer.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Invalid user role.');
        }
    }
}
