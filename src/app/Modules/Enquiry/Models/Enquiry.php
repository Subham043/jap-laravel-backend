<?php

namespace App\Modules\Enquiry\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $table = 'enquiries';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'company_website',
        'designation',
        'product',
        'quantity',
        'gst',
        'certification',
        'address',
        'alternate_name',
        'alternate_phone',
        'alternate_email',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
