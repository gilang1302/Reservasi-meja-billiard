<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'Order';
    protected $keyType = 'string';
    public $incrementing = false;
}
