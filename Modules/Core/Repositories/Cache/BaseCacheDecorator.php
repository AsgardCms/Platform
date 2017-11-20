<?php

namespace Modules\Core\Repositories\Cache;

use Illuminate\Cache\Repository;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Repositories\BaseRepository;

abstract class BaseCacheDecorator implements BaseRepository
{
    /**
     * @var \Modules\Core\Repositories\BaseRepository
     */
    protected $repository;
    /**
     * @var Repository
     */
    protected $cache;
    /**
     * @var string The entity name
     */
    protected $entityName;
    /**
     * @var string The application locale
     */
    protected $locale;

    /**
     * @var int caching time
     */
    protected $cacheTime;

    public function __construct()
    {
        $this->cache = app(Repository::class);
        $this->cacheTime = app(ConfigRepository::class)->get('cache.time', 60);
        $this->locale = app()->getLocale();
    }

    /**
     * @inheritdoc
     */
    public function find($id)
    {
        return $this->remember(function () use ($id) {
            return $this->repository->find($id);
        });
    }

    /**
     * @inheritdoc
     */
    public function all()
    {
        return $this->remember(function () {
            return $this->repository->all();
        });
    }

    /**
     * @inheritdoc
     */
    public function allWithBuilder() : Builder
    {
        return $this->remember(function () {
            return $this->repository->allWithBuilder();
        });
    }

    /**
     * @inheritdoc
     */
    public function paginate($perPage = 15)
    {
        return $this->remember(function () use ($perPage) {
            return $this->repository->paginate($perPage);
        });
    }

    /**
     * @inheritdoc
     */
    public function allTranslatedIn($lang)
    {
        return $this->remember(function () use ($lang) {
            return $this->repository->allTranslatedIn($lang);
        });
    }

    /**
     * @inheritdoc
     */
    public function findBySlug($slug)
    {
        return $this->remember(function () use ($slug) {
            return $this->repository->findBySlug($slug);
        });
    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->create($data);
    }

    /**
     * @inheritdoc
     */
    public function update($model, $data)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->update($model, $data);
    }

    /**
     * @inheritdoc
     */
    public function destroy($model)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->destroy($model);
    }

    /**
     * @inheritdoc
     */
    public function findByAttributes(array $attributes)
    {
        return $this->remember(function () use ($attributes) {
            return $this->repository->findByAttributes($attributes);
        });
    }

    /**
     * @inheritdoc
     */
    public function getByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc')
    {
        return $this->remember(function () use ($attributes, $orderBy, $sortOrder) {
            return $this->repository->getByAttributes($attributes, $orderBy, $sortOrder);
        });
    }

    /**
     * @inheritdoc
     */
    public function findByMany(array $ids)
    {
        return $this->remember(function () use ($ids) {
            return $this->repository->findByMany($ids);
        });
    }

    /**
     * @inheritdoc
     */
    public function clearCache()
    {
        $store = $this->cache;

        if (method_exists($this->cache->getStore(), 'tags')) {
            $store = $store->tags($this->entityName);
        }

        return $store->flush();
    }

    /**
     * @param \Closure $callback
     * @param null|string $key
     * @return mixed
     */
    protected function remember(\Closure $callback, $key = null)
    {
        $cacheKey = $this->makeCacheKey($key);

        $store = $this->cache;

        if (method_exists($this->cache->getStore(), 'tags')) {
            $store = $store->tags([$this->entityName, 'global']);
        }

        return $store->remember($cacheKey, $this->cacheTime, $callback);
    }

    /**
     * Generate a cache key with the called method name and its arguments
     * If a key is provided, use that instead
     * @param null|string $key
     * @return string
     */
    private function makeCacheKey($key = null): string
    {
        if ($key !== null) {
            return $key;
        }

        $cacheKey = $this->getBaseKey();

        $backtrace = debug_backtrace()[2];

        return sprintf("$cacheKey %s %s", $backtrace['function'], \serialize($backtrace['args']));
    }

    /**
     * @return string
     */
    protected function getBaseKey(): string
    {
        return sprintf(
            'asgardcms -locale:%s -entity:%s',
            $this->locale,
            $this->entityName
        );
    }
}
