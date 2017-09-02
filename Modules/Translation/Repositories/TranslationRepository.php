<?php

namespace Modules\Translation\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Translation\Entities\TranslationTranslation;

interface TranslationRepository extends BaseRepository
{
    /**
     * @param string $key
     * @param string $locale
     * @return string
     */
    public function findByKeyAndLocale($key, $locale = null);

    /**
     * @return mixed
     */
    public function allFormatted();

    /**
     * @param $locale
     * @param $key
     * @param $value
     * @return mixed
     */
    public function saveTranslationForLocaleAndKey($locale, $key, $value);

    /**
     * @param $key
     * @return mixed
     */
    public function findTranslationByKey($key);

    /**
     * Update the given translation key with the given data
     * @param string $key
     * @param array $data
     * @return mixed
     */
    public function updateFromImport($key, array $data);

    /**
     * Set the given value on the given TranslationTranslation
     * @param TranslationTranslation $translationTranslation
     * @param string $value
     * @return void
     */
    public function updateTranslationToValue(TranslationTranslation $translationTranslation, $value);

    public function getTranslationsForGroupAndNamespace($locale, $group, $namespace);
}
