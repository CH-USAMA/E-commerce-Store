<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $uuid
 * @property string $google_id
 * @property string $google_token
 * @property string $google_refresh_token
 * @property string $role
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'cart_data',
        'google_id',
        'google_token',
        'google_refresh_token',
        'role',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'cart_data' => 'array',
        'permissions' => 'array',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    public function managedStores()
    {
        return $this->belongsToMany(Store::class, 'store_user');
    }

    /**
     * Singular accessor for managed stores, returns the first one.
     */
    public function getManagedStoreAttribute()
    {
        return $this->managedStores->first();
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'admin' && (empty($this->permissions) || in_array('all', $this->permissions));
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission($permission)
    {
        if ($this->role === 'admin' && (empty($this->permissions) || in_array('all', $this->permissions))) {
            return true;
        }

        return is_array($this->permissions) && in_array($permission, $this->permissions);
    }
}
