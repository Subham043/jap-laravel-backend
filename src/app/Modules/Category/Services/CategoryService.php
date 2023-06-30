<?php

namespace App\Modules\Category\Services;

use App\Http\Services\FileService;
use App\Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class CategoryService
{

    public function all(): Collection
    {
        return Category::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Category::latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function main_paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Category::where('is_active', true)->latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): Category|null
    {
        return Category::findOrFail($id);
    }

    public function getBySlug(String $slug): Category
    {
        return Category::where('is_active', true)->where('slug', $slug)->firstOrFail();
    }

    public function create(array $data): Category
    {
        $category = Category::create($data);
        return $category;
    }

    public function update(array $data, Category $category): Category
    {
        $category->update($data);
        return $category;
    }

    public function saveFile(Category $category, string $file_key): Category
    {
        $this->deleteFile($category, $file_key);
        $file = (new FileService)->save_file($file_key, (new Category)->image_path);
        return $this->update([
            $file_key => $file,
        ], $category);
    }

    public function delete(Category $category): bool|null
    {
        $this->deleteFile($category, 'banner_image');
        $this->deleteFile($category, 'icon_image');
        return $category->delete();
    }

    public function deleteFile(Category $category, string $file_key): void
    {
        if($category[$file_key]){
            $path = str_replace("storage","app/public",$category[$file_key]);
            (new FileService)->delete_file($path);
        }
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('name', 'LIKE', '%' . $value . '%')
        ->orWhere('description', 'LIKE', '%' . $value . '%')
        ->orWhere('is_active', 'LIKE', '%' . $value . '%')
        ->orWhere('slug', 'LIKE', '%' . $value . '%');
    }
}
