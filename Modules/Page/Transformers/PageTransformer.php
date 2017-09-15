<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PageTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'created_at' => $this->created_at->format('d-m-Y'),
            'translations' => [
                'title' => $this->title,
                'slug' => $this->slug,
            ],
            'urls' => [
                'delete_url' => route('api.page.page.destroy', $this->id),
                'edit_url' => route('admin.page.page.edit', $this->id),
            ],
        ];
    }
}
