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

        // Check if user has an active subscription
        if (!$user->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->route('packages.index')
                ->with('error', 'You need an active subscription to access this feature.');
        }

        return $next($request);
    }
} 