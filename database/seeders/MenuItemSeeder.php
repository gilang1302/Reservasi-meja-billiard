<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Makanan Berat
            ['id' => 'MN001', 'name' => 'Nasi Goreng CueMaster', 'category' => 'Food', 'price' => 28000, 'stock' => 50],
            ['id' => 'MN002', 'name' => 'Mie Goreng Telur Spesial', 'category' => 'Food', 'price' => 20000, 'stock' => 40],
            ['id' => 'MN003', 'name' => 'Club Sandwich & Chips', 'category' => 'Food', 'price' => 25000, 'stock' => 30],
            ['id' => 'MN004', 'name' => 'Rice Bowl Chicken Teriyaki', 'category' => 'Food', 'price' => 27000, 'stock' => 35],

            // Cemilan
            ['id' => 'MN005', 'name' => 'French Fries Crispy', 'category' => 'Snack', 'price' => 15000, 'stock' => 60],
            ['id' => 'MN006', 'name' => 'Onion Rings Tartar Sauce', 'category' => 'Snack', 'price' => 17000, 'stock' => 40],
            ['id' => 'MN007', 'name' => 'Nachos Cheese & Salsa', 'category' => 'Snack', 'price' => 22000, 'stock' => 25],
            ['id' => 'MN008', 'name' => 'Platter Mix (Fries, Sausage, Nugget)', 'category' => 'Snack', 'price' => 35000, 'stock' => 20],

            // Minuman
            ['id' => 'MN009', 'name' => 'Es Teh Manis', 'category' => 'Beverage', 'price' => 6000, 'stock' => 100],
            ['id' => 'MN010', 'name' => 'Lemon Tea Fresh', 'category' => 'Beverage', 'price' => 10000, 'stock' => 80],
            ['id' => 'MN011', 'name' => 'Iced Cafe Latte', 'category' => 'Beverage', 'price' => 18000, 'stock' => 50],
            ['id' => 'MN012', 'name' => 'Mineral Water Cold', 'category' => 'Beverage', 'price' => 5000, 'stock' => 150],
            ['id' => 'MN013', 'name' => 'Virgin Mojito Mint', 'category' => 'Beverage', 'price' => 20000, 'stock' => 40],
        ];

        foreach ($items as $item) {
            MenuItem::create($item + [
                'is_available' => true,
                'image' => null,
            ]);
        }
    }
}
