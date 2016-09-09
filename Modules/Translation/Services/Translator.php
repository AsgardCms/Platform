<?php

namespace Modules\Translation\Services;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Translation\Repositories\TranslationRepository;

class Translator extends \Illuminate\Translation\Translator
{
    use DispatchesJobs;
    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @param  bool  $fallback
     * @return string
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $translationRepository = app(TranslationRepository::class);
        if ($translation = $translationRepository->findByKeyAndLocale($key, $locale)) {
            return $this->makeReplacements($translation, $replace);
        }

        return parent::get($key, $replace, $locale);
    }
}
