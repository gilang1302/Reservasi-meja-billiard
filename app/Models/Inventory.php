<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'Inventory';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
    'item_name',
    'quantity'
    ];
}
