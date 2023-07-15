<?php

namespace App\Modules\Enquiry\Services;

use App\Http\Services\FileService;
use App\Modules\Enquiry\Models\Enquiry;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class EnquiryService
{

    public function all(): Collection
    {
        return Enquiry::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Enquiry::latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): Enquiry|null
    {
        return Enquiry::findOrFail($id);
    }

    public function create(array $data): Enquiry
    {
        $category = Enquiry::create($data);
        return $category;
    }

    public function update(array $data, Enquiry $category): Enquiry
    {
        $category->update($data);
        return $category;
    }

    public function delete(Enquiry $category): bool|null
    {
        return $category->delete();
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('name', 'LIKE', '%' . $value . '%')
        ->orWhere('email', 'LIKE', '%' . $value . '%')
        ->orWhere('phone', 'LIKE', '%' . $value . '%')
        ->orWhere('company_name', 'LIKE', '%' . $value . '%')
        ->orWhere('company_website', 'LIKE', '%' . $value . '%')
        ->orWhere('designation', 'LIKE', '%' . $value . '%')
        ->orWhere('product', 'LIKE', '%' . $value . '%')
        ->orWhere('quantity', 'LIKE', '%' . $value . '%')
        ->orWhere('gst', 'LIKE', '%' . $value . '%')
        ->orWhere('certification', 'LIKE', '%' . $value . '%')
        ->orWhere('address', 'LIKE', '%' . $value . '%')
        ->orWhere('alternate_name', 'LIKE', '%' . $value . '%')
        ->orWhere('alternate_phone', 'LIKE', '%' . $value . '%')
        ->orWhere('alternate_email', 'LIKE', '%' . $value . '%')
        ->orWhere('message', 'LIKE', '%' . $value . '%');
    }
}
