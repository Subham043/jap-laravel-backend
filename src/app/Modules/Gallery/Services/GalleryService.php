<?php

namespace App\Modules\Gallery\Services;

use App\Http\Services\FileService;
use App\Modules\Gallery\Models\Gallery;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class GalleryService
{

    public function all(): Collection
    {
        return Gallery::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Gallery::with(['category']);
        return QueryBuilder::for($query)
                ->allowedIncludes(['category'])
                ->defaultSort('id')
                ->allowedSorts('id', 'title')
                ->allowedFilters([
                    AllowedFilter::callback('has_categories', function (Builder $query, $value) {
                        $query->where('gallery_categories_id', $value)
                        ->whereHas('category', function($q) use($value) {
                            $q->where('id', $value);
                        });
                    }),
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function main_paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Gallery::with(['category']);
        return QueryBuilder::for($query)
                ->allowedIncludes(['category'])
                ->defaultSort('id')
                ->allowedSorts('id', 'title')
                ->allowedFilters([
                    AllowedFilter::callback('has_categories', function (Builder $query, $value) {
                        $query->where('gallery_categories_id', $value)
                        ->whereHas('category', function($q) use($value) {
                            $q->where('id', $value);
                        });
                    }),
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): Gallery|null
    {
        return Gallery::with(['category'])->findOrFail($id);
    }

    public function create(array $data): Gallery
    {
        $product = Gallery::create($data);
        return $product;
    }

    public function update(array $data, Gallery $product): Gallery
    {
        $product->update($data);
        return $product;
    }

    public function saveFile(Gallery $product, string $file_key): Gallery
    {
        // $this->deleteFile($product, $file_key);
        $file = (new FileService)->save_file($file_key, (new Gallery)->image_path);
        return $this->update([
            $file_key => $file,
        ], $product);
    }

    public function delete(Gallery $product): bool|null
    {
        // $this->deleteFile($product, 'featured_image');
        return $product->delete();
    }

    public function deleteFile(Gallery $product, string $file_key): void
    {
        if($product[$file_key]){
            $path = str_replace("storage","app/public",$product[$file_key]);
            (new FileService)->delete_file($path);
        }
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function($q) use($value){
            $q->where('title', 'LIKE', '%' . $value . '%')
            ->orWhere('description', 'LIKE', '%' . $value . '%');
        });
    }
}
