<?php

namespace App\Modules\Contact\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Requests\ContactUpdateRequest;
use App\Modules\Contact\Resources\ContactCollection;
use App\Modules\Contact\Services\ContactService;

class ContactUpdateController extends Controller
{
    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->middleware('permission:edit enquiries', ['only' => ['post']]);
        $this->contactService = $contactService;
    }

    public function post(ContactUpdateRequest $request, $id){
        $contact = $this->contactService->getById($id);
        try {
            //code...
            $this->contactService->update(
                [...$request->validated()],
                $contact
            );

            return response()->json([
                'message' => "Contact updated successfully.",
                'contact' => ContactCollection::make($contact),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
