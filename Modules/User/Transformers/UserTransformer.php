<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class UserTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'fullname' => $this->present()->fullname,
            'email' => $this->email,
            'created_at' => $this->created_at,

            'urls' => [
                'delete_url' => route('api.user.user.destroy', $this->id),
            ],
        ];
    }
}
