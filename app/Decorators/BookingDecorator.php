<?php

namespace App\Decorators;

abstract class BookingDecorator implements BookingCostInterface
{
    protected $wrappee;

    public function __construct(BookingCostInterface $wrappee)
    {
        $this->wrappee = $wrappee;
    }

    public function calculateCost(): float
    {
        return $this->wrappee->calculateCost();
    }

    public function getDescription(): string
    {
        return $this->wrappee->getDescription();
    }
}
