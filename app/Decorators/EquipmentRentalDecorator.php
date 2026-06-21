<?php

namespace App\Decorators;

class EquipmentRentalDecorator extends BookingDecorator
{
    public function calculateCost(): float
    {
        $booking = $this->getBooking();
        $equipmentTotal = 0;

        if ($booking && $booking->notes) {
            $data = json_decode($booking->notes, true);
            if (isset($data['rented_items']) && is_array($data['rented_items'])) {
                $duration = $booking->duration_hours;
                foreach ($data['rented_items'] as $item) {
                    // rental price * duration * quantity
                    $equipmentTotal += (float)$item['price'] * $duration * (int)$item['qty'];
                }
            }
        }

        return $this->wrappee->calculateCost() + $equipmentTotal;
    }

    public function getDescription(): string
    {
        $booking = $this->getBooking();
        $equipmentDesc = "";

        if ($booking && $booking->notes) {
            $data = json_decode($booking->notes, true);
            if (isset($data['rented_items']) && is_array($data['rented_items'])) {
                $itemsCount = count($data['rented_items']);
                if ($itemsCount > 0) {
                    $equipmentDesc = " + Sewa Alat (" . $itemsCount . " item)";
                }
            }
        }

        return $this->wrappee->getDescription() . $equipmentDesc;
    }

    protected function getBooking()
    {
        $current = $this->wrappee;
        while ($current instanceof BookingDecorator) {
            $current = $current->wrappee;
        }
        if ($current instanceof BaseBookingCost) {
            $reflection = new \ReflectionClass($current);
            $property = $reflection->getProperty('booking');
            $property->setAccessible(true);
            return $property->getValue($current);
        }
        return null;
    }
}
