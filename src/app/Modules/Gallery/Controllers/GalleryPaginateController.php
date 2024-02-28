<?php

namespace App\Modules\Gallery\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Resources\GalleryCollection;
use App\Modules\Gallery\Services\GalleryService;
use Illuminate\Http\Request;

class GalleryPaginateController extends Controller
{
    private $productService;

    public function __construct(GalleryService $productService)
    {
        $this->middleware('permission:list products', ['only' => ['get']]);
        $this->productService = $productService;
    }

    public function get(Request $request){
        $data = $this->productService->paginate($request->total ?? 10);
        return GalleryCollection::collection($data);
    }

}
