<?php

namespace App\Repositories\Interfaces;

use App\Models\ReservationLog;
use Illuminate\Support\Collection;

interface ReservationLogRepositoryInterface
{
    public function create(array $data): ReservationLog;
    
    public function getLatestLogs(int $limit = 50): Collection;
    
    public function getLogsByReservationId(string $reservationId): Collection;
}
