<?php

namespace Modules\Translation\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class LocaleSelectTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'label' => $this['name'],
            'code'  => $this['code'],
        ];
    }
}
