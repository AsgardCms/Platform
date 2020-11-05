<?php

namespace Modules\Tag\Blade;

use Illuminate\Support\Arr;
use Modules\Tag\Contracts\TaggableInterface;
use Modules\Tag\Repositories\TagRepository;

class TagWidget
{
    /**
     * @var TagRepository
     */
    private $tag;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var TaggableInterface|null
     */
    private $entity;
    /**
     * @var string|null
     */
    private $view;
    /**
     * @var string|null
     */
    private $name;

    public function __construct(TagRepository $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @param $arguments
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($arguments)
    {
        $this->extractArguments($arguments);

        $view = $this->view ?: 'tag::admin.fields.tags';

        $name = $this->name ?: 'Tags';

        $availableTags = $this->tag->allForNamespace($this->namespace);

        $tags = $this->getTags();

        return view($view, compact('availableTags', 'tags', 'name'));
    }

    /**
     * Extract the possible arguments as class properties
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->namespace = Arr::get($arguments, 0);
        $this->entity = Arr::get($arguments, 1);
        $this->view = Arr::get($arguments, 2);
        $this->name = Arr::get($arguments, 3);
    }

    /**
     * Get the available tags, if an entity is available from that
     * @return array
     */
    private function getTags()
    {
        if ($this->entity === null) {
            return request()->old('tags', []);
        }

        return request()->old('tags', $this->entity->tags->pluck('slug')->toArray());
    }
}
