<?php

namespace App\Modules\Product\Models;

use App\Modules\Cart\Models\Cart;
use App\Modules\Category\Models\Category;
use App\Modules\Order\Models\Order;
use App\Modules\Pincode\Models\Pincode;
use App\Modules\ProductImage\Models\ProductImage;
use App\Modules\ProductReview\Models\ProductReview;
use App\Modules\Wishlist\Models\Wishlist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount',
        'inventory',
        'featured_image',
        'image_title',
        'image_alt',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'user_id',
        'is_active',
        'is_new_arrival',
        'is_featured',
        'is_best_sale',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'float',
        'discount' => 'float',
        'inventory' => 'int',
        'is_active' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_featured' => 'boolean',
        'is_best_sale' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
        'is_new_arrival' => true,
        'is_featured' => true,
        'is_best_sale' => true,
        'price' => 0,
        'discount' => 0,
        'inventory' => 0,
    ];

    public $image_path = 'product';

    protected function featuredImage(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => 'storage/'.$this->image_path.'/'.$value,
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => str()->slug($value),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function other_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function wishlist()
    {
        return $this->belongsToMany(Wishlist::class, 'wishlist_products', 'product_id', 'wishlist_id');
    }

    public function cart()
    {
        return $this->belongsToMany(Cart::class, 'cart_products', 'product_id', 'cart_id');
    }

    public function order()
    {
        return $this->belongsToMany(Order::class, 'order_products', 'product_id', 'order_id');
    }

    public function pincodes()
    {
        return $this->belongsToMany(Pincode::class, 'product_pincodes', 'product_id', 'pincode_id');
    }
}
