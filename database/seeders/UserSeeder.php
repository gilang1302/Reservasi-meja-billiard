<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Owner
        User::create([
            'id'          => 'US' . strtoupper(Str::random(9)),
            'name'        => 'Gilang Owner',
            'email'       => 'owner@cuemaster.com',
            'password'    => Hash::make('password123'),
            'phone'       => '081234567890',
            'role'        => 'owner',
            'member_type' => null,
            'member_poin' => 0,
            'total_hours_played' => 0,
        ]);

        // 2. Kasir (Operator)
        User::create([
            'id'          => 'US' . strtoupper(Str::random(9)),
            'name'        => 'Stepanus Kasir',
            'email'       => 'kasir@cuemaster.com',
            'password'    => Hash::make('password123'),
            'phone'       => '081234567891',
            'role'        => 'kasir',
            'member_type' => null,
            'member_poin' => 0,
            'total_hours_played' => 0,
        ]);

        // 3. Pelanggan Platinum
        User::create([
            'id'          => 'US' . strtoupper(Str::random(9)),
            'name'        => 'Maverick Rafael',
            'email'       => 'maverick@gmail.com',
            'password'    => Hash::make('password123'),
            'phone'       => '081234567892',
            'role'        => 'pelanggan',
            'member_type' => 'Platinum',
            'member_poin' => 450,
            'total_hours_played' => 45,
        ]);

        // 4. Pelanggan Gold
        User::create([
            'id'          => 'US' . strtoupper(Str::random(9)),
            'name'        => 'Syahrial Achmad',
            'email'       => 'syahrial@gmail.com',
            'password'    => Hash::make('password123'),
            'phone'       => '081234567893',
            'role'        => 'pelanggan',
            'member_type' => 'Gold',
            'member_poin' => 200,
            'total_hours_played' => 22,
        ]);

        // 5. Pelanggan Bronze
        User::create([
            'id'          => 'US' . strtoupper(Str::random(9)),
            'name'        => 'Daud Panjaitan',
            'email'       => 'daud@gmail.com',
            'password'    => Hash::make('password123'),
            'phone'       => '081234567894',
            'role'        => 'pelanggan',
            'member_type' => 'Bronze',
            'member_poin' => 50,
            'total_hours_played' => 6,
        ]);
    }
}
