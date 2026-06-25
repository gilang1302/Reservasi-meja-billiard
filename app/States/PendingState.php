<?php

namespace App\States;

class PendingState extends ReservationState
{
    public function getStatusName(): string
    {
        return 'Pending';
    }

    public function confirm(): void
    {
        $this->reservation->status = 'Confirmed';
        $this->reservation->save();
    }

    public function cancel(): void
    {
        $this->reservation->status = 'Cancelled';
        $this->reservation->save();
    }

    public function getBadgeClass(): string
    {
        return 'bg-warning text-dark';
    }
}
