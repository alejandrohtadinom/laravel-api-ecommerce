<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // protected $casts = [
    //     'producst' => AsArrayObject::class,
    // ];

    protected $fillable = [
        'user_id',
        'product_id',
        'name',
        'description',
        'price',
        'qty',
        'sub_total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function total($carts)
    {
        $total = 0.0;

        foreach ($carts as $cart) {
            $total += $cart->sub_total;
        }

        return $total;
    }
}
