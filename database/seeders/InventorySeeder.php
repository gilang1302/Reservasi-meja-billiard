<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'id' => 'IV001',
                'item_name' => 'Stik Billiard Carbon Fiber Predator Revo',
                'category' => 'Cue',
                'quantity' => 5,
                'quantity_available' => 5,
                'status' => 'Good',
                'price' => 7500000,
                'rental_price_per_hour' => 15000,
                'notes' => 'Stik premium serat karbon berakurasi tinggi.',
            ],
            [
                'id' => 'IV002',
                'item_name' => 'Stik Billiard Wood Poison VX5',
                'category' => 'Cue',
                'quantity' => 10,
                'quantity_available' => 10,
                'status' => 'Good',
                'price' => 3200000,
                'rental_price_per_hour' => 8000,
                'notes' => 'Stik kayu maple kualitas menengah atas dengan grip karet.',
            ],
            [
                'id' => 'IV003',
                'item_name' => 'Set Bola Billiard Aramith Tournament Duramith',
                'category' => 'Ball',
                'quantity' => 4,
                'quantity_available' => 4,
                'status' => 'Good',
                'price' => 5500000,
                'rental_price_per_hour' => 20000,
                'notes' => 'Set bola turnamen resmi standar internasional.',
            ],
            [
                'id' => 'IV004',
                'item_name' => 'Glove Predator Cue Billiard',
                'category' => 'Accessory',
                'quantity' => 15,
                'quantity_available' => 15,
                'status' => 'Good',
                'price' => 250000,
                'rental_price_per_hour' => 3000,
                'notes' => 'Sarung tangan licin predator 3 jari.',
            ],
            [
                'id' => 'IV005',
                'item_name' => 'Chalk Predator 1080 Blue (pcs)',
                'category' => 'Chalk',
                'quantity' => 20,
                'quantity_available' => 20,
                'status' => 'Good',
                'price' => 45000,
                'rental_price_per_hour' => 0,
                'notes' => 'Kapur stik premium - free untuk rental meja.',
            ]
        ];

        foreach ($items as $item) {
            Inventory::create($item);
        }
    }
}
