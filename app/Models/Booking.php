<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $table = 'booking';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'table_id',
        'start_time',
        'end_time',
        'total_price',
        'price_type',
        'status',
        'extended_count',
        'notes',
    ];

    protected $casts = [
        'start_time'     => 'datetime',
        'end_time'       => 'datetime',
        'total_price'    => 'decimal:2',
        'extended_count' => 'integer',
    ];

    // ===================== RELATIONSHIPS =====================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function table()
    {
        return $this->belongsTo(BilliardTable::class, 'table_id', 'id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'booking_id', 'id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'booking_id', 'id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'booking_id', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'booking_id', 'id');
    }

    // ===================== HELPERS =====================

    public function getDurationHoursAttribute(): float
    {
        return $this->start_time->diffInMinutes($this->end_time) / 60;
    }

    public function getRemainingMinutesAttribute(): int
    {
        if ($this->status !== 'Active') return 0;
        return max(0, now()->diffInMinutes($this->end_time, false));
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        // Notifikasi jika sisa waktu <= 15 menit
        return $this->status === 'Active' && $this->remaining_minutes <= 15 && $this->remaining_minutes > 0;
    }

    public function canExtend(): bool
    {
        // Bisa extend jika status Active dan meja belum ada booking berikutnya
        if ($this->status !== 'Active') return false;

        $nextBooking = Booking::where('table_id', $this->table_id)
            ->where('id', '!=', $this->id)
            ->where('status', 'Confirmed')
            ->where('start_time', '>=', $this->end_time)
            ->where('start_time', '<', $this->end_time->copy()->addHours(1))
            ->first();

        return $nextBooking === null;
    }

    public function isPaid(): bool
    {
        return $this->transaction && $this->transaction->payment_status === 'Success';
    }

    public function hasFeedback(): bool
    {
        return $this->feedback !== null;
    }
}
