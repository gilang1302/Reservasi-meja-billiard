<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
    'customer_name',
    'table_id',
    'booking_date',
    'status'
    ];
}

