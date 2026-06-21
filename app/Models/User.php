<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'user';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'phone',
        'member_type',
        'member_poin',
        'role',
        'total_hours_played',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'member_poin' => 'integer',
        ];
    }

    // ===================== RELATIONSHIPS =====================

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'user_id', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    // ===================== HELPERS =====================

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    public function getMemberLevelAttribute(): string
    {
        return $this->member_type ?? 'Bronze';
    }

    public function getMemberDiscountAttribute(): float
    {
        // Factory Pattern: discount per member type
        return match($this->member_type) {
            'Gold'     => 0.10, // 10% discount
            'Platinum' => 0.20, // 20% discount
            default    => 0.00, // Bronze: no discount
        };
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function getTotalBookingsAttribute(): int
    {
        return $this->bookings()->whereIn('status', ['Completed'])->count();
    }
}
