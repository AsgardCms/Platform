<?php

namespace Modules\Setting\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Support\Traits\MediaRelation;

class Setting extends Model
{
    use Translatable, MediaRelation;

    public $translatedAttributes = ['value', 'description'];
    protected $fillable = ['name', 'value', 'description', 'isTranslatable', 'plainValue'];
    protected $table = 'setting__settings';
}
