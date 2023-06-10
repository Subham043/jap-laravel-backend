<?php

namespace App\Modules\ProductImage\Services;

use App\Http\Services\FileService;
use App\Modules\ProductImage\Models\ProductImage;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class ProductImageService
{

    public function all(string $product_id): Collection
    {
        return ProductImage::with([
            'product' => function($q) use($product_id) {
                $q->where('id', $product_id);
            },
        ])->where('product_id', $product_id)->get();
    }

    public function paginate(Int $total = 10, string $product_id): LengthAwarePaginator
    {
        $query = ProductImage::with([
            'product' => function($q) use($product_id) {
                $q->where('id', $product_id);
            },
        ])->where('product_id', $product_id)->latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(string $product_id, Int $id): ProductImage|null
    {
        return ProductImage::with([
            'product' => function($q) use($product_id) {
                $q->where('id', $product_id);
            },
        ])->where('product_id', $product_id)->findOrFail($id);
    }

    public function create(array $data): ProductImage
    {
        $product = ProductImage::create($data);
        return $product;
    }

    public function update(array $data, ProductImage $product): ProductImage
    {
        $product->update($data);
        return $product;
    }

    public function saveFile(ProductImage $product, string $file_key): ProductImage
    {
        $this->deleteFile($product, $file_key);
        $file = (new FileService)->save_file($file_key, (new ProductImage)->image_path);
        return $this->update([
            $file_key => $file,
        ], $product);
    }

    public function delete(ProductImage $product): bool|null
    {
        $this->deleteFile($product, 'image');
        return $product->delete();
    }

    public function deleteFile(ProductImage $product, string $file_key): void
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
        $query->where('image_title', 'LIKE', '%' . $value . '%')
        ->orWhere('image_alt', 'LIKE', '%' . $value . '%');
    }
}
