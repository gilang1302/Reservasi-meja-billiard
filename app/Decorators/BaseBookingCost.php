<?php

namespace App\Decorators;

use App\Models\Booking;

class BaseBookingCost implements BookingCostInterface
{
    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function calculateCost(): float
    {
        // Total price calculated on the booking model or through the strategy context
        return (float) $this->booking->total_price;
    }

    public function getDescription(): string
    {
        return "Sewa Meja #" . $this->booking->table->table_number . " (" . $this->booking->duration_hours . " jam)";
    }
}
