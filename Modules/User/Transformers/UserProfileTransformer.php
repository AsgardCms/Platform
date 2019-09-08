<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class UserProfileTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'email' => $this->resource->email,
            'created_at' => $this->resource->created_at,
        ];
    }
}
