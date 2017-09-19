<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PageTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_home' => $this->is_home,
            'template' => $this->template,
            'created_at' => $this->created_at->format('d-m-Y'),
            'translations' => [
                'title' => optional($this->translate(locale()))->title,
                'slug' => optional($this->translate(locale()))->slug,
                'status' => optional($this->translate(locale()))->status,
            ],
            'urls' => [
                'delete_url' => route('api.page.page.destroy', $this->id),
            ],
        ];
    }
}
