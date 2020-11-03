<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleTransformer extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'created_at' => $this->resource->created_at,
            'urls' => [
                'delete_url' => route('api.user.role.destroy', $this->resource->id),
            ],
        ];
    }
}
