<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderExcelController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list orders', ['only' => ['get']]);
    }

    public function get(){
        return Excel::download(new OrderExport, 'order.xlsx');
    }

}
