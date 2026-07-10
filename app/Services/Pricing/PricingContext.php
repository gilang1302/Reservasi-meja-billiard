<?php

namespace App\Services\Pricing;

use App\Models\BilliardTable;
use App\Models\User;
use Carbon\Carbon;

/**
 * Strategy Pattern — Context untuk kalkulasi harga
 * 
 * Menentukan strategi yang tepat berdasarkan waktu dan tipe member,
 * kemudian mendelegasikan kalkulasi ke strategi yang dipilih.
 */
class PricingContext
{
    private PricingStrategyInterface $strategy;

    /**
     * Pilih strategi berdasarkan waktu booking dan tipe member.
     * Peak hour: 17:00 - 23:00
     */
    public function selectStrategy(Carbon $startTime, ?User $user = null): void
    {
        $hour = (int) $startTime->format('H');
        $isPeakHour = $hour >= 17 && $hour < 23;
        $memberType = $user?->member_type ?? 'Bronze';

        if ($isPeakHour) {
            // Peak hour mengalahkan semua strategi lain
            $this->strategy = new PeakHourPricingStrategy();
        } elseif ($memberType !== 'Bronze' && $memberType !== null) {
            // Member Gold/Platinum mendapat diskon di off-peak
            $this->strategy = new MemberDiscountPricingStrategy();
        } else {
            // Default: harga normal
            $this->strategy = new NormalPricingStrategy();
        }
    }

    public function setStrategy(PricingStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * Hitung total harga booking
     *
     * @param BilliardTable $table Meja yang dipesan
     * @param float $durationHours Durasi dalam jam
     * @param array $context Konteks tambahan
     * @return array ['total' => float, 'type' => string, 'per_hour' => float]
     */
    public function calculate(BilliardTable $table, float $durationHours, array $context = []): array
    {
        $context['peak_price'] = $table->price_peak_per_hour;
        $context['member_type'] = $context['member_type'] ?? 'Bronze';

        $total = $this->strategy->calculatePrice(
            $table->price_per_hour,
            $durationHours,
            $context
        );

        return [
            'total'    => round($total),
            'type'     => $this->strategy->getStrategyName(),
            'per_hour' => round($total / $durationHours),
        ];
    }

    public function getStrategyName(): string
    {
        return $this->strategy->getStrategyName();
    }

    /**
     * Periksa apakah jam tertentu adalah peak hour
     */
    public static function isPeakHour(Carbon $time): bool
    {
        $hour = (int) $time->format('H');
        return $hour >= 17 && $hour < 23;
    }
}
