<?php

namespace App\Modules\Order\Controllers;

use App\Enums\PaymentMode;
use App\Http\Controllers\Controller;
use App\Modules\Order\Jobs\SendInvoiceEmailJob;
use App\Modules\Order\Requests\OrderRequest;
use App\Modules\Order\Resources\OrderCollection;
use App\Modules\Order\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;

class PlaceOrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function post(OrderRequest $request){

        try {
            //code...
            $order = $this->orderService->place_order($request->safe()->except(['order', 'coupon_code']));

            if($order->mode_of_payment == PaymentMode::COD){
                $data = [
                    'order' => $order,
                ];
                $pdf = Pdf::loadView('pdf.invoice', $data)->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);
                $pdf->save(storage_path('app/public/invoices/').$order->receipt.'.pdf');
                dispatch(new SendInvoiceEmailJob(auth()->user()->email, $order));
            }

            return response()->json([
                'message' => "Order placed successfully.",
                'order' => OrderCollection::make($order),
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
