<?php

namespace App\Modules\ProductReview\Services;

use App\Http\Services\FileService;
use App\Modules\ProductReview\Models\ProductReview;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class ProductReviewService
{

    public function all(string $product_id): Collection
    {
        return ProductReview::with([
            'product' => function($q) use($product_id) {
                $q->where('id', $product_id);
            },
        ])->where('product_id', $product_id)->get();
    }

    public function paginate(Int $total = 10, string $product_id): LengthAwarePaginator
    {
        $query = ProductReview::with([
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

    public function getById(string $product_id, Int $id): ProductReview|null
    {
        return ProductReview::with([
            'product' => function($q) use($product_id) {
                $q->where('id', $product_id);
            },
        ])->where('product_id', $product_id)->findOrFail($id);
    }

    public function create(array $data): ProductReview
    {
        $productReview = ProductReview::create($data);
        return $productReview;
    }

    public function update(array $data, ProductReview $productReview): ProductReview
    {
        $productReview->update($data);
        return $productReview;
    }

    public function saveFile(ProductReview $productReview, string $file_key): ProductReview
    {
        $this->deleteFile($productReview, $file_key);
        $file = (new FileService)->save_file($file_key, (new ProductReview)->image_path);
        return $this->update([
            $file_key => $file,
        ], $productReview);
    }

    public function delete(ProductReview $productReview): bool|null
    {
        $this->deleteFile($productReview, 'image');
        return $productReview->delete();
    }

    public function deleteFile(ProductReview $productReview, string $file_key): void
    {
        if($productReview[$file_key]){
            $path = str_replace("storage","app/public",$productReview[$file_key]);
            (new FileService)->delete_file($path);
        }
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('name', 'LIKE', '%' . $value . '%')
        ->orWhere('star', 'LIKE', '%' . $value . '%')
        ->orWhere('message', 'LIKE', '%' . $value . '%')
        ->orWhere('email', 'LIKE', '%' . $value . '%');
    }
}
