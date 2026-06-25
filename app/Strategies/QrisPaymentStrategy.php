<?php

namespace App\Strategies;

class QrisPaymentStrategy implements PaymentStrategy
{
    public function processPayment(float $amount, array $details): array
    {
        $reservationId = $details['reservation_id'] ?? 'RES-TEMP';
        
        // Generate a real dynamic QR code URL referencing the reservation ID
        $qrContent = "https://cuemaster.com/pay/" . $reservationId . "?amount=" . $amount;
        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrContent);

        return [
            'status' => 'Pending', // QRIS payments can stay Pending until paid/simulated
            'transaction_details' => [
                'qr_url' => $qrImageUrl,
                'merchant_name' => 'CueMaster Billiard & Lounge',
                'amount_paid' => $amount,
                'qris_id' => 'NMID102030405060',
                'expires_at' => now()->addMinutes(15)->toDateTimeString(),
            ]
        ];
    }
}
