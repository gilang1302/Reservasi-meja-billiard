<?php

namespace App\Decorators;

interface BookingCostInterface
{
    public function calculateCost(): float;
    public function getDescription(): string;
}
