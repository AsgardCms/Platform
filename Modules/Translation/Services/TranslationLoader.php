<?php

namespace Modules\Translation\Services;

class TranslationLoader extends \Illuminate\Translation\FileLoader
{
    /**
     * Get all Paths where Translations could be found.
     * @return array
     */
    public function paths()
    {
        return array_merge(
            [$this->path],
            $this->hints
        );
    }
}
