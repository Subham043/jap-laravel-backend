<?php

namespace App\Modules\GalleryCategory\Models;

use App\Modules\Authentication\Models\User;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    protected $table = 'gallery_categories';

    protected $fillable = [
        'name',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function gallery()
    {
        return $this->hasMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
}
