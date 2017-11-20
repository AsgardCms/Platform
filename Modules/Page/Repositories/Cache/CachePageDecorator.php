<?php

namespace Modules\Page\Repositories\Cache;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Page\Entities\Page;
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
        return $this->remember(function () {
            return $this->repository->findHomepage();
        });
    }

    /**
     * Count all records
     * @return int
     */
    public function countAll()
    {
        return $this->remember(function () {
            return $this->repository->countAll();
        });
    }

    /**
     * @param $slug
     * @param $locale
     * @return object
     */
    public function findBySlugInLocale($slug, $locale)
    {
        return $this->remember(function () use ($slug, $locale) {
            return $this->repository->findBySlugInLocale($slug, $locale);
        });
    }

    /**
     * Paginating, ordering and searching through pages for server side index table
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $page = $request->get('page');
        $order = $request->get('order');
        $orderBy = $request->get('order_by');
        $perPage = $request->get('per_page');
        $search = $request->get('search');

        $key = $this->getBaseKey() . "serverPaginationFilteringFor.{$page}-{$order}-{$orderBy}-{$perPage}-{$search}";

        return $this->remember(function () use ($request) {
            return $this->repository->serverPaginationFilteringFor($request);
        }, $key);
    }

    /**
     * @param Page $page
     * @return mixed
     */
    public function markAsOnlineInAllLocales(Page $page)
    {
        $this->clearCache();

        return $this->repository->markAsOnlineInAllLocales($page);
    }

    /**
     * @param Page $page
     * @return mixed
     */
    public function markAsOfflineInAllLocales(Page $page)
    {
        $this->clearCache();

        return $this->repository->markAsOfflineInAllLocales($page);
    }

    /**
     * @param array $pageIds [int]
     * @return mixed
     */
    public function markMultipleAsOnlineInAllLocales(array $pageIds)
    {
        $this->clearCache();

        return $this->repository->markMultipleAsOnlineInAllLocales($pageIds);
    }

    /**
     * @param array $pageIds [int]
     * @return mixed
     */
    public function markMultipleAsOfflineInAllLocales(array $pageIds)
    {
        $this->clearCache();

        return $this->repository->markMultipleAsOfflineInAllLocales($pageIds);
    }
}
