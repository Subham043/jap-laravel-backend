<?php

namespace App\Modules\HomePage\Banner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HomePage\Banner\Resources\UserBannerCollection;
use App\Modules\HomePage\Banner\Services\BannerService;
use Illuminate\Http\Request;

class BannerPaginateController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->middleware('permission:list home page content', ['only' => ['get']]);
        $this->bannerService = $bannerService;
    }

    public function get(Request $request){
        $data = $this->bannerService->paginate($request->total ?? 10);
        return UserBannerCollection::collection($data);
    }

}
