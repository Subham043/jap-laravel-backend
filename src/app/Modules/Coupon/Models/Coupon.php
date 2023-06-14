<?php

namespace App\Modules\Coupon\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'name',
        'code',
        'description',
        'discount',
        'maximum_dicount_in_price',
        'maximum_number_of_use',
        'user_id',
        'is_active'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
        'discount' => 'float',
        'maximum_dicount_in_price' => 'float',
        'maximum_number_of_use' => 'int',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'coupon_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'coupon_id');
    }
}
