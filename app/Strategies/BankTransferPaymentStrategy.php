<?php

namespace App\Strategies;

class BankTransferPaymentStrategy implements PaymentStrategy
{
    public function processPayment(float $amount, array $details): array
    {
        $bank = $details['bank'] ?? 'BCA';
        $phone = $details['phone'] ?? '081234567890';
        
        // Generate mock VA code based on bank prefix
        $bankPrefix = match (strtoupper($bank)) {
            'BCA' => '88390',
            'MANDIRI' => '89608',
            'BNI' => '82740',
            default => '88888',
        };

        // Last 10 digits based on telephone or random numbers
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $vaSuffix = substr($cleanPhone, -8);
        if (strlen($vaSuffix) < 8) {
            $vaSuffix = str_pad($vaSuffix, 8, '0', STR_PAD_LEFT);
        }
        
        $vaNumber = $bankPrefix . $vaSuffix;

        return [
            'status' => 'Pending',
            'transaction_details' => [
                'bank_name' => strtoupper($bank),
                'virtual_account' => $vaNumber,
                'account_name' => 'CueMaster Reservasi',
                'amount_paid' => $amount,
                'expires_at' => now()->addHours(2)->toDateTimeString(),
            ]
        ];
    }
}
