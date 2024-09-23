<?php

namespace App\Services;

use GuzzleHttp\Client;

class PaymobService
{
    protected $client;
    protected $apiKey;
    protected $integrationId;
    protected $iframeId;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.paymob.api_key');
        $this->integrationId = config('services.paymob.integration_id');
        $this->iframeId = config('services.paymob.iframe_id');
    }

    public function authenticate()
    {
        $response = $this->client->post('https://accept.paymobsolutions.com/api/auth/tokens', [
            'json' => [
                'api_key' => $this->apiKey,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['token'];
    }

    public function createOrder($authToken, $amount, $currency, $merchantOrderId)
    {
        $response = $this->client->post('https://accept.paymobsolutions.com/api/ecommerce/orders', [
            'headers' => [
                'Authorization' => 'Bearer ' . $authToken,
            ],
            'json' => [
                'auth_token' => $authToken,
                'delivery_needed' => false,
                'amount_cents' => $amount * 100,
                'currency' => $currency,
                'merchant_order_id' => $merchantOrderId,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function createPaymentKey($authToken, $orderId, $amount, $billingData) //$cardData
    {
        $response = $this->client->post('https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
            'headers' => [
                'Authorization' => 'Bearer ' . $authToken,
            ],
            'json' => [
                'auth_token' => $authToken,
                'amount_cents' => $amount * 100,
                'expiration' => 3600,
                'order_id' => $orderId,
                'billing_data' => $billingData,
                'currency' => 'EGP',
                'integration_id' => $this->integrationId,
                // 'card_number' => $cardData['number'],
                // 'card_expiry_month' => $cardData['exp_month'],
                // 'card_expiry_year' => $cardData['exp_year'],
                // 'card_cvv' => $cardData['cvv'],
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
    
}
