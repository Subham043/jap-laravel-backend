<?php

namespace App\Modules\Order\Resources;

use App\Modules\Order\Resources\ProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCollection extends JsonResource
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
            'total_items' => $this->products()->count(),
            'total_quantity' => $total_quantity,
            'sub_total' => $this->sub_total,
            'total_discount' => $this->total_discount,
            'total_price_without_gst_delivery_charge' => $this->total_price,
            'gst_charge' => $this->gst_charge,
            'delivery_charge' => $this->delivery_charge,
            'total_price_with_gst_delivery_charge' => round(($this->total_price + $this->gst_charge + $this->delivery_charge), 2),
            'coupon_discount' => $this->coupon_discount,
            'total_price_with_coupon_dicount' => round($total_price_with_coupon, 2),
            'coupon' => CouponCollection::make($this->coupon),
            'products' => ProductCollection::collection($this->products),
            'coupon_name' => $this->coupon_name,
            'coupon_code' => $this->coupon_code,
            'coupon_discount_percentage' => $this->coupon_discount_percentage,
            'coupon_maximum_discount' => $this->coupon_maximum_discount,
            'coupon_maximum_use' => $this->coupon_maximum_use,
            'billing_first_name' => $this->billing_first_name,
            'billing_last_name' => $this->billing_last_name,
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone,
            'billing_country' => $this->billing_country,
            'billing_state' => $this->billing_state,
            'billing_city' => $this->billing_city,
            'billing_pin' => $this->billing_pin,
            'billing_address_1' => $this->billing_address_1,
            'billing_address_2' => $this->billing_address_2,
            'shipping_first_name' => $this->shipping_first_name,
            'shipping_last_name' => $this->shipping_last_name,
            'shipping_email' => $this->shipping_email,
            'shipping_phone' => $this->shipping_phone,
            'shipping_country' => $this->shipping_country,
            'shipping_state' => $this->shipping_state,
            'shipping_city' => $this->shipping_city,
            'shipping_pin' => $this->shipping_pin,
            'shipping_address_1' => $this->shipping_address_1,
            'shipping_address_2' => $this->shipping_address_2,
            'order_notes' => $this->order_notes,
            'receipt' => $this->receipt,
            'mode_of_payment' => $this->mode_of_payment,
            'order_status' => $this->order_status,
            'payment_status' => $this->payment_status,
            'razorpay_order_id' => $this->razorpay_order_id,
            'payment_url' => $this->razorpay_order_id ? route('make_payment', $this->receipt) : null,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
