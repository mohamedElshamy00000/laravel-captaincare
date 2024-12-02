<?php

namespace App\Http\Controllers\Api;

use App\Models\Father;
use Illuminate\Http\Request;
use App\Services\PaymobService;
use App\Models\SubscriptionInvoice;
use App\Http\Controllers\Controller;
use Creatydev\Plans\Models\PlanModel;
use Illuminate\Support\Facades\Log;
class PaymentController extends Controller
{
    protected $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }

    public function initiatePayment(Request $request)
    {
        try {
            $authToken = $this->paymobService->authenticate();

            $order = $this->paymobService->createOrder($authToken, $request->amount, 'EGP', uniqid());

            $billingData = [
                'apartment' => $request->apartment,
                'floor' => $request->floor,
                'building' => $request->building,
                'street' => $request->street,
                'city' => $request->city,
                'country' => $request->country,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone,
            ];

            // $cardData = [
            //     'number' => $request->card_number,
            //     'exp_month' => $request->card_exp_month,
            //     'exp_year' => $request->card_exp_year,
            //     'cvv' => $request->card_cvv,
            // ];

            $paymentKey = $this->paymobService->createPaymentKey($authToken, $order['id'], $request->amount, $billingData); // $cardData

            return response()->json(['payment_key' => $paymentKey['token'], 'iframe_id' => config('services.paymob.iframe_id')]);

        } catch (\Exception $e) {
            Log::error('Payment initiation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'amount' => $request->amount
            ]);

            return response()->json([
                'error' => 'Failed to initiate payment',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }

    /**
     * Handle the response from Paymob.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function handleResponse(Request $request)
    {
        // Extract relevant data from the request
        $paymentStatus = $request->input('success');
        $orderId = $request->input('id');

        if (!$orderId) {
            return response()->json(['message' => 'Invalid order ID'], 400);
        }

        if ($request->input('success') == true) {

            $invoice = SubscriptionInvoice::where('transaction_id', $orderId)->first();
            // dd($invoice);
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            // Update order status, send confirmation emails, etc.
            $invoice = SubscriptionInvoice::where('transaction_id', $orderId)->first();
            $invoice->update([
                'status' => 1,
                'payment_way' => $request->source_data_sub_type ?? $request->source_data_type,
                'updated_at' => $request->updated_at,
            ]);

            $user = Father::where('id', $invoice->user_id)->first();
            $plan = PlanModel::where('id', $invoice->plan_id)->first();
            $user->subscribeTo($plan, $plan->duration);

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
            return response()->json(['message' => 'Error processing payment response'], 500);
        }
    }
}
