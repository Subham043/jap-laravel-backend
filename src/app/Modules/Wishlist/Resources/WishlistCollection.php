<?php

namespace App\Modules\Wishlist\Resources;

use App\Modules\Wishlist\Resources\ProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistCollection extends JsonResource
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
            'total_items' => $this->products_count,
            'products' => ProductCollection::collection($this->products),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
