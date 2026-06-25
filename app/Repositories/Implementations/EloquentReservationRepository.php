<?php

namespace App\Repositories\Implementations;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function all(): Collection
    {
        return Reservation::with(['user', 'table', 'payment'])->orderBy('created_at', 'desc')->get();
    }

    public function find(string $id): ?Reservation
    {
        return Reservation::with(['user', 'table', 'payment'])->find($id);
    }

    public function create(Reservation $reservation): Reservation
    {
        $reservation->save();
        return $reservation;
    }

    public function update(string $id, array $data): bool
    {
        $reservation = Reservation::find($id);
        if ($reservation) {
            return $reservation->update($data);
        }
        return false;
    }

    public function getByUserId(string $userId): Collection
    {
        return Reservation::with(['table', 'payment'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getConflictingReservations(string $tableId, string $startTime, string $endTime, ?string $excludeReservationId = null): Collection
    {
        $query = Reservation::where('table_id', $tableId)
            ->where('status', '!=', 'Cancelled')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return $query->get();
    }
}
