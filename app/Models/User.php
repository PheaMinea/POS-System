<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected static function booted(): void
    {
        static::created(function (self $user): void {
            if ($user->role === 'customer' && !Customer::where('user_id', $user->id)->exists()) {
                $user->customer()->create([
                    'name' => $user->name,
                    'phone' => $user->phone ?? '',
                    'address' => $user->address ?? null,
                ]);
            }
        });
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'image',
        'google_id',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCashier()
    {
        return $this->role === 'cashier';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}