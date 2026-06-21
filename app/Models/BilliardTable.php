<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * BilliardTable Model
 * 
 * State Pattern diimplementasikan di sini:
 * Status meja: Available → Booked → Occupied → Available
 *                        ↘ Maintenance
 */
class BilliardTable extends Model
{
    protected $table = 'table';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'table_number',
        'status',
        'price_per_hour',
        'price_peak_per_hour',
        'lamp_status',
        'position_x',
        'position_y',
        'table_type',
        'description',
    ];

    // ===================== RELATIONSHIPS =====================

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'table_id', 'id');
    }

    public function activeBooking()
    {
        return $this->hasOne(Booking::class, 'table_id', 'id')
            ->whereIn('status', ['Confirmed', 'Active'])
            ->latest();
    }

    public function feedbacks()
    {
        return $this->hasManyThrough(Feedback::class, Booking::class, 'table_id', 'booking_id', 'id', 'id');
    }

    // ===================== STATE PATTERN HELPERS =====================

    public function getState(): \App\States\TableState\TableStateInterface
    {
        return match($this->status) {
            'Available'   => new \App\States\TableState\AvailableState(),
            'Occupied'    => new \App\States\TableState\OccupiedState(),
            'Booked'      => new \App\States\TableState\BookedState(),
            'Maintenance' => new \App\States\TableState\MaintenanceState(),
            default       => new \App\States\TableState\AvailableState(),
        };
    }

    public function isAvailable(): bool
    {
        return $this->status === 'Available';
    }

    public function isOccupied(): bool
    {
        return $this->status === 'Occupied';
    }

    public function isBooked(): bool
    {
        return $this->status === 'Booked';
    }

    public function isMaintenance(): bool
    {
        return $this->status === 'Maintenance';
    }

    public function transitionToBooked(): void
    {
        $this->getState()->reserve($this);
    }

    public function transitionToOccupied(): void
    {
        $this->getState()->startSession($this);
    }

    public function transitionToAvailable(): void
    {
        $this->getState()->release($this);
    }

    public function transitionToMaintenance(): void
    {
        $this->getState()->maintain($this);
    }

    public function toggleLamp(): void
    {
        $newStatus = $this->lamp_status === 'on' ? 'off' : 'on';
        $this->update(['lamp_status' => $newStatus]);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Available'   => 'green',
            'Occupied'    => 'red',
            'Booked'      => 'yellow',
            'Maintenance' => 'gray',
            default       => 'gray',
        };
    }

    public function getTotalUsageCountAttribute(): int
    {
        return $this->bookings()->where('status', 'Completed')->count();
    }
}
