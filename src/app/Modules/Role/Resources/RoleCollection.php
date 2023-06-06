<?php

namespace App\Modules\Role\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'permissions' => PermissionCollection::collection($this->permissions),
        ];
    }
}
