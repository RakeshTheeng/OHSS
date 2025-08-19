<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            // Store the intended URL for after login
            session(['url.intended' => $request->url()]);
            
            return redirect()->route('login')->with('info', 'Please login to send service requests.');
        }

        if (Auth::user()->role !== 'customer') {
            return redirect()->route('dashboard')->with('error', 'Only customers can send service requests.');
        }

        return $next($request);
    }
}
