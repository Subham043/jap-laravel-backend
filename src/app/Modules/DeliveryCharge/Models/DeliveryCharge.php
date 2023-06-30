<?php

namespace App\Modules\DeliveryCharge\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    protected $table = 'delivery_charges';

    protected $fillable = [
        'delivery_charges_slug',
        'delivery_charges',
        'no_delivery_charges_for_cart_total_price_above',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'delivery_charges' => 'float',
        'no_delivery_charges_for_cart_total_price_above' => 'float',
    ];

    protected $attributes = [
        'delivery_charges' => 0,
        'no_delivery_charges_for_cart_total_price_above' => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

}
