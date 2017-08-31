<?php

namespace Modules\Page\Entities;

use Dimsav\Translatable\Translatable;
use Modules\Tag\Traits\TaggableTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\NamespacedEntity;
use Modules\Tag\Contracts\TaggableInterface;
use Modules\Core\Doctrine\HasDynamicRelationships;

class Page extends Model implements TaggableInterface
{
    use Translatable, HasDynamicRelationships, TaggableTrait, NamespacedEntity;

    protected $table = 'page__pages';

    public $translatedAttributes = [
        'page_id',
        'title',
        'slug',
        'status',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
    ];

    protected $fillable = [
        'is_home',
        'template',
        // Translatable fields
        'page_id',
        'title',
        'slug',
        'status',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
    ];

    protected $casts = [
        'is_home' => 'boolean',
    ];

    protected static $entityNamespace = 'asgardcms/page';
}
