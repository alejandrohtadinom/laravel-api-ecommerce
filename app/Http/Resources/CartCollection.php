<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;

class CartCollection extends JsonResource
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
            'user_id' => $this->user_id,
            'product' => new ProductCollection(Product::find($this->product->id)),
            'sub_total' => $this->sub_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
