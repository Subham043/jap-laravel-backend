<?php

namespace App\Modules\Tax\Models;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';

    protected $fillable = [
        'tax_slug',
        'tax_in_percentage',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tax_in_percentage' => 'float',
    ];

    protected $attributes = [
        'tax_in_percentage' => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

}
