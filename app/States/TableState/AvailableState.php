<?php

namespace App\States\TableState;

use App\Models\BilliardTable;

class AvailableState implements TableStateInterface
{
    public function reserve(BilliardTable $table): void
    {
        $table->status = 'Booked';
        $table->save();
    }

    public function startSession(BilliardTable $table): void
    {
        $table->status = 'Occupied';
        $table->lamp_status = 'on';
        $table->save();
    }

    public function endSession(BilliardTable $table): void
    {
        // Table is already available/not active
    }

    public function maintain(BilliardTable $table): void
    {
        $table->status = 'Maintenance';
        $table->lamp_status = 'off';
        $table->save();
    }

    public function release(BilliardTable $table): void
    {
        // Already released
    }
}
