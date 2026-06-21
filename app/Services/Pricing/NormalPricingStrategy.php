<?php

namespace App\Services\Pricing;

/**
 * Strategy Pattern — Harga Normal (Off-Peak)
 * Berlaku pada jam 07:00 - 17:00
 */
class NormalPricingStrategy implements PricingStrategyInterface
{
    public function calculatePrice(float $pricePerHour, float $durationHours, array $context = []): float
    {
        // Harga normal: harga per jam × durasi
        return $pricePerHour * $durationHours;
    }

    public function getStrategyName(): string
    {
        return 'normal';
    }
}
