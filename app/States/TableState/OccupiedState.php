<?php

namespace App\States\TableState;

use App\Models\BilliardTable;

class OccupiedState implements TableStateInterface
{
    public function reserve(BilliardTable $table): void
    {
        // Cannot reserve active table directly, booking is scheduled
    }

    public function startSession(BilliardTable $table): void
    {
        // Already active
    }

    public function endSession(BilliardTable $table): void
    {
        $table->status = 'Available';
        $table->lamp_status = 'off';
        $table->save();
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
