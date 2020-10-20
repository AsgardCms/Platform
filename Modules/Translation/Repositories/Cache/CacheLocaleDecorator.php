<?php

namespace Modules\Translation\Repositories\Cache;

use Illuminate\Support\Collection;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Translation\Http\Requests\LocaleCodeRequest;
use Modules\Translation\Repositories\LocaleRepository;

class CacheLocaleDecorator extends BaseCacheDecorator implements LocaleRepository
{
    public function __construct(LocaleRepository $recipe)
    {
        parent::__construct();
        $this->entityName = 'translation.translations';
        $this->repository = $recipe;
    }

    /** {@inheritdoc} */
    public function apiIndex(LocaleCodeRequest $request) : Collection
    {
        return $this->remember(
            function () use ($request) {
                return $this->repository->apiIndex($request);
            },
            'locales_search_' . $request->get('search')
        );
    }

    /** {@inheritdoc} */
    public function listLocalesForSelect(LocaleCodeRequest $request): Collection
    {
        return $this->remember(
            function () use ($request) {
                return $this->repository->listLocalesForSelect($request);
            },
            'locales_for_select'
        );
    }

    /** {@inheritdoc} */
    public function availableLocales() : Collection
    {
        return $this->remember(
            function () {
                return $this->repository->availableLocales();
            },
            'locales_availables'
        );
    }

    /** {@inheritdoc} */
    public function supportedLocales() : Collection
    {
        return $this->remember(
            function () {
                return $this->repository->supportedLocales();
            },
            'locales_supported'
        );
    }

    /** {@inheritdoc} */
    public function translatableLocales() : Collection
    {
        return $this->remember(
            function () {
                return $this->repository->translatableLocales();
            },
            'locales_translatables'
        );
    }
}
