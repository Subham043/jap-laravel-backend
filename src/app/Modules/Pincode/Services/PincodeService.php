<?php

namespace App\Modules\Pincode\Services;

use App\Modules\Pincode\Models\Pincode;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class PincodeService
{

    public function all(): Collection
    {
        return Pincode::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Pincode::latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): Pincode|null
    {
        return Pincode::findOrFail($id);
    }

    public function getByCode(String $code): Pincode
    {
        return Pincode::where('code', $code)->firstOrFail();
    }

    public function create(array $data): Pincode
    {
        $pincode = Pincode::create($data);
        return $pincode;
    }

    public function update(array $data, Pincode $pincode): Pincode
    {
        $pincode->update($data);
        return $pincode;
    }

    public function delete(Pincode $pincode): bool|null
    {
        return $pincode->delete();
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('place', 'LIKE', '%' . $value . '%')
        ->orWhere('pincode', 'LIKE', '%' . $value . '%');
    }
}
