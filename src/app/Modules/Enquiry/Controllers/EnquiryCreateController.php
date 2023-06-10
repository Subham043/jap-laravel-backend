<?php

namespace App\Modules\Enquiry\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Enquiry\Requests\EnquiryCreateRequest;
use App\Modules\Enquiry\Resources\EnquiryCollection;
use App\Modules\Enquiry\Services\EnquiryService;

class EnquiryCreateController extends Controller
{
    private $enquiryService;

    public function __construct(EnquiryService $enquiryService)
    {
        $this->enquiryService = $enquiryService;
    }

    public function post(EnquiryCreateRequest $request){

        try {
            //code...
            $enquiry = $this->enquiryService->create(
                [
                    ...$request->validated(),
                ]
            );

            return response()->json([
                'message' => "Enquiry created successfully.",
                'enquiry' => EnquiryCollection::make($enquiry),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
