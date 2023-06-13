<?php

namespace App\Modules\Coupon\Services;

use App\Modules\Coupon\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class CouponService
{

    public function all(): Collection
    {
        return Coupon::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Coupon::latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): Coupon|null
    {
        return Coupon::findOrFail($id);
    }

    public function getByCode(String $code): Coupon
    {
        return Coupon::where('code', $code)->firstOrFail();
    }

    public function create(array $data): Coupon
    {
        $coupon = Coupon::create($data);
        return $coupon;
    }

    public function update(array $data, Coupon $coupon): Coupon
    {
        $coupon->update($data);
        return $coupon;
    }

    public function delete(Coupon $coupon): bool|null
    {
        return $coupon->delete();
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('name', 'LIKE', '%' . $value . '%')
        ->orWhere('description', 'LIKE', '%' . $value . '%')
        ->orWhere('is_active', 'LIKE', '%' . $value . '%')
        ->orWhere('code', 'LIKE', '%' . $value . '%');
    }
}
