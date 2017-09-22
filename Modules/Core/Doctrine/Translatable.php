<?php

namespace Modules\Core\Doctrine;

use Illuminate\Support\Facades\App;
use Mitch\LaravelDoctrine\EntityManagerFacade;
use ReflectionClass;

trait Translatable
{
    /**
     * @param array $input
     */
    public function fillTranslations(array $input)
    {
        foreach ($input as $locale => $attributes) {
            foreach ($attributes as $key => $value) {
                $this->createOrUpdateTranslation($key, $locale, $value);
            }
        }
    }

    /**
     * Create or update the given field name
     * @param string $fieldName
     * @param string $locale
     * @param string $value
     */
    public function createOrUpdateTranslation($fieldName, $locale, $value)
    {
        $found = false;
        foreach ($this->translation as $translation) {
            if ($translation->locale == $locale) {
                $translation->$fieldName = $value;
                $found = true;
                continue;
            }
        }

        if (! $found) {
            $foreignKey = $this->getForeignKey();

            $translationObjectClass = $this->getTranslationClass();
            $translationObject = new $translationObjectClass();
            $translationObject->locale = $locale;
            $translationObject->$fieldName = $value;
            $translationObject->$foreignKey = $this;
            $this->translation->add($translationObject);
        }
    }

    /**
     * Get the translation of the given field name
     * @param  string      $fieldName
     * @param  string|null $locale
     * @return string
     */
    public function translation($fieldName, $locale = null)
    {
        $locale = $locale ?: App::getLocale();

        foreach ($this->translation as $translation) {
            if ($translation->locale == $locale) {
                return $translation->$fieldName;
            }
        }
    }

    /**
     * @param  string $fieldName
     * @return mixed
     */
    public function translatableGetter($fieldName)
    {
        if (in_array($fieldName, $this->getTranslatedFieldNamesForEntity())) {
            $result = $this->translation($fieldName);
        } else {
            $result = $this->getRawField($fieldName);
        }

        return $result;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getRawField($name)
    {
        return $this->$name;
    }

    /**
     * @return array
     */
    private function getTranslatedFieldNamesForEntity()
    {
        $cacheArray = [];
        $translatedEntityName = $this->getTranslationClass();
        if (! isset($cacheArray[$translatedEntityName])) {
            $cacheArray[$translatedEntityName] = array_values(array_diff(
                EntityManagerFacade::getClassMetadata($translatedEntityName)->getColumnNames(),
                ['id', 'locale']
            ));
        }

        return $cacheArray[$translatedEntityName];
    }

    /**
     * Get the foreign key for the current class
     * @return string
     */
    private function getForeignKey()
    {
        $reflectionClass = new ReflectionClass(get_class($this));
        $foreignKey = strtolower($reflectionClass->getShortName());

        return $foreignKey;
    }

    /**
     * Get the Translations class name
     * @return string
     */
    private function getTranslationClass()
    {
        return get_class($this) . 'Translation';
    }
}
