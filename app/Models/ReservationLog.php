<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationLog extends Model
{
    use HasFactory;

    protected $table = 'reservation_logs';

    protected $fillable = [
        'reservation_id',
        'action',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }
}
