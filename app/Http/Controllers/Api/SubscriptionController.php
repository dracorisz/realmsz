<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,annual',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $user = $request->user();

        // Calculate price based on billing cycle
        $price = $request->billing_cycle === 'annual' 
            ? $plan->price * 12 * 0.8  // 20% discount for annual
            : $plan->price;

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $user->email,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $plan->name,
                            'description' => $plan->description,
                        ],
                        'unit_amount' => (int)($price * 100), // Convert to cents
                        'recurring' => [
                            'interval' => $request->billing_cycle === 'annual' ? 'year' : 'month',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscription.cancel'),
                'metadata' => [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'billing_cycle' => $request->billing_cycle,
                ],
            ]);

            return response()->json([
                'checkout_url' => $session->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create subscription session. Please try again.',
            ], 500);
        }
    }

    public function success(Request $request): JsonResponse
    {
        try {
            $session = Session::retrieve($request->session_id);
            
            // Create subscription record
            Subscription::create([
                'user_id' => $session->metadata->user_id,
                'plan_id' => $session->metadata->plan_id,
                'stripe_subscription_id' => $session->subscription,
                'stripe_customer_id' => $session->customer,
                'status' => 'active',
                'trial_ends_at' => now()->addDays(14), // 14-day trial
            ]);

            return response()->json([
                'message' => 'Subscription created successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to process subscription. Please contact support.',
            ], 500);
        }
    }

    public function cancel(): JsonResponse
    {
        return response()->json([
            'message' => 'Subscription cancelled.',
        ]);
    }
} 