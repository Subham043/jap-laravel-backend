<?php

namespace App\Modules\Cart\Models;

use App\Modules\Authentication\Models\User;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'total_price',
        'sub_total',
        'total_discount',
        'coupon_discount',
        'gst_charge',
        'delivery_charge',
        'coupon_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'sub_total' => 'float',
        'total_discount' => 'float',
        'total_price' => 'float',
        'coupon_discount' => 'float',
        'gst_charge' => 'float',
        'delivery_charge' => 'float',
    ];

    protected $attributes = [
        'total_price' => 0,
        'total_discount' => 0,
        'sub_total' => 0,
        'coupon_discount' => 0,
        'gst_charge' => 0,
        'delivery_charge' => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_products', 'cart_id', 'product_id')->withPivot('quantity');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id')->withDefault();
    }

}
