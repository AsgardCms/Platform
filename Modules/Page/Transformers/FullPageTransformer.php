<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class FullPageTransformer extends Resource
{
    public function toArray($request)
    {
        $pageData = [
            'id' => $this->id,
            'template' => $this->template,
            'is_home' => $this->template,
        ];

        foreach ($this->translations as $pageTranslation) {
            $pageData[$pageTranslation->locale] = $pageTranslation;
        }
        foreach ($this->tags as $tag) {
            $pageData['tags'][] = $tag->name;
        }

        return $pageData;
    }
}
