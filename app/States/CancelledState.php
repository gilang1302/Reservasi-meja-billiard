<?php

namespace App\States;

class CancelledState extends ReservationState
{
    public function getStatusName(): string
    {
        return 'Cancelled';
    }

    public function confirm(): void
    {
        throw new \Exception("Reservasi yang dibatalkan tidak dapat dikonfirmasi kembali.");
    }

    public function cancel(): void
    {
        throw new \Exception("Reservasi ini sudah dibatalkan sebelumnya.");
    }

    public function getBadgeClass(): string
    {
        return 'bg-danger text-white';
    }
}
