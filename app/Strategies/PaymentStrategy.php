<?php

namespace App\Strategies;

interface PaymentStrategy
{
    /**
     * Process the payment strategy.
     *
     * @param float $amount
     * @param array $details
     * @return array Returns structured details [status, transaction_details]
     */
    public function processPayment(float $amount, array $details): array;
}
