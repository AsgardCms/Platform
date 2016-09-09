<?php

namespace Modules\Translation\Repositories;

interface FileTranslationRepository
{
    /**
     * Get all the translations for all modules on disk
     * @return array
     */
    public function all();
}
