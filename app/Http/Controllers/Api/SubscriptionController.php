<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\PaymobService;
use App\Models\SubscriptionInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Creatydev\Plans\Models\PlanModel;
use App\Traits\Father\SubscriptionTrait;

class SubscriptionController extends Controller
{
    use SubscriptionTrait;

    protected $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }

    // Returns subscription overview
    public function subscriptionOverview()
    {
        // الحصول على المستخدم المصادق باستخدام JWT
        $father = auth()->guard('father')->user();
        // $driver = auth()->guard('driver')->user();
        if (!$father) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($father->hasActiveSubscription()) {
            $subscription = $father->activeSubscription();
            $invoices = SubscriptionInvoice::where('user_id', $father->id)->get();
            return response()->json([
                'subscription' => $subscription,
                'invoices' => $invoices
            ], 200);
        } else {
            $plans = PlanModel::whereJsonContains('metadata', ['user' => 'father'])->get();
            return response()->json([
                'plans' => $plans
            ], 200);
        }
    }

    // Returns available subscription plans
    public function subscriptionPlans()
    {
        // الحصول على المستخدم المصادق باستخدام JWT
        $father = auth()->guard('father')->user();

        if (!$father) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $plans = PlanModel::whereJsonContains('metadata', ['user' => 'father'])->get();
        return response()->json([
            'plans' => $plans
        ], 200);
    }

    // Sets a subscription for the user
    public function setSubscription($plan_id)
    {
        try {
            // الحصول على المستخدم المصادق باستخدام JWT
            $father = auth()->guard('father')->user();

            if (!$father) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            if ($father->hasActiveSubscription()) {
                return response()->json([
                    'message' => __('message.already_subscribed'),
                    'alert' => 'danger'
                ], 400);
            } else {
                $plan = PlanModel::find($plan_id);
                if ($plan) {
                    $invoice = SubscriptionInvoice::where('user_id', $father->id)->where('status', 0)->first();
                    if (!$invoice) {
                        $invoice = SubscriptionInvoice::create([
                            'due_date'  => now(),
                            'amount'    => $plan->price,
                            'user_id'   => $father->id,
                            'plan_id'   => $plan->id,
                            'status'    => 0,
                        ]);
                    }

                    // After creating the invoice, initiate the payment

                    $authToken = $this->paymobService->authenticate();
                    $order = $this->paymobService->createOrder($authToken, $invoice->amount, 'EGP', uniqid());
                    $invoice->transaction_id = $order['id'];
                    $invoice->save();

                    $billingData = [
                        'amount' => $invoice->amount,
                        'apartment' => 'null',
                        'floor' => 'null',
                        'building' => 'null',
                        'street' => $father->address,
                        'city' => $father->city,
                        'country' => 'Egypt',
                        'first_name' => $father->name,
                        'last_name' => $father->name,
                        'email' => $father->email,
                        'phone_number' => $father->phone,
                    ];
                    $paymentKey = $this->paymobService->createPaymentKey($authToken, $order['id'], $invoice->amount, $billingData); // $cardData
                    
                    // Check if the payment initiation was successful
                    if ($paymentKey) {

                        // Return the payment response along with invoice and plan details
                        return response()->json([
                            'invoice' => $invoice,
                            'plan' => $plan,
                            'payment_key' => $paymentKey['token'],
                            'iframe_id' => config('services.paymob.iframe_id'),
                        ], 200);
                    } else {
                        // Handle payment initiation failure
                        return response()->json([
                            'message' => __('message.payment_initiation_failed'),
                            'alert' => 'danger'
                        ], 500);
                    }

                } else {
                    return response()->json([
                        'message' => __('message.plan_not_found'),
                        'alert' => 'danger'
                    ], 404);
                }
            }
        } catch (\Exception $e) {
            // Return an error response to the client
            return response()->json(['error' => 'Failed. Please try again later.'], 500);
        }

    }
}

