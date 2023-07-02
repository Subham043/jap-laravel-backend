<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function get($receipt){
        $order = $this->orderService->getInvoiceByReceipt($receipt);

        $data = [
            'order' => $order,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data)->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->save(storage_path('app/public/invoices/').$receipt.'.pdf');
        return response()->download(Storage::path('/invoices/'.$receipt.'.pdf'));
        // return view('pdf.invoice', compact('order'));
    }

}
