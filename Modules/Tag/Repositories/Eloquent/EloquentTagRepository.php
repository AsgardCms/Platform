<?php

namespace Modules\Tag\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Tag\Events\TagIsCreating;
use Modules\Tag\Events\TagIsUpdating;
use Modules\Tag\Events\TagWasCreated;
use Modules\Tag\Events\TagWasUpdated;
use Modules\Tag\Repositories\TagRepository;

class EloquentTagRepository extends EloquentBaseRepository implements TagRepository
{
    /**
     * Get all the tags in the given namespace
     * @param string $namespace
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allForNamespace($namespace)
    {
        return $this->model->with('translations')->where('namespace', $namespace)->get();
    }

    public function create($data)
    {
        event($event = new TagIsCreating($data));
        $tag = $this->model->create($event->getAttributes());

        event(new TagWasCreated($tag));

        return $tag;
    }

    public function update($tag, $data)
    {
        event($event = new TagIsUpdating($tag, $data));
        $tag->update($event->getAttributes());

        event(new TagWasUpdated($tag));

        return $tag;
    }
}
