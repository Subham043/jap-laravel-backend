<?php

namespace App\Modules\Cart\Resources;

use App\Modules\Cart\Resources\ProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CartCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $total_quantity = $this->products->sum('pivot.quantity');
        // $sub_total = $this->products->sum('price') * $total_quantity;
        // // $sub_total = $this->products->sum('price');
        // // $sub_discount = ($this->products->sum('discount')/100) * $total_quantity;
        // $sub_discount = ($this->products->sum('discount')/100);
        // $total_discount = $sub_total * $sub_discount;
        // $total_price = $sub_total - $total_discount;
        return [
            'id' => $this->id,
            // 'total_price' => $total_price,
            'total_items' => $this->products_count,
            // 'total_quantity' => $total_quantity,
            // 'sub_total' => $sub_total,
            // 'total_discount' => $total_discount,
            'products' => ProductCollection::collection($this->products),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
