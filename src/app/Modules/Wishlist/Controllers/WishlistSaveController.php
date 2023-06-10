<?php

namespace App\Modules\Wishlist\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Wishlist\Requests\WishlistRequest;
use App\Modules\Wishlist\Resources\WishlistCollection;
use App\Modules\Wishlist\Services\WishlistService;

class WishlistSaveController extends Controller
{
    private $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function post(WishlistRequest $request){

        try {
            //code...
            $wishlist = $this->wishlistService->save(
                [
                    ...$request->safe()->except(['product']),
                ]
            );

            if($request->product && count($request->product)>0){
                $wishlist->products()->sync($request->product);
            }else{
                $wishlist->products()->sync([]);
            }

            return response()->json([
                'message' => "Wishlist created successfully.",
                'wishlist' => WishlistCollection::make($wishlist),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
