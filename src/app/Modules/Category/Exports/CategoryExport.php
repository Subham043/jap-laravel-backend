<?php

namespace App\Modules\Category\Exports;

use App\Modules\Category\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoryExport implements FromCollection,WithHeadings,WithMapping
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
            'meta_title',
            'meta_keywords',
            'meta_description',
            'is_active',
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
             $data->meta_title,
             $data->meta_keywords,
             $data->meta_description,
             $data->is_active,
             $data->created_at,
         ];
    }
    public function collection()
    {
        return Category::all();
    }
}
