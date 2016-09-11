<?php

namespace Modules\Translation\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Translation\Entities\TranslationTranslation;
use Modules\Translation\Repositories\TranslationRepository;

class CacheTranslationDecorator extends BaseCacheDecorator implements TranslationRepository
{
    public function __construct(TranslationRepository $recipe)
    {
        parent::__construct();
        $this->entityName = 'translation.translations';
        $this->repository = $recipe;
    }

    /**
     * @param string $key
     * @param string $locale
     * @return string
     */
    public function findByKeyAndLocale($key, $locale = null)
    {
        $cleanKey = $this->cleanKey($key);

        $locale = $locale ?: app()->getLocale();

        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->rememberForever("{$this->entityName}.findByKeyAndLocale.{$cleanKey}.{$locale}",
                function () use ($key, $locale) {
                    return $this->repository->findByKeyAndLocale($key, $locale);
                }
            );
    }

    public function allFormatted()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->rememberForever("{$this->locale}.{$this->entityName}.allFormatted",
                function () {
                    return $this->repository->allFormatted();
                }
            );
    }

    public function saveTranslationForLocaleAndKey($locale, $key, $value)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->saveTranslationForLocaleAndKey($locale, $key, $value);
    }

    public function findTranslationByKey($key)
    {
        $cleanKey = $this->cleanKey($key);

        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->rememberForever("{$this->locale}.{$this->entityName}.findTranslationByKey.{$cleanKey}",
                function () use ($key) {
                    return $this->repository->findTranslationByKey($key);
                }
            );
    }

    /**
     * Update the given translation key with the given data
     * @param string $key
     * @param array $data
     * @return mixed
     */
    public function updateFromImport($key, array $data)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->updateFromImport($key, $data);
    }

    /**
     * Set the given value on the given TranslationTranslation
     * @param TranslationTranslation $translationTranslation
     * @param string $value
     * @return void
     */
    public function updateTranslationToValue(TranslationTranslation $translationTranslation, $value)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->updateTranslationToValue($translationTranslation, $value);
    }

    /**
     * Clean a Cache Key so it is safe for use
     * @param string $key   Potentially unsafe key
     * @return string
     */
    protected function cleanKey($key)
    {
        return str_replace(" ", "--", $key);
    }
}
