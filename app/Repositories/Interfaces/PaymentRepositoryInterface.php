<?php

namespace App\Repositories\Interfaces;

use App\Models\Payment;
use Illuminate\Support\Collection;

interface PaymentRepositoryInterface
{
    public function all(): Collection;
    
    public function find(string $id): ?Payment;
    
    public function findByReservationId(string $reservationId): ?Payment;
    
    public function create(array $data): Payment;
    
    public function updateStatus(string $id, string $status): bool;
}
