<?php

namespace App\Modules\Enquiry\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Enquiry\Resources\EnquiryCollection;
use App\Modules\Enquiry\Services\EnquiryService;
use Illuminate\Http\Request;

class EnquiryPaginateController extends Controller
{
    private $enquiryService;

    public function __construct(EnquiryService $enquiryService)
    {
        $this->middleware('permission:list enquiries', ['only' => ['get']]);
        $this->enquiryService = $enquiryService;
    }

    public function get(Request $request){
        $data = $this->enquiryService->paginate($request->total ?? 10);
        return EnquiryCollection::collection($data);
    }

}
