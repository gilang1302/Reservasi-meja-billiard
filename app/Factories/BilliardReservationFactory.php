<?php

namespace App\Factories;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Support\Str;

class BilliardReservationFactory extends ReservationFactory
{
    public function createReservation(array $data): Reservation
    {
        $tableId = $data['table_id'];
        $table = Table::findOrFail($tableId);

        // Calculate hours duration
        $startTime = new \DateTime($data['start_time']);
        $endTime = new \DateTime($data['end_time']);
        
        $interval = $startTime->diff($endTime);
        $hours = $interval->h + ($interval->i / 60) + ($interval->days * 24);
        
        if ($hours <= 0) {
            $hours = 1.0; // Default minimum duration
        }

        // Calculate total price
        $totalPrice = $table->price_per_hour * $hours;

        // Generate customized string ID: RES-YYYYMMDD-XXXX
        $datePrefix = now()->format('Ymd');
        $randomSuffix = strtoupper(Str::random(4));
        $resId = "RES-{$datePrefix}-{$randomSuffix}";

        return new Reservation([
            'id' => $resId,
            'user_id' => $data['user_id'],
            'table_id' => $tableId,
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'total_price' => $totalPrice,
            'status' => 'Pending', // Initial status
            'payment_method' => $data['payment_method'],
        ]);
    }
}
