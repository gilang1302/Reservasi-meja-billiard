<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'OrderDetail';
    protected $keyType = 'string';
    public $incrementing = false;
}
