<?php

namespace Modules\Translation\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Modules\Translation\Http\Requests\LocaleCodeRequest;
use Modules\Translation\Repositories\LocaleRepository;

class EloquentLocaleRepository implements LocaleRepository
{
    private $locales;
    private $mapper;

    public function __construct()
    {
        $this->mapper = function (array $item, string $key) {
            return [
                'code' => $key,
                'name'   => trans('translation::locales.' . $key),
                'script' => $item['script'],
                'native' => $item['native'],
            ];
        };
        $this->locales = collect(config('asgard.core.available-locales'))
            ->map($this->mapper)
            ->values();
    }

    /**
     * @inheritdoc
     */
    public function listLocalesForSelect(LocaleCodeRequest $request): Collection
    {
        $locales = $this->locales;

        $search = $request->get('search');
        if ($search !== null) {
            $locales = $locales->filter(function ($locale) use ($search) {
                $name = $locale['name'];

                return stripos($name, $search) !== false;   // https://www.php.net/manual/en/function.stripos.php
            });
        }

        $order    = $request->get('order');
        $order_by = $request->get('order_by');
        switch ($order_by) {
            case 'code':
                if ($order == 'asc') {
                    $locales->sortBy('code');
                } else {
                    $locales->sortByDesc('code');
                }
                break;
            case 'name':
            default:
                if ($order == 'asc') {
                    $locales->sortBy('name');
                } else {
                    $locales->sortByDesc('name');
                }
                break;
        }

        return $locales->values();
    }

    /**
     * @inheritdoc
     */
    public function availableLocales(): Collection
    {
        return $this->locales;
    }

    /**
     * @inheritdoc
     */
    public function supportedLocales(): Collection
    {
        return collect(app()->config->get('laravellocalization.supportedLocales'))
            ->map($this->mapper)
            ->values();
    }

    /**
     * @inheritdoc
     */
    public function translatableLocales(): Collection
    {
        $translatable_locales = collect(app()->config->get('translatable.locales'))
            ->filter(function ($item) {
                return is_string($item);
            })->toArray();

        return $this->locales->filter(function ($locale) use ($translatable_locales) {
            return in_array($locale['code'], $translatable_locales);
        });
    }
}
