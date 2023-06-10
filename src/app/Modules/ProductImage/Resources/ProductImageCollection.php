<?php

namespace App\Modules\ProductImage\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageCollection extends JsonResource
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
            'image_link' => asset($this->image),
            'image_title' => $this->image_title,
            'image_alt' => $this->image_alt,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
