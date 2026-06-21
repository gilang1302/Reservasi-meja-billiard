<?php

namespace App\States\TableState;

use App\Models\BilliardTable;

class MaintenanceState implements TableStateInterface
{
    public function reserve(BilliardTable $table): void
    {
        // Cannot reserve in maintenance
    }

    public function startSession(BilliardTable $table): void
    {
        // Cannot play in maintenance
    }

    public function endSession(BilliardTable $table): void
    {
        // No session
    }

    public function maintain(BilliardTable $table): void
    {
        // Already in maintenance
    }

    public function release(BilliardTable $table): void
    {
        $table->status = 'Available';
        $table->lamp_status = 'off';
        $table->save();
    }
}
