<?php

namespace App\Modules\DeliveryCharge\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryChargeCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'delivery_charges' => $this->delivery_charges,
            'no_delivery_charges_for_cart_total_price_above' => $this->no_delivery_charges_for_cart_total_price_above,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
