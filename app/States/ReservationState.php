<?php

namespace App\States;

use App\Models\Reservation;

abstract class ReservationState
{
    protected Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the name of the state.
     */
    abstract public function getStatusName(): string;

    /**
     * Confirm the reservation.
     *
     * @throws \Exception
     */
    abstract public function confirm(): void;

    /**
     * Cancel the reservation.
     *
     * @throws \Exception
     */
    abstract public function cancel(): void;

    /**
     * Get the Bootstrap badge CSS class for this state.
     */
    abstract public function getBadgeClass(): string;
}
