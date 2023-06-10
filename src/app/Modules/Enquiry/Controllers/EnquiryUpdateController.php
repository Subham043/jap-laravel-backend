<?php

namespace App\Modules\Enquiry\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Enquiry\Requests\EnquiryUpdateRequest;
use App\Modules\Enquiry\Resources\EnquiryCollection;
use App\Modules\Enquiry\Services\EnquiryService;

class EnquiryUpdateController extends Controller
{
    private $enquiryService;

    public function __construct(EnquiryService $enquiryService)
    {
        $this->middleware('permission:edit enquiries', ['only' => ['post']]);
        $this->enquiryService = $enquiryService;
    }

    public function post(EnquiryUpdateRequest $request, $id){
        $enquiry = $this->enquiryService->getById($id);
        try {
            //code...
            $this->enquiryService->update(
                [...$request->validated()],
                $enquiry
            );

            return response()->json([
                'message' => "Enquiry updated successfully.",
                'enquiry' => EnquiryCollection::make($enquiry),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
