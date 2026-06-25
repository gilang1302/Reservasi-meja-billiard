<?php

namespace App\Services;

use App\Factories\ReservationFactory;
use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\TableRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ReservationService
{
    protected ReservationRepositoryInterface $reservationRepo;
    protected TableRepositoryInterface $tableRepo;
    protected ReservationFactory $factory;

    public function __construct(
        ReservationRepositoryInterface $reservationRepo,
        TableRepositoryInterface $tableRepo,
        ReservationFactory $factory
    ) {
        $this->reservationRepo = $reservationRepo;
        $this->tableRepo = $tableRepo;
        $this->factory = $factory;
    }

    /**
     * Create a new reservation.
     *
     * @param array $data
     * @return Reservation
     * @throws \Exception
     */
    public function createReservation(array $data): Reservation
    {
        // 1. Validate duration and times
        $start = $data['start_time'];
        $end = $data['end_time'];
        if (strtotime($start) >= strtotime($end)) {
            throw new \Exception("Waktu mulai harus lebih awal dari waktu selesai.");
        }

        // 2. Check for time overlap conflicts
        $conflicts = $this->reservationRepo->getConflictingReservations($data['table_id'], $start, $end);
        if ($conflicts->count() > 0) {
            throw new \Exception("Meja billiard pada waktu tersebut sudah dipesan. Silakan pilih meja atau waktu lain.");
        }

        // 3. Utilize Factory Method Pattern to instantiate
        $reservation = $this->factory->createReservation($data);

        // 4. Persist using Repository
        return $this->reservationRepo->create($reservation);
    }

    /**
     * Update reservation status using the State Pattern.
     *
     * @param string $id
     * @param string $status
     * @throws \Exception
     */
    public function updateReservationStatus(string $id, string $status): void
    {
        $reservation = $this->reservationRepo->find($id);
        if (!$reservation) {
            throw new \Exception("Reservasi tidak ditemukan.");
        }

        // Invoke appropriate transition on the State pattern object
        if ($status === 'Confirmed') {
            $reservation->confirm();
        } elseif ($status === 'Cancelled') {
            $reservation->cancel();
        } else {
            throw new \Exception("Status transisi tidak didukung.");
        }
    }

    public function getAllReservations(): Collection
    {
        return $this->reservationRepo->all();
    }

    public function getReservationById(string $id): ?Reservation
    {
        return $this->reservationRepo->find($id);
    }

    public function getReservationsByUserId(string $userId): Collection
    {
        return $this->reservationRepo->getByUserId($userId);
    }

    /**
     * Get reservation events compiled for the calendar.
     *
     * @return array
     */
    public function getCalendarEvents(): array
    {
        return Cache::remember('calendar_events', 3600, function () {
            $reservations = $this->reservationRepo->all();
            $events = [];
            
            foreach ($reservations as $res) {
                if ($res->status === 'Cancelled') {
                    continue;
                }
                
                $color = $res->status === 'Confirmed' ? '#198754' : '#ffc107';
                $textColor = $res->status === 'Confirmed' ? '#ffffff' : '#212529';
                
                $events[] = [
                    'id' => $res->id,
                    'title' => "Meja " . $res->table->table_number . " (" . $res->user->name . ")",
                    'start' => $res->start_time->toIso8601String(),
                    'end' => $res->end_time->toIso8601String(),
                    'color' => $color,
                    'textColor' => $textColor,
                    'extendedProps' => [
                        'table_number' => $res->table->table_number,
                        'customer' => $res->user->name,
                        'status' => $res->status,
                    ]
                ];
            }
            
            return $events;
        });
    }
}
