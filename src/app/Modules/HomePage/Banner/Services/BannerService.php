<?php

namespace App\Modules\HomePage\Banner\Services;

use App\Http\Services\FileService;
use App\Modules\HomePage\Banner\Models\Banner;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class BannerService
{

    public function all(): Collection
    {
        return Banner::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Banner::latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): Banner|null
    {
        return Banner::findOrFail($id);
    }

    public function create(array $data): Banner
    {
        $banner = Banner::create($data);
        $banner->user_id = auth()->user()->id;
        $banner->save();
        return $banner;
    }

    public function update(array $data, Banner $banner): Banner
    {
        $banner->update($data);
        return $banner;
    }

    public function saveImage(Banner $banner): Banner
    {
        $this->deleteImage($banner);
        $banner_image = (new FileService)->save_file('banner_image', (new Banner)->image_path);
        return $this->update([
            'banner_image' => $banner_image,
        ], $banner);
    }

    public function delete(Banner $banner): bool|null
    {
        $this->deleteImage($banner);
        return $banner->delete();
    }

    public function deleteImage(Banner $banner): void
    {
        if($banner->banner_image){
            $path = str_replace("storage","app/public",$banner->banner_image);
            (new FileService)->delete_file($path);
        }
    }

    public function main_all(): Collection
    {
        return Banner::where('is_active', true)->latest()->get();
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function($q) use($value){
            $q->where('title', 'LIKE', '%' . $value . '%')
            ->orWhere('sub_title', 'LIKE', '%' . $value . '%')
            ->orWhere('description', 'LIKE', '%' . $value . '%');
        });
    }
}
