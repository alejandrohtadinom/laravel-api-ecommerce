<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'sub_total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // public function sub_total($item)
    // {
    //    return $item->price * $item->qty;
    // }

    /**
     * Return total value of the current cart
     *
     * @item array
     * @return float
     * */
    public static function total(Array $item) :float
    {
        $total = 0.0;

        foreach ($item as $i) {
            $total += $i['item_sub_total'];
        }

        return $total;
    }
}
