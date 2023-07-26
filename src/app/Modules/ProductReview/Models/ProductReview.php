<?php

namespace App\Modules\ProductReview\Models;

use App\Modules\Authentication\Models\User;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductReview extends Model
{
    protected $table = 'product_reviews';

    protected $fillable = [
        'name',
        'email',
        'message',
        'star',
        'is_approved',
        'image',
        'product_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_approved' => 'boolean',
    ];

    protected $attributes = [
        'is_approved' => false,
    ];

    public $image_path = 'product';

    protected function image(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => 'storage/'.$this->image_path.'/'.$value,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }
}
