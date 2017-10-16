<?php

namespace Modules\Core\Events;

/**
 * Hook LoadingBackendTranslations
 * Triggered when loading the backend
 * Used to send laravel translations to the frontend
 * Example for VueJS
 * @package Modules\Core\Events
 */
class LoadingBackendTranslations
{
    private $translations = [];

    public function getTranslations() : array
    {
        return $this->translations;
    }

    public function load($key, array $translations)
    {
        $this->translations = array_merge($this->translations, [$key => $translations]);

        return $this;
    }

    public function loadMultiple(array $translations)
    {
        $this->translations = array_merge($this->translations, $translations);

        return $this;
    }
}
