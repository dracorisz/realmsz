<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index()
    {
        $plans = Plan::all();
        return view('plans', compact('plans'));
    }

    public function createCheckoutSession(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $billingCycle = $request->billing_cycle;

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $plan->name,
                    ],
                    'unit_amount' => $billingCycle === 'annual' 
                        ? $plan->annual_price * 100 
                        : $plan->monthly_price * 100,
                    'recurring' => [
                        'interval' => $billingCycle === 'annual' ? 'year' : 'month',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('plans'),
            'customer_email' => Auth::user()->email,
        ]);

        return response()->json(['sessionId' => $session->id]);
    }

    public function success(Request $request)
    {
        $session = Session::retrieve($request->session_id);
        
        // Create or update subscription
        Subscription::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'stripe_subscription_id' => $session->subscription,
                'stripe_customer_id' => $session->customer,
                'stripe_price_id' => $session->line_items->data[0]->price->id,
                'status' => 'active',
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Subscription activated successfully!');
    }

    public function cancel()
    {
        $subscription = Auth::user()->subscription;
        
        if ($subscription) {
            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_subscription_id);
            $stripeSubscription->cancel();
            
            $subscription->update(['status' => 'cancelled']);
        }

        return redirect()->route('dashboard')->with('success', 'Subscription cancelled successfully!');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('', 400);
        }

        switch ($event->type) {
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                Subscription::where('stripe_subscription_id', $subscription->id)
                    ->update(['status' => 'cancelled']);
                break;
            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                Subscription::where('stripe_subscription_id', $subscription->id)
                    ->update(['status' => $subscription->status]);
                break;
        }

        return response('', 200);
    }
} 