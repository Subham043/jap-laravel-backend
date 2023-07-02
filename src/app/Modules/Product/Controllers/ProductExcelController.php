<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductExcelController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list products', ['only' => ['get']]);
    }

    public function get(){
        return Excel::download(new ProductExport, 'product.xlsx');
    }

}
