<?php

namespace App\Http\Services;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayService
{
    public function create_order_id(float $amount, string $receipt): string
    {

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $orderData = [
            'receipt'         => $receipt,
            'amount'          => $amount*100, // 39900 rupees in paise
            'currency'        => 'INR',
            'partial_payment' => false,
        ];

        $razorpayOrder = $api->order->create($orderData);
        return $razorpayOrder['id'];
    }

    public function payment_verify(array $data): bool
    {

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        try
        {
            $data['status'] = 1;

            $api->utility->verifyPaymentSignature($data);
            return true;
        }
        catch(SignatureVerificationError $e)
        {
            return false;
        }
    }
}
