<?php

namespace Modules\Tag\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TagTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'slug' => $this->resource->slug,
            'name' => $this->resource->name,
        ];
    }
}
