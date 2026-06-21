<?php

namespace App\States\TableState;

use App\Models\BilliardTable;

interface TableStateInterface
{
    public function reserve(BilliardTable $table): void;
    public function startSession(BilliardTable $table): void;
    public function endSession(BilliardTable $table): void;
    public function maintain(BilliardTable $table): void;
    public function release(BilliardTable $table): void;
}
