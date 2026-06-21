<?php

namespace App\Factories;

use App\Models\User;
use Illuminate\Support\Str;

/**
 * Factory Pattern — Pembuatan objek Member berdasarkan tipe
 * 
 * Setiap tipe member (Bronze, Gold, Platinum) memiliki keuntungan berbeda:
 * - Bronze:   Tidak ada diskon
 * - Gold:     Diskon 10%, prioritas booking
 * - Platinum: Diskon 20%, prioritas tertinggi, akses VIP table
 *
 * Factory memastikan objek member dibuat dengan konfigurasi yang tepat.
 */
class MemberFactory
{
    /**
     * Konfigurasi keuntungan per tipe member
     */
    private static array $memberBenefits = [
        'Bronze' => [
            'discount'       => 0.00,
            'priority'       => 1,
            'vip_access'     => false,
            'max_bookings'   => 2,     // Max booking bersamaan
            'points_per_hour' => 10,
            'color'          => '#CD7F32',
            'icon'           => '🥉',
            'description'    => 'Member standar',
        ],
        'Gold' => [
            'discount'        => 0.10,
            'priority'        => 2,
            'vip_access'      => false,
            'max_bookings'    => 4,
            'points_per_hour' => 20,
            'color'           => '#FFD700',
            'icon'            => '🥇',
            'description'     => 'Diskon 10% & prioritas booking',
        ],
        'Platinum' => [
            'discount'        => 0.20,
            'priority'        => 3,
            'vip_access'      => true,
            'max_bookings'    => 6,
            'points_per_hour' => 30,
            'color'           => '#E5E4E2',
            'icon'            => '💎',
            'description'     => 'Diskon 20%, akses VIP, prioritas tertinggi',
        ],
    ];

    /**
     * Buat instance member dengan konfigurasi yang sesuai
     */
    public static function create(string $memberType, array $userData): User
    {
        $benefits = self::$memberBenefits[$memberType] ?? self::$memberBenefits['Bronze'];

        return User::create([
            'id'          => 'US' . strtoupper(Str::random(9)),
            'name'        => $userData['name'],
            'email'       => $userData['email'],
            'password'    => $userData['password'],
            'phone'       => $userData['phone'] ?? null,
            'member_type' => $memberType,
            'member_poin' => 0,
            'role'        => 'pelanggan',
        ]);
    }

    /**
     * Upgrade member ke tipe yang lebih tinggi
     */
    public static function upgrade(User $user): ?string
    {
        $nextTier = match($user->member_type) {
            'Bronze'   => 'Gold',
            'Gold'     => 'Platinum',
            'Platinum' => null, // Sudah tier tertinggi
            default    => null,
        };

        if ($nextTier) {
            $user->update(['member_type' => $nextTier]);
        }

        return $nextTier;
    }

    /**
     * Cek apakah user layak naik tier berdasarkan poin
     */
    public static function checkUpgradeEligibility(User $user): array
    {
        $thresholds = [
            'Gold'     => 500,   // 500 poin untuk naik ke Gold
            'Platinum' => 2000,  // 2000 poin untuk naik ke Platinum
        ];

        return [
            'current_type'    => $user->member_type,
            'current_points'  => $user->member_poin ?? 0,
            'next_tier'       => match($user->member_type) {
                'Bronze' => 'Gold',
                'Gold'   => 'Platinum',
                default  => null,
            },
            'points_needed'   => match($user->member_type) {
                'Bronze' => max(0, $thresholds['Gold'] - ($user->member_poin ?? 0)),
                'Gold'   => max(0, $thresholds['Platinum'] - ($user->member_poin ?? 0)),
                default  => 0,
            },
        ];
    }

    /**
     * Ambil konfigurasi benefit untuk tipe member tertentu
     */
    public static function getBenefits(string $memberType): array
    {
        return self::$memberBenefits[$memberType] ?? self::$memberBenefits['Bronze'];
    }

    /**
     * Semua konfigurasi member
     */
    public static function getAllBenefits(): array
    {
        return self::$memberBenefits;
    }
}
