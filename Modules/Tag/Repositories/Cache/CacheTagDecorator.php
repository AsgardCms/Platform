<?php

namespace Modules\Tag\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Tag\Repositories\TagRepository;

class CacheTagDecorator extends BaseCacheDecorator implements TagRepository
{
    public function __construct(TagRepository $tag)
    {
        parent::__construct();
        $this->entityName = 'tag.tags';
        $this->repository = $tag;
    }

    /**
     * Get all the tags in the given namespace
     * @param string $namespace
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allForNamespace($namespace)
    {
        return $this->remember(function () use ($namespace) {
            return $this->repository->allForNamespace($namespace);
        });
    }
}
