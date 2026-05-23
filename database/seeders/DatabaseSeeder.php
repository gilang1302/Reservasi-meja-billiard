<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Table; 
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'id' => 'USR001',
            'name' => 'Maverick Rafael Tanadi',
            'email' => 'Maverick@cuemaster.com',
            'password' => bcrypt('my116688'),
            'role' => 'owner',
        ]);
        User::create([
            'id' => 'USR001',
            'name' => 'Gilang Ardiwilaga',
            'email' => 'Gilang@cuemaster.com',
            'password' => bcrypt('password123'),
            'role' => 'owner',
        ]);
        User::create([
            'id' => 'USR002',
            'name' => 'Lev Kravchenko',
            'email' => 'customer@dummy.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);

        Table::create([
            'id' => 'TBL001',
            'table_number' => '01',
            'status' => 'Available',
            'price_per_hour' => 50000.00,
        ]);

        Table::create([
            'id' => 'TBL002',
            'table_number' => '02',
            'status' => 'Occupied',
            'price_per_hour' => 60000.00,
        ]);

        Table::create([
            'id' => 'TBL003',
            'table_number' => '03',
            'status' => 'Maintenance',
            'price_per_hour' => 50000.00,
        ]);
        Table::create([
            'id' => 'TBL004',
            'table_number' => '04',
            'status' => 'Available',
            'price_per_hour' => 50000.00,
        ]);
        Table::create([
            'id' => 'TBL005',
            'table_number' => '05',
            'status' => 'Available',
            'price_per_hour' => 50000.00,
        ]);
    }
}