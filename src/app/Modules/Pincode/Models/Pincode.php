<?php

namespace App\Modules\Pincode\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pincodes';

    protected $fillable = [
        'state',
        'min_pincode',
        'max_pincode',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'min_pincode' => 'int',
        'max_pincode' => 'int',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_pincodes', 'pincode_id', 'product_id');
    }
}
