<?php

namespace App\States;

class ConfirmedState extends ReservationState
{
    public function getStatusName(): string
    {
        return 'Confirmed';
    }

    public function confirm(): void
    {
        throw new \Exception("Reservasi ini sudah dikonfirmasi.");
    }

    public function cancel(): void
    {
        $this->reservation->status = 'Cancelled';
        $this->reservation->save();
    }

    public function getBadgeClass(): string
    {
        return 'bg-success text-white';
    }
}
