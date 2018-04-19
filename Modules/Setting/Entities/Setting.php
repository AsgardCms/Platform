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

    public function hasMedia(): bool
    {
        $value = json_decode($this->plainValue, true);
        return is_array($value) && isset($value['media_single']) && $setting->files->count();
    }
}
