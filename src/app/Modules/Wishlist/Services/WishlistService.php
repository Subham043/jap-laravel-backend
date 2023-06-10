<?php

namespace App\Modules\Wishlist\Services;

use App\Modules\Wishlist\Models\Wishlist;

class WishlistService
{

    public function get(): Wishlist|null
    {
        return Wishlist::with(['products'])->where('user_id', auth()->user()->id)->first();
    }

    public function save(array $data): Wishlist
    {
        $wishlist = Wishlist::updateOrCreate(['user_id'=>auth()->user()->id],$data);
        return $wishlist;
    }

}
