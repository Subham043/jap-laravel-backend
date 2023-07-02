<?php

namespace App\Modules\Product\Exports;

use App\Modules\Product\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'name',
            'slug',
            'description',
            'price',
            'discount',
            'inventory',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'is_active',
            'is_new_arrival',
            'is_featured',
            'is_best_sale',
            'Created_at',
        ];
    }
    public function map($data): array
    {
         return[
             $data->id,
             $data->name,
             $data->slug,
             $data->description,
             $data->price,
             $data->discount,
             $data->inventory,
             $data->meta_title,
             $data->meta_keywords,
             $data->meta_description,
             $data->is_active,
             $data->is_new_arrival,
             $data->is_featured,
             $data->is_featured,
             $data->is_best_sale,
             $data->created_at,
         ];
    }
    public function collection()
    {
        return Product::all();
    }
}
