<?php

namespace App\Modules\Cart\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'total_price',
        'sub_total',
        'total_discount',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'sub_total' => 'float',
        'total_discount' => 'float',
        'total_price' => 'float',
    ];

    protected $attributes = [
        'total_price' => 0,
        'total_discount' => 0,
        'sub_total' => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_products', 'cart_id', 'product_id')->withPivot('quantity');
    }

}
