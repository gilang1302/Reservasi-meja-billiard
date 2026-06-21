<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'booking_id',
        'payment_method',
        'payment_status',
        'total_amount',
        'payment_proof',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'verified_at'  => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'BCA'   => 'Transfer BCA',
            'QRIS'  => 'QRIS / QR Code',
            'Tunai' => 'Tunai / Cash',
            default => $this->payment_method,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'Pending' => 'warning',
            'Success' => 'success',
            'Failed'  => 'danger',
            default   => 'secondary',
        };
    }
}
