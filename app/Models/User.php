<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Returns the billing profile assosiate to the user
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne Profile
     */
    public function profile(): Profile
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Returns the billing profile assosiate to the user
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne Cart
     */
    public function cart(): Cart
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Return true if the current user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * Return true if the current user is staff
     *
     * @return bool
     */
    public function isStaff(): bool
    {
        return $this->staff;
    }

    /**
     * Return true if the current user is supplyer
     *
     * @return bool
     */
    public function isSupplyer(): bool
    {
        return $this->supplyer;
    }
}
