<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentGatewayInterface;

class PaymentService
{
    public function __construct(private readonly PaymentGatewayInterface $gateway) {}

    public function initialize(Order $order)
    {
        if ($order->status !== 'pending') {
            throw new \RuntimeException('Payments can only be made for pending orders.');
        }

        // Prevent duplicate successful payments
        if ($order->payments()->where('status', 'successful')->exists()) {
            throw new \RuntimeException('Payment already made for this order.');
        }

        DB::beginTransaction();

        try {
            $payment = Payment::create([
                'order_id' => $order->id,
                'gateway'  => $this->gateway->getName(),
                'amount'   => $order->total,
                'currency' => $order->currency,
                'status'   => 'initialized',
            ]);

            $result = $this->gateway->initializePayment($order, $payment);

            DB::commit();

            return [
                'client_secret' => $result->clientSecret,
                'reference'     => $result->reference,
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
