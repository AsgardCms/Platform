<?php

namespace Modules\Translation\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use Translatable;

    protected $table = 'translation__translations';
    public $translatedAttributes = ['value'];
    protected $fillable = ['key', 'value'];
}
