<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ApiKeysTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'access_token' => $this->access_token,
            'created_at' => $this->created_at,
        ];
    }
}
