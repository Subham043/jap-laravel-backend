<?php

namespace App\Modules\Tax\Services;

use App\Modules\Tax\Models\Tax;

class TaxService
{

    public function get(): Tax|null
    {
        $data = Tax::where('tax_slug', 'tax_gst')->first();
        return $data;
    }

    public function save(array $data): Tax
    {
        $tax = Tax::updateOrCreate(
            ['tax_slug'=> 'tax_gst'],
            [...$data]);
        return $tax;
    }

}
