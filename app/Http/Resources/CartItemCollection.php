<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;

class CartItemCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'item' => Product::find($this->product_id),
            'qty' => $this->qty,
            'item_sub_total' => $this->item_sub_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
