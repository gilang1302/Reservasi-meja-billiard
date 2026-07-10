<?php

namespace App\States\TableState;

use App\Models\BilliardTable;

class BookedState implements TableStateInterface
{
    public function reserve(BilliardTable $table): void
    {
        // Already booked
    }

    public function startSession(BilliardTable $table): void
    {
        $table->status = 'Occupied';
        $table->lamp_status = 'on';
        $table->save();
    }

    public function endSession(BilliardTable $table): void
    {
        // Not active yet
    }

    public function maintain(BilliardTable $table): void
    {
        $table->status = 'Maintenance';
        $table->lamp_status = 'off';
        $table->save();
    }

    public function release(BilliardTable $table): void
    {
        $table->status = 'Available';
        $table->lamp_status = 'off';
        $table->save();
    }
}
