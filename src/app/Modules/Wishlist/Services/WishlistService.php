<?php

namespace App\Modules\Wishlist\Services;

use App\Modules\Wishlist\Models\Wishlist;

class WishlistService
{

    public function get(): Wishlist|null
    {
        $data = Wishlist::with([
            'products' => function($q) {
                $q->with(['other_images', 'categories']);
            }
        ])->withCount(['products'])->where('user_id', auth()->user()->id)->first();
        if(!$data){
            $data = $data = $this->save(['user_id'=>auth()->user()->id]);
        }
        return $data;
    }

    public function save(array $data): Wishlist
    {
        $wishlist = Wishlist::with([
            'products' => function($q) {
                $q->with(['other_images', 'categories']);
            }
        ])->withCount(['products'])->updateOrCreate(['user_id'=>auth()->user()->id],$data);
        return $wishlist;
    }

}
