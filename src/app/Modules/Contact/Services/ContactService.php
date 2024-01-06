<?php

namespace App\Modules\Contact\Services;

use App\Modules\Contact\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class ContactService
{

    public function all(): Collection
    {
        return Contact::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Contact::latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getById(Int $id): Contact|null
    {
        return Contact::findOrFail($id);
    }

    public function create(array $data): Contact
    {
        $contact = Contact::create($data);
        return $contact;
    }

    public function update(array $data, Contact $contact): Contact
    {
        $contact->update($data);
        return $contact;
    }

    public function delete(Contact $contact): bool|null
    {
        return $contact->delete();
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function($q) use($value){
          $q->where('name', 'LIKE', '%' . $value . '%')
          ->orWhere('email', 'LIKE', '%' . $value . '%')
          ->orWhere('phone', 'LIKE', '%' . $value . '%')
          ->orWhere('message', 'LIKE', '%' . $value . '%');
        });
    }
}
