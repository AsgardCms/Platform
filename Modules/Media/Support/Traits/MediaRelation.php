<?php

namespace Modules\Media\Support\Traits;

use Modules\Media\Entities\File;

trait MediaRelation
{
    /**
     * Make the Many To Many Morph To Relation
     * @return object
     */
    public function files()
    {
        return $this->morphToMany(File::class, 'imageable', 'media__imageables')->withPivot('zone', 'id')->withTimestamps()->orderBy('order');
    }

    /**
     * Make the Many to Many Morph to Relation with specific zone
     * @param string $zone
     * @return object
     */
    public function filesByZone($zone)
    {
        return $this->morphToMany(File::class, 'imageable', 'media__imageables')
            ->withPivot('zone', 'id')
            ->wherePivot('zone', '=', $zone)
            ->withTimestamps()
            ->orderBy('order');
    }
}
