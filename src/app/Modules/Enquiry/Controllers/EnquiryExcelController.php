<?php

namespace App\Modules\Enquiry\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Enquiry\Exports\EnquiryExport;
use Maatwebsite\Excel\Facades\Excel;

class EnquiryExcelController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list enquiries', ['only' => ['get']]);
    }

    public function get(){
        return Excel::download(new EnquiryExport, 'enquiry.xlsx');
    }

}
