<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;

class MenuitemTranslation extends Model
{
    public $fillable = ['title', 'uri', 'url', 'status', 'locale', 'description'];
    protected $table = 'menu__menuitem_translations';
}
