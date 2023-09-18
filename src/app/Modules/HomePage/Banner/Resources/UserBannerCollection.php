<?php

namespace App\Modules\HomePage\Banner\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBannerCollection extends JsonResource
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
            'banner_image_alt' => $this->banner_image_alt,
            'banner_image_title' => $this->banner_image_title,
            'banner_image' => asset($this->banner_image),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
