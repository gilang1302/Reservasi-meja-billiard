<?php

namespace App\Repositories\Interfaces;

use App\Models\Reservation;
use Illuminate\Support\Collection;

interface ReservationRepositoryInterface
{
    public function all(): Collection;
    
    public function find(string $id): ?Reservation;
    
    public function create(Reservation $reservation): Reservation;
    
    public function update(string $id, array $data): bool;
    
    public function getByUserId(string $userId): Collection;
    
    public function getConflictingReservations(string $tableId, string $startTime, string $endTime, ?string $excludeReservationId = null): Collection;
}
