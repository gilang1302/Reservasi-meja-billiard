<?php

namespace App\Services\Pricing;

/**
 * Strategy Pattern — Harga Member Discount
 * Diskon berdasarkan tipe member: Bronze 0%, Gold 10%, Platinum 20%
 */
class MemberDiscountPricingStrategy implements PricingStrategyInterface
{
    public function calculatePrice(float $pricePerHour, float $durationHours, array $context = []): float
    {
        $basePrice = $pricePerHour * $durationHours;

        // Context berisi 'member_type' dari user
        $discount = match($context['member_type'] ?? 'Bronze') {
            'Gold'     => 0.10, // Diskon 10%
            'Platinum' => 0.20, // Diskon 20%
            default    => 0.00, // Bronze: tidak ada diskon
        };

        return $basePrice * (1 - $discount);
    }

    public function getStrategyName(): string
    {
        return 'member_discount';
    }
}
