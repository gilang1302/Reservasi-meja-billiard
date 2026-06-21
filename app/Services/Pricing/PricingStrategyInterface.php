<?php

namespace App\Services\Pricing;

/**
 * Strategy Pattern — Interface untuk kalkulasi harga
 * 
 * Memudahkan perubahan logika perhitungan harga tanpa mengubah kode inti pembayaran.
 * Setiap strategi harga mengimplementasikan interface ini.
 */
interface PricingStrategyInterface
{
    /**
     * Hitung total harga berdasarkan durasi dan harga per jam
     *
     * @param float $pricePerHour Harga per jam meja
     * @param float $durationHours Durasi dalam jam
     * @param array $context Konteks tambahan (user, waktu, dll)
     * @return float Total harga
     */
    public function calculatePrice(float $pricePerHour, float $durationHours, array $context = []): float;

    /**
     * Nama strategi untuk ditampilkan ke user
     */
    public function getStrategyName(): string;
}
