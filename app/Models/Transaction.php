<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'Transaction';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
    'booking_id',
    'amount',
    'status'
    ];
}
