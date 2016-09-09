<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
    protected $fillable = ['title', 'status'];
    protected $table = 'menu__menu_translations';
}
