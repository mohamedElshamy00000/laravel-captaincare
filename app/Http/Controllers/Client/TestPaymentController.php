<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Services\PaymobService;
use App\Models\SubscriptionInvoice;
use App\Http\Controllers\Controller;

class TestPaymentController extends Controller
{
    protected $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }
    public function showPaymentForm()
    {
        return view('admin.settings.payment.test-payment');
    }
    public function initiatePayment(Request $request)
    {
        $authToken = $this->paymobService->authenticate();

        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:1',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'card_number' => 'required|string',
            'card_exp_month' => 'required|string',
            'card_exp_year' => 'required|string',
            'card_cvv' => 'required|string',
        ]);


        
        $order = $this->paymobService->createOrder($authToken, $request->amount, 'EGP', uniqid());
        $billingData = [
            'apartment' => 'test',
            'floor' => 'test',
            'building' => 'test',
            'street' => 'test',
            'city' => 'damitta',
            'country' => 'egypt',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone,
        ];

        $cardData = [
            'number' => $request->card_number,
            'exp_month' => $request->card_exp_month,
            'exp_year' => $request->card_exp_year,
            'cvv' => $request->card_cvv,
        ];
        // Example logic to integrate with PaymobService and initiate payment
        $paymentKey = $this->paymobService->createPaymentKey(
            $authToken, // Replace with your authentication logic
            $order['id'], // Replace with your order ID logic
            $request->amount,
            $billingData, // Replace with your billing data logic
            $cardData // Replace with your card data logic
        );

        // return response()->json(['payment_key' => $paymentKey['token'], 'iframe_id' => config('services.paymob.iframe_id')]);
        $iframeId = config('services.paymob.iframe_id'); // Replace with your actual iframe_id configuration
        return view('admin.settings.payment.form', [
            'payment_key' => $paymentKey['token'],
            'iframe_id' => $iframeId,
        ]);
    }

    public function handlePaymentCallback(Request $request)
    {

        $paymentStatus = $request->input('success');
        $orderId = $request->input('id');
        // dd($request->all());

        if ($request->input('success') == true) {
            // Update order status, send confirmation emails, etc.

            $invoice = SubscriptionInvoice::where('transaction_id', $orderId)->update([
                'status' => 1,
                'payment_way' => $request->source_data_sub_type ?? $request->source_data_type,
                'updated_at' => $request->updated_at,
            ]);

            // Send a response indicating success
            return response()->json([
                'message'          => 'Payment successful',
                'payment_order_id' => $orderId,
                'status'           => $paymentStatus,
                'Invoice'          => $invoice
            ]);

        } elseif ($request->input('success') == false) {
            // Handle failed payments or other statuses
            
            
            $invoice = SubscriptionInvoice::where('transaction_id', $orderId)->update([
                'payment_way' => $request->source_data_sub_type ?? $request->source_data_type,
                'updated_at' => $request->updated_at,
            ]);

            // Send a response indicating failure
            return response()->json([
                'message' => 'Payment failed',
                'payment_order_id' => $orderId,
                'status' => $paymentStatus,
            ], 400); // You can customize the HTTP status code as needed
        } else {
            // Send a response indicating failure
            return response()->json([
                'message' => 'error',
            ], 500); // You can customize the HTTP status code as needed
        }

    }
}
