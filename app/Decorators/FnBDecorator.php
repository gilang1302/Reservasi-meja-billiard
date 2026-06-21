<?php

namespace App\Decorators;

use App\Models\Order;

class FnBDecorator extends BookingDecorator
{
    public function calculateCost(): float
    {
        // Ambil semua order makanan/minuman yang bukan Cancelled untuk booking ini
        $bookingId = $this->wrappee instanceof BaseBookingCost
            ? $this->wrappee->calculateCost() // Wait, need to access the booking. Let's query based on booking ID
            : null;
        
        // We can get the booking ID directly from the wrappee or query
        $booking = $this->getBooking();
        $fnbTotal = 0;
        
        if ($booking) {
            $fnbTotal = (float) Order::where('booking_id', $booking->id)
                ->where('status', '!=', 'Cancelled')
                ->sum('total_price');
        }

        return $this->wrappee->calculateCost() + $fnbTotal;
    }

    public function getDescription(): string
    {
        $booking = $this->getBooking();
        $fnbDesc = "";
        
        if ($booking) {
            $ordersCount = Order::where('booking_id', $booking->id)
                ->where('status', '!=', 'Cancelled')
                ->count();
            if ($ordersCount > 0) {
                $fnbDesc = " + F&B ({$ordersCount} pesanan)";
            }
        }
        
        return $this->wrappee->getDescription() . $fnbDesc;
    }

    protected function getBooking()
    {
        // Recursive helper to get booking instance from decorator stack
        $current = $this->wrappee;
        while ($current instanceof BookingDecorator) {
            $current = $current->wrappee;
        }
        if ($current instanceof BaseBookingCost) {
            // Let's make booking property visible or use a public method
            $reflection = new \ReflectionClass($current);
            $property = $reflection->getProperty('booking');
            $property->setAccessible(true);
            return $property->getValue($current);
        }
        return null;
    }
}
