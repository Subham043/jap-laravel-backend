<?php

namespace App\Modules\Wishlist\Resources;

use App\Http\Services\PriceService;
use App\Modules\Category\Resources\CategoryCollection;
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
        $priceService = new PriceService($this->price, $this->discount);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'discount' => $this->discount,
            'discounted_price' => $priceService->getDiscountedPrice(),
            'inventory' => $this->inventory,
            'in_stock' => $this->inventory > 0 ? true : false,
            'featured_image_link' => asset($this->featured_image),
            'image_title' => $this->image_title,
            'image_alt' => $this->image_alt,
            'is_active' => $this->is_active,
            'is_new_arrival' => $this->is_new_arrival,
            'is_featured' => $this->is_featured,
            'is_best_sale' => $this->is_best_sale,
            'categories' => CategoryCollection::collection($this->categories),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
