<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_item';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'category',
        'price',
        'stock',
        'description',
        'image',
        'is_available',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'stock'        => 'integer',
        'is_available' => 'boolean',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'menu_item_id', 'id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
