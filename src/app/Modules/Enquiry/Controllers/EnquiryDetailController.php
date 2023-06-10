<?php

namespace App\Modules\Enquiry\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Enquiry\Resources\EnquiryCollection;
use App\Modules\Enquiry\Services\EnquiryService;

class EnquiryDetailController extends Controller
{
    private $enquiryService;

    public function __construct(EnquiryService $enquiryService)
    {
        $this->middleware('permission:list enquiries', ['only' => ['get']]);
        $this->enquiryService = $enquiryService;
    }

    public function get($id){
        $enquiry = $this->enquiryService->getById($id);

        return response()->json([
            'enquiry' => EnquiryCollection::make($enquiry),
        ], 200);
    }

}
