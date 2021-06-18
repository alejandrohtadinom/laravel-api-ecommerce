<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'items',
        'total',
    ];

    public function client()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Product::class);
    }
}
