<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'booking_id',
        'type',
        'title',
        'message',
        'is_read',
        'sent_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'booking_reminder'   => '⏰',
            'booking_confirmed'  => '✅',
            'booking_cancelled'  => '❌',
            'payment_success'    => '💰',
            'extend_time'        => '⏱️',
            'fnb_ready'          => '🍔',
            default              => '🔔',
        };
    }
}
