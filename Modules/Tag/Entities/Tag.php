<?php

namespace Modules\Tag\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Translatable;

    protected $table = 'tag__tags';
    public $translatedAttributes = ['slug', 'name'];
    protected $fillable = ['namespace', 'slug', 'name'];
}
