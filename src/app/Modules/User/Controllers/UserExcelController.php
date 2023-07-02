<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

class UserExcelController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list users', ['only' => ['get']]);
    }

    public function get(){
        return Excel::download(new UserExport, 'user.xlsx');
    }

}
