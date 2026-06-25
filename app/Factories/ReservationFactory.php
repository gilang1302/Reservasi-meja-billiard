<?php

namespace App\Factories;

use App\Models\Reservation;

abstract class ReservationFactory
{
    /**
     * Factory Method to instantiate a Reservation model.
     *
     * @param array $data
     * @return Reservation
     */
    abstract public function createReservation(array $data): Reservation;
}
