<?php

namespace Modules\Core\Internationalisation;

trait Translatable
{
    use \Astrotomic\Translatable\Translatable;

    public function save(array $options = [])
    {
        $tempTranslations = $this->translations;
        if ($this->exists) {
            if (count($this->getDirty()) > 0) {
                // If $this->exists and dirty, parent::save() has to return true. If not,
                // an error has occurred. Therefore we shouldn't save the translations.
                if (parent::save($options)) {
                    $this->translations = $tempTranslations;

                    return $this->saveTranslations();
                }

                return false;
            } else {
                // If $this->exists and not dirty, parent::save() skips saving and returns
                // false. So we have to save the translations
                $this->translations = $tempTranslations;

                return $this->saveTranslations();
            }
        } elseif (parent::save($options)) {
            // We save the translations only if the instance is saved in the database.
            $this->translations = $tempTranslations;

            return $this->saveTranslations();
        }

        return false;
    }
}
