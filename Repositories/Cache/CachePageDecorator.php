<?php

namespace Modules\Page\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Page\Repositories\PageRepository;

class CachePageDecorator extends BaseCacheDecorator implements PageRepository
{
    /**
     * @var PageRepository
     */
    protected $repository;

    public function __construct(PageRepository $page)
    {
        parent::__construct();
        $this->entityName = 'pages';
        $this->repository = $page;
    }

    /**
     * Find the page set as homepage
     *
     * @return object
     */
    public function findHomepage()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findHomepage", $this->cacheTime,
                function () {
                    return $this->repository->findHomepage();
                }
            );
    }

    /**
     * Count all records
     * @return int
     */
    public function countAll()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.countAll", $this->cacheTime,
                function () {
                    return $this->repository->countAll();
                }
            );
    }

    /**
     * @param $slug
     * @param $locale
     * @return object
     */
    public function findBySlugInLocale($slug, $locale)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findBySlugInLocale.{$slug}.{$locale}", $this->cacheTime,
                function () use ($slug, $locale) {
                    return $this->repository->findBySlugInLocale($slug, $locale);
                }
            );
    }
}
