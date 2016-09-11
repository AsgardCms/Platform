<?php

namespace Modules\Core\Repositories\Cache;

use Illuminate\Cache\Repository;
use Illuminate\Config\Repository as ConfigRepository;
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
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.find.{$id}", $this->cacheTime,
                function () use ($id) {
                    return $this->repository->find($id);
                }
            );
    }

    /**
     * @inheritdoc
     */
    public function all()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.all", $this->cacheTime,
                function () {
                    return $this->repository->all();
                }
            );
    }

    /**
     * @inheritdoc
     */
    public function paginate($perPage = 15)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.paginate.{$perPage}", $this->cacheTime,
                function () use ($perPage) {
                    return $this->repository->paginate($perPage);
                }
            );
    }

    /**
     * @inheritdoc
     */
    public function allTranslatedIn($lang)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.allTranslatedIn.{$lang}", $this->cacheTime,
                function () use ($lang) {
                    return $this->repository->allTranslatedIn($lang);
                }
            );
    }

    /**
     * @inheritdoc
     */
    public function findBySlug($slug)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findBySlug.{$slug}", $this->cacheTime,
                function () use ($slug) {
                    return $this->repository->findBySlug($slug);
                }
            );
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
        $tagIdentifier = json_encode($attributes);

        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findByAttributes.{$tagIdentifier}", $this->cacheTime,
                function () use ($attributes) {
                    return $this->repository->findByAttributes($attributes);
                }
            );
    }

    /**
     * @inheritdoc
     */
    public function getByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc')
    {
        $tagIdentifier = json_encode($attributes);

        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findByAttributes.{$tagIdentifier}.{$orderBy}.{$sortOrder}", $this->cacheTime,
                function () use ($attributes, $orderBy, $sortOrder) {
                    return $this->repository->getByAttributes($attributes, $orderBy, $sortOrder);
                }
            );
    }

    /**
     * @inheritdoc
     */
    public function findByMany(array $ids)
    {
        $tagIdentifier = json_encode($ids);

        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findByMany.{$tagIdentifier}", $this->cacheTime,
                function () use ($ids) {
                    return $this->repository->findByMany($ids);
                }
            );
    }

    /**
     * @inheritdoc
     */
    public function clearCache()
    {
        return $this->cache->tags($this->entityName)->flush();
    }
}
