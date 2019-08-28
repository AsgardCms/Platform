<?php

namespace Modules\Translation\Repositories;

use Illuminate\Support\Collection;
use Modules\Translation\Http\Requests\LocaleCodeRequest;

interface LocaleRepository
{
    /**
     * Return locales filtered,sorted and paginated according to request data
     *
     * @param LocaleCodeRequest $request
     * @return Collection
     */
    public function listLocalesForSelect(LocaleCodeRequest $request): Collection;

    /**
     * Return all available locales, as config('asgard.core.available-locales')
     *
     * @return Collection
     */
    public function availableLocales(): Collection;

    /**
     * Return same locales as app()->config->get('laravellocalization.supportedLocales')
     *
     * @return Collection
     */
    public function supportedLocales(): Collection;

    /**
     * Return same locales as app()->config->get('translatable.locales')
     *
     * @param array $locales
     * @return Collection
     */
    public function translatableLocales(): Collection;
}
