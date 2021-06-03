<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $casts = [
        'product' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'product',
        'sub_total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function product()
    // {
    //     return $this->hasOne(Product::class);
    // }

    public function sub_total($item)
    {
       return $item->price * $item->qty;
    }

    /**
     * Return total value of the current cart
     *
     * @item array
     * @return float
     * */
    public static function total(Array $item)
    {
        $total = 0.0;

        foreach ($item as $i) {
            $total += $i['sub_total'];
        }

        return $total;
    }
}
