<?php

namespace App\Modules\Product\Imports;

use App\Modules\Product\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class ProductImport implements ToModel
{
   /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new Product([
            'name' => $row[0],
            'slug' => Str::slug($row[1]),
            'description' => $row[2],
            'price' => $row[3],
            'discount' => $row[4],
            'inventory' => $row[5],
            'meta_title' => $row[6],
            'meta_keywords' => $row[7],
            'meta_description' => $row[8],
            'is_active' => $row[9],
            'is_new_arrival' => $row[10],
            'is_featured' => $row[11],
            'is_best_sale' => $row[12],
        ]);
    }
}
