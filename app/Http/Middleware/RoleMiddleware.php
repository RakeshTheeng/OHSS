<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            // Redirect to appropriate dashboard based on user's actual role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'provider':
                    return redirect()->route('provider.dashboard');
                case 'customer':
                    return redirect()->route('customer.dashboard');
                default:
                    abort(403, 'Unauthorized access.');
            }
        }

        // Additional check for providers - must be approved
        if (in_array('provider', $roles) && $user->role === 'provider' && $user->provider_status !== 'approved') {
            return redirect()->route('provider.pending')->with('warning',
                'Your provider account is still under review. Please wait for admin approval.');
        }

        return $next($request);
    }
}
