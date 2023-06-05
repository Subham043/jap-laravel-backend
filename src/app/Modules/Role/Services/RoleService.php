<?php

namespace App\Modules\Role\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;

class RoleService
{

    public function all(): Collection
    {
        return Role::all();
    }

    public function allPermissions(): Collection
    {
        return Permission::all();
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Role::with(['permissions'])->whereNot('name', 'Super-Admin')->latest();
        return QueryBuilder::for($query)
                ->allowedFilters([
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total);
    }

    public function getById(Int $id): Role|null
    {
        return Role::with(['permissions'])->whereNot('name', 'Super-Admin')->findOrFail($id);
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }

    public function update(array $data, Role $role): Role
    {
        $role->update($data);
        return $role;
    }

    public function syncPermissions(?array $permissions = [], Role $role): void
    {
        $role->syncPermissions($permissions);
    }

    public function delete(Role $role): bool|null
    {
        return $role->delete();
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('name', 'LIKE', '%' . $value . '%');
    }
}
