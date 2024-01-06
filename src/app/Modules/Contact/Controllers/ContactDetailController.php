<?php

namespace App\Modules\Contact\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Resources\ContactCollection;
use App\Modules\Contact\Services\ContactService;

class ContactDetailController extends Controller
{
    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->middleware('permission:list enquiries', ['only' => ['get']]);
        $this->contactService = $contactService;
    }

    public function get($id){
        $contact = $this->contactService->getById($id);

        return response()->json([
            'contact' => ContactCollection::make($contact),
        ], 200);
    }

}
