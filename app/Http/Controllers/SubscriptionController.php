<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
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
                'success_url' => route('subscriptions.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscriptions.plans'),
                'customer_email' => Auth::user()->email,
            ]);

            Log::info('Stripe checkout session created', [
                'user_id' => Auth::id(),
                'plan_id' => $plan->id,
                'billing_cycle' => $billingCycle,
                'session_id' => $session->id
            ]);

            return response()->json(['url' => $session->url]);
        } catch (\Exception $e) {
            Log::error('Stripe checkout session creation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'plan_id' => $request->plan_id
            ]);
            
            return response()->json([
                'error' => 'Failed to create checkout session. Please try again.'
            ], 500);
        }
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
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $this->handleSubscriptionDeleted($subscription);
                break;
            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                $this->handleSubscriptionUpdated($subscription);
                break;
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                $this->handleInvoicePaymentSucceeded($invoice);
                break;
            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                $this->handleInvoicePaymentFailed($invoice);
                break;
        }

        return response($event->type, 200);
    }

    protected function handleCheckoutSessionCompleted($session)
    {
        $user = \App\Models\User::find($session->metadata->user_id);
        if (!$user) return;

        $subscription = \App\Models\Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $session->metadata->plan_id,
            'stripe_subscription_id' => $session->subscription,
            'stripe_customer_id' => $session->customer,
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        // Send confirmation email
        $user->notify(new \App\Notifications\SubscriptionActivated($subscription));
    }

    protected function handleSubscriptionDeleted($subscription)
    {
        \App\Models\Subscription::where('stripe_subscription_id', $subscription->id)
            ->update([
                'status' => 'cancelled',
                'ends_at' => now(),
            ]);
    }

    protected function handleSubscriptionUpdated($subscription)
    {
        \App\Models\Subscription::where('stripe_subscription_id', $subscription->id)
            ->update(['status' => $subscription->status]);
    }

    protected function handleInvoicePaymentSucceeded($invoice)
    {
        $subscription = \App\Models\Subscription::where('stripe_subscription_id', $invoice->subscription)
            ->first();
        
        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'ends_at' => null,
            ]);
        }
    }

    protected function handleInvoicePaymentFailed($invoice)
    {
        $subscription = \App\Models\Subscription::where('stripe_subscription_id', $invoice->subscription)
            ->first();
        
        if ($subscription) {
            $subscription->update([
                'status' => 'past_due',
            ]);
            
            // Notify user about failed payment
            $subscription->user->notify(new \App\Notifications\PaymentFailed($subscription));
        }
    }
} 