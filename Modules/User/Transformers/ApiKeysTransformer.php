<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiKeysTransformer extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'access_token' => $this->resource->access_token,
            'created_at' => $this->resource->created_at,
        ];
    }
}
