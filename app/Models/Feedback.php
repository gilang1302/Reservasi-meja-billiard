<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'Feedback';
    protected $keyType = 'string';
    public $incrementing = false;
}
