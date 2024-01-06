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
        $total_price_with_coupon = $this->coupon_discount > 0 ? ($this->total_price + $this->gst_charge + $this->delivery_charge) - $this->coupon_discount : ($this->total_price + $this->gst_charge + $this->delivery_charge);
        return [
            'id' => $this->id,
            'total_items' => $this->products_count,
            'total_quantity' => $total_quantity,
            'sub_total' => $this->sub_total,
            'total_discount' => $this->total_discount,
            'total_price_without_gst_delivery_charge' => $this->total_price,
            'gst_charge' => $this->gst_charge,
            'delivery_charge' => $this->delivery_charge,
            'total_price_with_gst_delivery_charge' => round(($this->total_price + $this->gst_charge + $this->delivery_charge), 2),
            'coupon_discount' => $this->coupon_discount,
            'total_price_with_coupon_dicount' => round($total_price_with_coupon, 2),
            'gst_charge' => $this->gst_charge,
            'delivery_charge' => $this->delivery_charge,
            'coupon' => CouponCollection::make($this->coupon),
            'products' => ProductCollection::collection($this->products),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
