<?php

namespace App\Modules\ProductReview\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewCollection extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'star' => $this->star,
            'message' => $this->message,
            'is_approved' => $this->is_approved,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
