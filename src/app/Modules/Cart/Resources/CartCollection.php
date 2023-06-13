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
        $total_price_with_coupon = $this->coupon_discount > 0 ? $this->total_price - $this->coupon_discount : $this->total_price;
        return [
            'id' => $this->id,
            'total_items' => $this->products_count,
            'total_quantity' => $total_quantity,
            'sub_total' => $this->sub_total,
            'total_discount' => $this->total_discount,
            'total_price_excluding_coupon' => $this->total_price,
            'total_price_with_coupon' => $total_price_with_coupon,
            'coupon_discount' => $this->coupon_discount,
            'coupon' => CouponCollection::make($this->coupon),
            'products' => ProductCollection::collection($this->products),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
