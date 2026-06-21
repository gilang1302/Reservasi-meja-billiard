<?php

namespace Database\Seeders;

use App\Models\BilliardTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            // Standard Tables
            ['id' => 'TB001', 'table_number' => '1', 'table_type' => 'Standard', 'price_per_hour' => 30000, 'price_peak_per_hour' => 45000, 'position_x' => 1, 'position_y' => 1, 'description' => 'Dekat pintu masuk, AC dingin.'],
            ['id' => 'TB002', 'table_number' => '2', 'table_type' => 'Standard', 'price_per_hour' => 30000, 'price_peak_per_hour' => 45000, 'position_x' => 2, 'position_y' => 1, 'description' => 'Dekat minibar, pencahayaan terang.'],
            ['id' => 'TB003', 'table_number' => '3', 'table_type' => 'Standard', 'price_per_hour' => 30000, 'price_peak_per_hour' => 45000, 'position_x' => 3, 'position_y' => 1, 'description' => 'Meja tengah, sirkulasi udara baik.'],
            ['id' => 'TB004', 'table_number' => '4', 'table_type' => 'Standard', 'price_per_hour' => 30000, 'price_peak_per_hour' => 45000, 'position_x' => 4, 'position_y' => 1, 'description' => 'Dekat toilet, akses mudah.'],
            ['id' => 'TB005', 'table_number' => '5', 'table_type' => 'Standard', 'price_per_hour' => 30000, 'price_peak_per_hour' => 45000, 'position_x' => 1, 'position_y' => 2, 'description' => 'Sudut kiri, tenang dan nyaman.'],
            ['id' => 'TB006', 'table_number' => '6', 'table_type' => 'Standard', 'price_per_hour' => 30000, 'price_peak_per_hour' => 45000, 'position_x' => 2, 'position_y' => 2, 'description' => 'Meja tengah, dekat dengan sofa penonton.'],
            ['id' => 'TB007', 'table_number' => '7', 'table_type' => 'Standard', 'price_per_hour' => 30000, 'price_peak_per_hour' => 45000, 'position_x' => 3, 'position_y' => 2, 'description' => 'Dekat dengan kasir, pelayanan cepat.'],
            ['id' => 'TB008', 'table_number' => '8', 'table_type' => 'Standard', 'price_per_hour' => 30000, 'price_peak_per_hour' => 45000, 'position_x' => 4, 'position_y' => 2, 'description' => 'Sudut kanan bawah, private vibes.'],
            
            // VIP Tables (Premium area, higher price)
            ['id' => 'TB009', 'table_number' => 'V1', 'table_type' => 'VIP', 'price_per_hour' => 50000, 'price_peak_per_hour' => 75000, 'position_x' => 1, 'position_y' => 3, 'description' => 'VIP Room A - Sofa eksklusif, AC pribadi, mini bar.'],
            ['id' => 'TB010', 'table_number' => 'V2', 'table_type' => 'VIP', 'price_per_hour' => 50000, 'price_peak_per_hour' => 75000, 'position_x' => 2, 'position_y' => 3, 'description' => 'VIP Room B - Smart TV, sound system, room service.'],

            // Tournament Tables (Pro cloths, Aramith balls)
            ['id' => 'TB011', 'table_number' => 'T1', 'table_type' => 'Tournament', 'price_per_hour' => 60000, 'price_peak_per_hour' => 90000, 'position_x' => 3, 'position_y' => 3, 'description' => 'Meja Turnamen Xingjue - Kain Andy Pro, Bola Aramith.'],
            ['id' => 'TB012', 'table_number' => 'T2', 'table_type' => 'Tournament', 'price_per_hour' => 60000, 'price_peak_per_hour' => 90000, 'position_x' => 4, 'position_y' => 3, 'description' => 'Meja Turnamen Wiraka L1 - Kain Hainsworth Match, Bola Aramith.'],
        ];

        foreach ($tables as $table) {
            BilliardTable::create($table + [
                'status' => 'Available',
                'lamp_status' => 'off',
            ]);
        }
    }
}
