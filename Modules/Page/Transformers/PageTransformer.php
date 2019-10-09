<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PageTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'is_home' => $this->resource->is_home,
            'template' => $this->resource->template,
            'created_at' => $this->resource->created_at->format('d-m-Y'),
            'translations' => [
                'title' => optional($this->resource->translate(locale()))->title,
                'slug' => optional($this->resource->translate(locale()))->slug,
                'status' => optional($this->resource->translate(locale()))->status,
            ],
            'urls' => [
                'delete_url' => route('api.page.page.destroy', $this->resource->id),
            ],
        ];
    }
}
