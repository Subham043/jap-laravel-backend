<?php

namespace App\Modules\Order\Exports;

use App\Modules\Order\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'total_price',
            'sub_total',
            'total_discount',
            'gst_charge',
            'delivery_charge',
            'coupon_discount',
            'coupon_id',
            'coupon_name',
            'coupon_code',
            'coupon_discount',
            'coupon_maximum_discount',
            'coupon_maximum_use',
            'billing_first_name',
            'billing_last_name',
            'billing_email',
            'billing_phone',
            'billing_country',
            'billing_state',
            'billing_city',
            'billing_pin',
            'billing_address_1',
            'billing_address_2',
            'order_notes',
            'mode_of_payment',
            'order_status',
            'payment_status',
            'razorpay_order_id',
            'razorpay_payment_id',
            'razorpay_signature',
            'receipt',
            'Created_at',
        ];
    }
    public function map($data): array
    {
         return[
            $data->id,
            $data->total_price,
            $data->sub_total,
            $data->coupon_discount,
            $data->total_discount,
            $data->coupon_name,
            $data->coupon_code,
            $data->coupon_discount_percentage,
            $data->coupon_maximum_discount,
            $data->coupon_maximum_use,
            $data->billing_first_name,
            $data->billing_last_name,
            $data->billing_email,
            $data->billing_phone,
            $data->billing_country,
            $data->billing_state,
            $data->billing_city,
            $data->billing_pin,
            $data->billing_address_1,
            $data->billing_address_2,
            $data->order_notes,
            $data->receipt,
            $data->mode_of_payment->value,
            $data->order_status->value,
            $data->payment_status->value,
            $data->razorpay_signature,
            $data->razorpay_order_id,
            $data->razorpay_payment_id,
            $data->created_at,
         ];
    }
    public function collection()
    {
        return Order::all();
    }
}
