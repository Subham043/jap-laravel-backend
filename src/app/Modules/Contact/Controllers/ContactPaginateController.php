<?php

namespace App\Modules\Contact\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Resources\ContactCollection;
use App\Modules\Contact\Services\ContactService;
use Illuminate\Http\Request;

class ContactPaginateController extends Controller
{
    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->middleware('permission:list enquiries', ['only' => ['get']]);
        $this->contactService = $contactService;
    }

    public function get(Request $request){
        $data = $this->contactService->paginate($request->total ?? 10);
        return ContactCollection::collection($data);
    }

}
