<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTransformer extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'fullname' => $this->resource->present()->fullname,
            'email' => $this->resource->email,
            'created_at' => $this->resource->created_at,
            'urls' => [
                'delete_url' => route('api.user.user.destroy', $this->resource->id),
            ],
        ];
    }
}
