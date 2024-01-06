<?php

namespace App\Modules\Contact\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Exports\ContactExport;
use Maatwebsite\Excel\Facades\Excel;

class ContactExcelController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list enquiries', ['only' => ['get']]);
    }

    public function get(){
        return Excel::download(new ContactExport, 'contact.xlsx');
    }

}
