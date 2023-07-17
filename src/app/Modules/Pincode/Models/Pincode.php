<?php

namespace App\Modules\Pincode\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pincodes';

    protected $fillable = [
        'place',
        'pincode',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'pincode' => 'int',
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
