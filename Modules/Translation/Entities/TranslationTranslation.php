<?php

namespace Modules\Translation\Entities;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * @property string value
 */
class TranslationTranslation extends Model
{
    use RevisionableTrait;
    public $timestamps = false;
    protected $fillable = ['value'];
    protected $table = 'translation__translation_translations';

    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 100;
    protected $revisionCreationsEnabled = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->historyLimit = config('asgard.translation.config.revision-history-limit', 100);
    }

    public static function boot()
    {
        parent::boot();
    }
}
