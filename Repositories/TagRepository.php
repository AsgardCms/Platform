<?php

namespace Modules\Tag\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface TagRepository extends BaseRepository
{
    /**
     * Get all the tags in the given namespace
     * @param string $namespace
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allForNamespace($namespace);
}
