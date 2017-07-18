<?php

namespace Modules\Translation\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Translation\Entities\TranslationTranslation;
use Modules\Translation\Repositories\TranslationRepository;

class EloquentTranslationRepository extends EloquentBaseRepository implements TranslationRepository
{
    /**
     * @param string $key
     * @param string $locale
     * @return string
     */
    public function findByKeyAndLocale($key, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->model->where('key', $key)->with('translations')->first();
        if ($translation && $translation->hasTranslation($locale)) {
            return $translation->translate($locale)->value;
        }

        return '';
    }

    public function getTranslationsForGroupAndNamespace($locale, $group, $namespace)
    {
        $start = $namespace . '::' . $group;

        $test = $this->model->where('key', 'LIKE', "{$start}%")->whereHas('translations', function (Builder $query) use ($locale) {
            $query->where('locale', $locale);
        })->get();

        $translations = [];
        foreach ($test as $item) {
            $key = str_replace($start . '.', '', $item->key);
            $translations[$key] = $item->translate($locale)->value;
        }

        return $translations;
    }

    public function allFormatted()
    {
        $allRows = $this->all();
        $allDatabaseTranslations = [];
        foreach ($allRows as $translation) {
            foreach (config('laravellocalization.supportedLocales') as $locale => $language) {
                if ($translation->hasTranslation($locale)) {
                    $allDatabaseTranslations[$locale][$translation->key] = $translation->translate($locale)->value;
                }
            }
        }

        return $allDatabaseTranslations;
    }

    public function saveTranslationForLocaleAndKey($locale, $key, $value)
    {
        $translation = $this->findTranslationByKey($key);
        $translation->translateOrNew($locale)->value = $value;
        $translation->save();
    }

    public function findTranslationByKey($key)
    {
        return $this->model->firstOrCreate(['key' => $key]);
    }

    /**
     * Update the given translation key with the given data
     * @param string $key
     * @param array $data
     * @return mixed
     */
    public function updateFromImport($key, array $data)
    {
        $translation = $this->findTranslationByKey($key);
        $translation->update($data);
    }

    /**
     * Set the given value on the given TranslationTranslation
     * @param TranslationTranslation $translationTranslation
     * @param string $value
     * @return void
     */
    public function updateTranslationToValue(TranslationTranslation $translationTranslation, $value)
    {
        $translationTranslation->value = $value;
        $translationTranslation->save();
    }
}
