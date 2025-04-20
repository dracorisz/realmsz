<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Allow access for suzy role without subscription check
        if ($user->role === 'suzy') {
            return $next($request);
        }

        // Check if user has an active subscription
        if (!$user->hasActiveSubscription()) {
            // If user is on the waiting page or subscription pages, allow access
            if ($request->is('subscription/*') || $request->is('packages/*') || $request->is('waiting')) {
                return $next($request);
            }
            
            // For all other protected routes, redirect to waiting page
            return redirect()->route('waiting')
                ->with('error', 'You need an active subscription to access this feature.');
        }

        return $next($request);
    }
} 