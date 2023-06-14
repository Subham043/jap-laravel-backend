<?php

namespace App\Modules\Order\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMode;
use App\Enums\PaymentStatus;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'total_price',
        'sub_total',
        'total_discount',
        'coupon_discount',
        'coupon_id',
        'user_id',
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
        'shipping_first_name',
        'shipping_last_name',
        'shipping_email',
        'shipping_phone',
        'shipping_country',
        'shipping_state',
        'shipping_city',
        'shipping_pin',
        'shipping_address_1',
        'shipping_address_2',
        'order_notes',
        'mode_of_payment',
        'order_status',
        'payment_status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'receipt',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'sub_total' => 'float',
        'total_discount' => 'float',
        'total_price' => 'float',
        'coupon_discount' => 'float',
        'mode_of_payment' => PaymentMode::class,
        'order_status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    protected $attributes = [
        'total_price' => 0,
        'total_discount' => 0,
        'sub_total' => 0,
        'coupon_discount' => 0,
        'mode_of_payment' => PaymentMode::COD->value,
        'order_status' => OrderStatus::PROCESSING->value,
        'payment_status' => PaymentStatus::PENDING->value,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')->withPivot('quantity');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id')->withDefault();
    }

}
