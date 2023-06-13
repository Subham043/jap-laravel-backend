<?php

namespace App\Modules\Cart\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponCollection extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'discount' => $this->discount,
            'maximum_dicount_in_price' => $this->maximum_dicount_in_price,
            'maximum_number_of_use' => $this->maximum_number_of_use,
        ];
    }
}
