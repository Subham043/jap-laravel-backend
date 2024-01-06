<?php

namespace App\Modules\HomePage\Banner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HomePage\Banner\Requests\BannerUpdateRequest;
use App\Modules\HomePage\Banner\Resources\UserBannerCollection;
use App\Modules\HomePage\Banner\Services\BannerService;

class BannerUpdateController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->middleware('permission:edit home page content', ['only' => ['get','post']]);
        $this->bannerService = $bannerService;
    }

    public function post(BannerUpdateRequest $request, $id){
        $banner = $this->bannerService->getById($id);
        try {
            //code...
            $this->bannerService->update(
                $request->except(['banner_image', 'counter_image_1', 'counter_image_2']),
                $banner
            );
            if($request->hasFile('banner_image')){
                $this->bannerService->saveImage($banner);
            }
            return response()->json([
                'message' => "Banner updated successfully.",
                'banner' => UserBannerCollection::make($banner),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Something went wrong. Please try again"], 400);
        }

    }
}
