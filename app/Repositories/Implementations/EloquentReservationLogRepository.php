<?php

namespace App\Repositories\Implementations;

use App\Models\ReservationLog;
use App\Repositories\Interfaces\ReservationLogRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentReservationLogRepository implements ReservationLogRepositoryInterface
{
    public function create(array $data): ReservationLog
    {
        return ReservationLog::create($data);
    }

    public function getLatestLogs(int $limit = 50): Collection
    {
        return ReservationLog::with(['reservation.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getLogsByReservationId(string $reservationId): Collection
    {
        return ReservationLog::with('user')
            ->where('reservation_id', $reservationId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
