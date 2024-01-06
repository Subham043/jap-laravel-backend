<?php

namespace App\Modules\Order\Resources;

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
        $priceService = new PriceService($this->pivot->price, $this->pivot->discount);
        return [
            'id' => $this->id,
            'name' => $this->pivot->name,
            'slug' => $this->pivot->slug,
            'description' => $this->pivot->description,
            'price' => $this->pivot->price,
            'discount' => $this->pivot->discount,
            'discounted_price' => $priceService->getDiscountedPrice(),
            'weight' => $this->pivot->weight,
            'inventory' => $this->inventory,
            'in_stock' => $this->inventory > 0 ? true : false,
            'featured_image_link' => asset($this->pivot->image),
            'image_title' => $this->image_title,
            'image_alt' => $this->image_alt,
            'is_active' => $this->is_active,
            'is_new_arrival' => $this->is_new_arrival,
            'is_featured' => $this->is_featured,
            'is_best_sale' => $this->is_best_sale,
            'quantity' => $this->pivot->quantity,
            'total_quantity_price' => $priceService->getPriceWithQuantity($this->pivot->quantity),
            'categories' => CategoryCollection::collection($this->categories),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
