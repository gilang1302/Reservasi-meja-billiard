<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table = 'table';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
    'table_number',
    'table_type',
    'status'
    ];
}
