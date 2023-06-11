<?php

namespace App\Modules\Cart\Resources;

use App\Modules\Category\Resources\CategoryCollection;
use App\Modules\ProductImage\Resources\ProductImageCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'discount' => $this->discount,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'featured_image_link' => asset($this->featured_image),
            'image_title' => $this->image_title,
            'image_alt' => $this->image_alt,
            'is_active' => $this->is_active,
            'is_new_arrival' => $this->is_new_arrival,
            'is_featured' => $this->is_featured,
            'is_best_sale' => $this->is_best_sale,
            'quantity' => $this->pivot->quantity,
            'other_images' => ProductImageCollection::collection($this->other_images),
            'categories' => CategoryCollection::collection($this->categories),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
