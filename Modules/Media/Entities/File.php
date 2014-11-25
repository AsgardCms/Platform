<?php namespace Modules\Media\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use Translatable;

    public $translatedAttributes = ['description', 'alt_attribute', 'keywords'];
    protected $fillable = [
        'description',
        'alt_attribute',
        'keywords',
        'filename',
        'path',
        'extension',
        'mimetype',
        'width',
        'height',
        'filesize',
        'folder_id'
    ];
}
