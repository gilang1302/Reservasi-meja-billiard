<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $table = 'tables';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'table_number',
        'status',
        'price_per_hour',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id', 'id');
    }
}
