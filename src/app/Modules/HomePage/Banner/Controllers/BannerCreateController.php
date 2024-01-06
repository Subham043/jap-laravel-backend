<?php

namespace App\Modules\HomePage\Banner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HomePage\Banner\Requests\BannerCreateRequest;
use App\Modules\HomePage\Banner\Resources\UserBannerCollection;
use App\Modules\HomePage\Banner\Services\BannerService;

class BannerCreateController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->middleware('permission:create home page content', ['only' => ['get','post']]);
        $this->bannerService = $bannerService;
    }

    public function post(BannerCreateRequest $request){

        try {
            //code...
            $banner = $this->bannerService->create(
                $request->except(['banner_image'])
            );
            if($request->hasFile('banner_image')){
                $this->bannerService->saveImage($banner);
            }
            return response()->json([
                'message' => "Banner created successfully.",
                'banner' => UserBannerCollection::make($banner),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Something went wrong. Please try again"], 400);
        }

    }
}
