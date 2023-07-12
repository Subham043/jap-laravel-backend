<?php

namespace App\Modules\Product\Services;

use App\Http\Services\FileService;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class ProductService
{

    public function all(): Collection
    {
        return Product::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Product::with(['categories', 'other_images', 'reviews'])->latest();
        return QueryBuilder::for($query)
                ->allowedIncludes(['categories', 'reviews'])
                ->defaultSort('id')
                ->allowedSorts('id', 'name', 'price', 'discount')
                ->allowedFilters([
                    'is_new_arrival',
                    'is_featured',
                    'is_best_sale',
                    AllowedFilter::callback('has_categories', function (Builder $query, $value) {
                        $query->whereHas('categories', function($q) use($value) {
                            $q->where('name', 'LIKE', '%' . $value . '%');
                        });
                    }),
                    AllowedFilter::callback('has_reviews', function (Builder $query, $value) {
                        $query->whereHas('reviews', function($q) use($value) {
                            $q->where('star', $value);
                        });
                    }),
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function main_paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Product::with([
                'categories' => function($q){
                    $q->where('is_active', true);
                },
                'other_images',
                'reviews' => function($q){
                    $q->where('is_approved', true);
                }
            ])->where('is_active', true)->latest();
        return QueryBuilder::for($query)
                ->allowedIncludes(['categories', 'reviews'])
                ->defaultSort('id')
                ->allowedSorts('id', 'name', 'price', 'discount')
                ->allowedFilters([
                    'is_new_arrival',
                    'is_featured',
                    'is_best_sale',
                    AllowedFilter::callback('has_categories', function (Builder $query, $value) {
                        $query->whereHas('categories', function($q) use($value) {
                            $q->where('is_active', true)->where(function($qr) use($value){
                                $qr->where('slug', $value);
                            });
                        });
                    }),
                    AllowedFilter::callback('has_reviews', function (Builder $query, $value) {
                        $query->whereHas('reviews', function($q) use($value) {
                            $q->where('is_approved', true)->where('star', $value);
                        });
                    }),
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): Product|null
    {
        return Product::with(['categories', 'other_images', 'reviews'])->findOrFail($id);
    }

    public function getBySlug(String $slug): Product
    {
        return Product::with([
            'categories' => function($q){
                $q->where('is_active', true);
            },
            'other_images',
            'reviews' => function($q){
                $q->where('is_approved', true);
            }
        ])->where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function create(array $data): Product
    {
        $product = Product::create($data);
        return $product;
    }

    public function update(array $data, Product $product): Product
    {
        $product->update($data);
        return $product;
    }

    public function saveFile(Product $product, string $file_key): Product
    {
        $this->deleteFile($product, $file_key);
        $file = (new FileService)->save_file($file_key, (new Product)->image_path);
        return $this->update([
            $file_key => $file,
        ], $product);
    }

    public function delete(Product $product): bool|null
    {
        $this->deleteFile($product, 'featured_image');
        return $product->delete();
    }

    public function deleteFile(Product $product, string $file_key): void
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
        $query->where('name', 'LIKE', '%' . $value . '%')
        ->orWhere('description', 'LIKE', '%' . $value . '%')
        ->orWhere('price', 'LIKE', '%' . $value . '%')
        ->orWhere('discount', 'LIKE', '%' . $value . '%')
        ->orWhere('is_active', 'LIKE', '%' . $value . '%')
        ->orWhere('is_new_arrival', 'LIKE', '%' . $value . '%')
        ->orWhere('is_featured', 'LIKE', '%' . $value . '%')
        ->orWhere('is_best_sale', 'LIKE', '%' . $value . '%')
        ->orWhere('slug', 'LIKE', '%' . $value . '%');
    }
}
