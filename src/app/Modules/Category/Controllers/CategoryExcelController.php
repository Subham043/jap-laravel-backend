<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;

class CategoryExcelController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list categories', ['only' => ['get']]);
    }

    public function get(){
        return Excel::download(new CategoryExport, 'category.xlsx');
    }

}
