<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'item_name',
        'category',
        'quantity',
        'quantity_available',
        'status',
        'price',
        'rental_price_per_hour',
        'notes',
    ];

    protected $casts = [
        'price'                  => 'decimal:2',
        'rental_price_per_hour'  => 'decimal:2',
        'quantity'               => 'integer',
        'quantity_available'     => 'integer',
    ];

    public function getAvailabilityLabelAttribute(): string
    {
        return "{$this->quantity_available}/{$this->quantity} tersedia";
    }

    public function getFormattedRentalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->rental_price_per_hour, 0, ',', '.') . '/jam';
    }
}
