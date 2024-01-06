<?php

namespace App\Modules\Contact\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Requests\ContactCreateRequest;
use App\Modules\Contact\Resources\ContactCollection;
use App\Modules\Contact\Services\ContactService;

class ContactCreateController extends Controller
{
    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function post(ContactCreateRequest $request){

        try {
            //code...
            $contact = $this->contactService->create(
                [
                    ...$request->validated(),
                ]
            );

            return response()->json([
                'message' => "Contact created successfully.",
                'contact' => ContactCollection::make($contact),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
