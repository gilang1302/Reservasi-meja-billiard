<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Models\ReservationLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ReservationObserver
{
    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        // 1. Clear calendar events cache
        Cache::forget('calendar_events');

        // 2. Log the action (notifies Admin of new booking)
        ReservationLog::create([
            'reservation_id' => $reservation->id,
            'action' => 'created',
            'old_status' => null,
            'new_status' => $reservation->status,
            'changed_by' => Auth::id() ?: $reservation->user_id,
            'notes' => 'Reservasi baru dibuat oleh pelanggan.',
        ]);
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        // 1. Clear calendar events cache
        Cache::forget('calendar_events');

        // 2. Log status transitions (forms notifications feed)
        if ($reservation->wasChanged('status')) {
            $oldStatus = $reservation->getOriginal('status');
            $newStatus = $reservation->status;
            
            $changedBy = Auth::id() ?: $reservation->user_id;
            $changedByName = Auth::user() ? Auth::user()->name : 'System/Pelanggan';

            $notes = "Status reservasi diubah dari {$oldStatus} menjadi {$newStatus} oleh {$changedByName}.";

            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'action' => 'status_changed',
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $changedBy,
                'notes' => $notes,
            ]);
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        // 1. Clear calendar events cache
        Cache::forget('calendar_events');

        // 2. Log deletion
        ReservationLog::create([
            'reservation_id' => $reservation->id,
            'action' => 'deleted',
            'old_status' => $reservation->status,
            'new_status' => 'Deleted',
            'changed_by' => Auth::id(),
            'notes' => 'Reservasi dihapus dari sistem.',
        ]);
    }
}
