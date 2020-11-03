<?php

namespace Modules\Translation\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LocaleSelectTransformer extends JsonResource
{
    public function toArray($request)
    {
        return [
            'label' => $this['name'],
            'code'  => $this['code'],
        ];
    }
}
