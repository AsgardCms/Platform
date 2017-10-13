<?php

namespace Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FullPageTransformer extends Resource
{
    public function toArray($request)
    {
        $pageData = [
            'id' => $this->id,
            'template' => $this->template,
            'is_home' => $this->is_home,
            'urls' => [
                'public_url' => $this->getCanonicalUrl(),
            ],
        ];

        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $pageData[$locale] = [];
            foreach ($this->translatedAttributes as $translatedAttribute) {
                $pageData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
            }
        }

        foreach ($this->tags as $tag) {
            $pageData['tags'][] = $tag->name;
        }

        return $pageData;
    }
}
