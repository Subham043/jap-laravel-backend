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
        $total_quantity = $this->products->sum('pivot.quantity');
        $sub_total = 0;
        $total_discount = 0;
        $total_price = 0;
        if($this->products_count > 0){
            foreach ($this->products as $value) {
                # code...
                $total_qt_price = $value->price * $value->pivot->quantity;
                $sub_total = $sub_total + $total_qt_price;
                $discount = $total_qt_price * ($value->discount/100);
                $total_discount = $total_discount + $discount;
                $total_price = $total_price + ($total_qt_price - $discount);
            }
        }
        return [
            'id' => $this->id,
            'total_items' => $this->products_count,
            'total_quantity' => $total_quantity,
            'sub_total' => $sub_total,
            'total_discount' => $total_discount,
            'total_price' => $total_price,
            'products' => ProductCollection::collection($this->products),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
