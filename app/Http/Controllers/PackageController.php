<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)->get();
        
        return Inertia::render('Packages/Index', [
            'packages' => $packages,
        ]);
    }

    public function show(Package $package)
    {
        return Inertia::render('Packages/Show', [
            'package' => $package,
        ]);
    }

    public function subscribe(Request $request, Package $package)
    {
        $user = $request->user();
        
        // Check if user already has an active subscription
        if ($user->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'You already have an active subscription.');
        }

        // Create new subscription
        $subscription = $user->subscriptions()->create([
            'package_id' => $package->id,
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14), // 14-day trial
        ]);

        // TODO: Implement payment processing
        // For now, we'll just create the subscription

        return redirect()->route('dashboard')
            ->with('success', 'Subscription created successfully!');
    }

    public function cancel(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscriptions()->where('status', 'active')->first();

        if (!$subscription) {
            return back()->with('error', 'No active subscription found.');
        }

        $subscription->update([
            'status' => 'cancelled',
            'ends_at' => now()->addMonth(), // End at next billing cycle
        ]);

        return back()->with('success', 'Subscription cancelled successfully.');
    }
} 