<?php

namespace App\Services\Pricing;

/**
 * Strategy Pattern — Harga Peak Hour
 * Berlaku pada jam 17:00 - 23:00 (padat)
 * Harga menggunakan price_peak_per_hour dari tabel
 */
class PeakHourPricingStrategy implements PricingStrategyInterface
{
    public function calculatePrice(float $pricePerHour, float $durationHours, array $context = []): float
    {
        // Peak hour menggunakan harga peak, bukan harga normal
        // Context berisi 'peak_price' jika tersedia
        $peakPrice = $context['peak_price'] ?? ($pricePerHour * 1.5); // default: 150% dari harga normal
        return $peakPrice * $durationHours;
    }

    public function getStrategyName(): string
    {
        return 'peak';
    }
}
