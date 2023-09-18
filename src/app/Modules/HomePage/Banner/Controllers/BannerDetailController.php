<?php

namespace App\Modules\HomePage\Banner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HomePage\Banner\Requests\BannerUpdateRequest;
use App\Modules\HomePage\Banner\Resources\UserBannerCollection;
use App\Modules\HomePage\Banner\Services\BannerService;

class BannerDetailController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->middleware('permission:list home page content', ['only' => ['get','post']]);
        $this->bannerService = $bannerService;
    }

    public function get($id){
        $banner = $this->bannerService->getById($id);
        return response()->json([
            'message' => "Banner recieved successfully.",
            'banner' => UserBannerCollection::make($banner),
        ], 200);
    }
}
