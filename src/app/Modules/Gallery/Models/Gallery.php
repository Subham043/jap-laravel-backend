<?php

namespace App\Modules\Gallery\Models;

use App\Modules\Authentication\Models\User;
use App\Modules\GalleryCategory\Models\GalleryCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'description',
        'image',
        'image_title',
        'image_alt',
        'gallery_categories_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $image_path = 'gallery';

    protected function Image(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => 'storage/'.$this->image_path.'/'.$value,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_categories_id')->withDefault();
    }

}
