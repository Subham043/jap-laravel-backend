<?php

namespace App\Modules\Contact\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Services\ContactService;

class ContactDeleteController extends Controller
{
    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->middleware('permission:delete enquiries', ['only' => ['delete']]);
        $this->contactService = $contactService;
    }

    public function delete($id){
        $contact = $this->contactService->getById($id);

        try {
            //code...
            $this->contactService->delete(
                $contact
            );
            return response()->json([
                'message' => "Contact deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
