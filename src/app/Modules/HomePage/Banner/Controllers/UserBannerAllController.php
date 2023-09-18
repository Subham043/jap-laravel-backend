<?php

namespace App\Modules\HomePage\Banner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HomePage\Banner\Resources\UserBannerCollection;
use App\Modules\HomePage\Banner\Services\BannerService;

class UserBannerAllController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function get(){
        $banner = $this->bannerService->main_all();
        return response()->json([
            'message' => "Banner recieved successfully.",
            'banner' => UserBannerCollection::collection($banner),
        ], 200);
    }

}
