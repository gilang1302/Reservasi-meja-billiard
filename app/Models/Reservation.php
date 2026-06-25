<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\States\ReservationState;
use App\States\PendingState;
use App\States\ConfirmedState;
use App\States\CancelledState;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'table_id',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'payment_method',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the State pattern object for this reservation status.
     */
    public function getState(): ReservationState
    {
        return match ($this->status) {
            'Pending' => new PendingState($this),
            'Confirmed' => new ConfirmedState($this),
            'Cancelled' => new CancelledState($this),
            default => new PendingState($this),
        };
    }

    /**
     * Confirm the reservation using State pattern transitions.
     */
    public function confirm(): void
    {
        $this->getState()->confirm();
    }

    /**
     * Cancel the reservation using State pattern transitions.
     */
    public function cancel(): void
    {
        $this->getState()->cancel();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'reservation_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(ReservationLog::class, 'reservation_id', 'id');
    }
}
