<?php

namespace App\Modules\Enquiry\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Enquiry\Services\EnquiryService;

class EnquiryDeleteController extends Controller
{
    private $enquiryService;

    public function __construct(EnquiryService $enquiryService)
    {
        $this->middleware('permission:delete enquiries', ['only' => ['delete']]);
        $this->enquiryService = $enquiryService;
    }

    public function delete($id){
        $enquiry = $this->enquiryService->getById($id);

        try {
            //code...
            $this->enquiryService->delete(
                $enquiry
            );
            return response()->json([
                'message' => "Enquiry deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
