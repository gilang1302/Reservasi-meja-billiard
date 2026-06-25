<?php

namespace App\Strategies;

class CashPaymentStrategy implements PaymentStrategy
{
    public function processPayment(float $amount, array $details): array
    {
        return [
            'status' => 'Pending', // Pending admin/cashier approval
            'transaction_details' => [
                'cashier_instructions' => 'Silakan lakukan pembayaran langsung ke meja kasir CueMaster paling lambat 15 menit sebelum waktu reservasi dimulai.',
                'amount_due' => $amount,
                'payment_location' => 'Meja Kasir Utama',
            ]
        ];
    }
}
