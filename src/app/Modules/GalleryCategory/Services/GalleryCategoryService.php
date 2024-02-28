<?php

namespace App\Modules\GalleryCategory\Services;

use App\Modules\GalleryCategory\Models\GalleryCategory;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class GalleryCategoryService
{

    public function all(): Collection
    {
        return GalleryCategory::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = GalleryCategory::latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function main_paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = GalleryCategory::latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): GalleryCategory|null
    {
        return GalleryCategory::findOrFail($id);
    }

    public function create(array $data): GalleryCategory
    {
        $category = GalleryCategory::create($data);
        return $category;
    }

    public function update(array $data, GalleryCategory $category): GalleryCategory
    {
        $category->update($data);
        return $category;
    }

    public function delete(GalleryCategory $category): bool|null
    {
        return $category->delete();
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function($q) use($value){
            $q->where('name', 'LIKE', '%' . $value . '%');
        });
    }
}
