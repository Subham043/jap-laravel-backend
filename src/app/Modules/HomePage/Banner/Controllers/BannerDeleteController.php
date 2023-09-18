<?php

namespace App\Modules\HomePage\Banner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HomePage\Banner\Resources\UserBannerCollection;
use App\Modules\HomePage\Banner\Services\BannerService;

class BannerDeleteController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->middleware('permission:delete home page content', ['only' => ['get']]);
        $this->bannerService = $bannerService;
    }

    public function get($id){
        $banner = $this->bannerService->getById($id);

        try {
            //code...
            $this->bannerService->delete(
                $banner
            );
            return response()->json([
                'message' => "Banner deleted successfully.",
                'banner' => UserBannerCollection::make($banner),
            ], 200);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error_status', 'Something went wrong. Please try again');
        }
    }

}
