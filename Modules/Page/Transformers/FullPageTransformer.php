<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FullPageTransformer extends Resource
{
    public function toArray($request)
    {
        $pageData = [
            'id' => $this->resource->id,
            'template' => $this->resource->template,
            'is_home' => $this->resource->is_home,
            'urls' => [
                'public_url' => $this->resource->getCanonicalUrl(),
            ],
        ];

        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $pageData[$locale] = [];
            foreach ($this->resource->translatedAttributes as $translatedAttribute) {
                $pageData[$locale][$translatedAttribute] = $this->resource->translateOrNew($locale)->$translatedAttribute;
            }
        }

        foreach ($this->resource->tags as $tag) {
            $pageData['tags'][] = $tag->name;
        }

        return $pageData;
    }
}
