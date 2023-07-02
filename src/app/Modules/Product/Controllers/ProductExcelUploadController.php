<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Imports\ProductImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ProductExcelUploadController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list products', ['only' => ['get']]);
    }

    public function post(Request $request){
        $rules = array(
            'excel' => ['required','mimes:xls,xlsx'],
        );
        $messages = array(
            'excel.required' => 'Please select an excel !',
            'excel.mimes' => 'Please enter a valid excel !',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }
        try {
            //code...
            Excel::import(new ProductImport, request()->file('excel'));
            return response()->json([
                'message' => "Product uploaded successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong! Please try again.",
            ], 400);
        }
    }

}
