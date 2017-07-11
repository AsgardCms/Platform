<?php

namespace Modules\Media\Blade;

use Modules\Media\Composers\Backend\PartialAssetComposer;

class MediaSingleDirective
{
    /**
     * @var string
     */
    private $zone;
    /**
     * @var
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

    public function show($arguments)
    {
        $this->extractArguments($arguments);

        $view = $this->view ?: 'media::admin.fields.new-file-link-single';
        view()->composer($view, PartialAssetComposer::class);

        $zone = $this->zone;

        $name = $this->name ?: ucwords(str_replace('_', ' ', $this->zone));

        if ($this->entity !== null) {
            $media = $this->entity->filesByZone($this->zone)->first();
        }

        return view($view, compact('media', 'zone', 'name'));
    }

    /**
     * Extract the possible arguments as class properties
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->zone = array_get($arguments, 0);
        $this->entity = array_get($arguments, 1);
        $this->view = array_get($arguments, 2);
        $this->name = array_get($arguments, 3);
    }
}
