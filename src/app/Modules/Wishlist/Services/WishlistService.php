<?php

namespace App\Modules\Wishlist\Services;

use App\Modules\Wishlist\Models\Wishlist;
use Illuminate\Support\Facades\DB;

class WishlistService
{

    public function get(): Wishlist|null
    {
        Wishlist::whereNull('user_id')->delete();
        $data = Wishlist::with([
            'products' => function($q) {
                $q->with(['categories']);
            }
        ])->withCount(['products'])->where('user_id', auth()->user()->id)->first();
        if(!$data){
            $data = $data = $this->save(['user_id'=>auth()->user()->id]);
        }else{
            DB::table('wishlist_products')->where('wishlist_id', $data->id)->where(function($q){
                $q->whereNull('product_id');
            })->delete();
        }
        return $data;
    }

    public function save(array $data): Wishlist
    {
        $wishlist = Wishlist::with([
            'products' => function($q) {
                $q->with(['categories']);
            }
        ])->withCount(['products'])->updateOrCreate(['user_id'=>auth()->user()->id],$data);
        return $wishlist;
    }

}
