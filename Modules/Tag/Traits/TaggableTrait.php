<?php

namespace Modules\Tag\Traits;

use Illuminate\Database\Eloquent\Builder;
use Modules\Tag\Entities\Tag;

trait TaggableTrait
{
    /**
     * {@inheritdoc}
     */
    protected static $tagsModel = Tag::class;

    /**
     * {@inheritdoc}
     */
    public static function getTagsModel()
    {
        return static::$tagsModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function setTagsModel($model)
    {
        static::$tagsModel = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function scopeWhereTag(Builder $query, $tags, $type = 'slug')
    {
        if (is_string($tags) === true) {
            $tags = [$tags];
        }
        $query->with('translations');

        foreach ($tags as $tag) {
            $query->whereHas('tags', function (Builder $query) use ($type, $tag) {
                $query->whereHas('translations', function (Builder $query) use ($type, $tag) {
                    $query->where($type, $tag);
                });
            });
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function scopeWithTag(Builder $query, $tags, $type = 'slug')
    {
        if (is_string($tags) === true) {
            $tags = [$tags];
        }
        $query->with('translations');

        return $query->whereHas('tags', function (Builder $query) use ($type, $tags) {
            $query->whereHas('translations', function (Builder $query) use ($type, $tags) {
                $query->whereIn($type, $tags);
            });
        });
    }

    /**
     * {@inheritdoc}
     */
    public function tags()
    {
        return $this->morphToMany(static::$tagsModel, 'taggable', 'tag__tagged', 'taggable_id', 'tag_id');
    }

    /**
     * {@inheritdoc}
     */
    public static function createTagsModel()
    {
        return new static::$tagsModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function allTags()
    {
        $instance = new static;

        return $instance->createTagsModel()->with('translations')->whereNamespace($instance->getEntityClassName());
    }

    /**
     * {@inheritdoc}
     */
    public function setTags($tags, $type = 'slug')
    {
        if (empty($tags)) {
            $tags = [];
        }

        // Get the current entity tags
        $entityTags = $this->tags->pluck($type)->all();

        // Prepare the tags to be added and removed
        $tagsToAdd = array_diff($tags, $entityTags);
        $tagsToDel = array_diff($entityTags, $tags);

        // Detach the tags
        if (count($tagsToDel) > 0) {
            $this->untag($tagsToDel);
        }

        // Attach the tags
        if (count($tagsToAdd) > 0) {
            $this->tag($tagsToAdd);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function tag($tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function addTag($name)
    {
        $tag = $this->createTagsModel()->where('namespace', $this->getEntityClassName())
            ->with('translations')
            ->whereHas('translations', function (Builder $q) use ($name) {
                $q->where('slug', $this->generateTagSlug($name));
            })->first();

        if ($tag === null) {
            $tag = new Tag([
                'namespace' => $this->getEntityClassName(),
                app()->getLocale() => [
                    'slug' => $this->generateTagSlug($name),
                    'name' => $name,
                ],
            ]);
        }
        if ($tag->exists === false) {
            $tag->save();
        }

        if ($this->tags->contains($tag->id) === false) {
            $this->tags()->attach($tag);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function untag($tags = null)
    {
        $tags = $tags ?: $this->tags->pluck('name')->all();

        foreach ($tags as $tag) {
            $this->removeTag($tag);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTag($name)
    {
        $tag = $this->createTagsModel()
            ->where('namespace', $this->getEntityClassName())
            ->with('translations')
            ->whereHas('translations', function (Builder $q) use ($name) {
                $q->where('slug', $this->generateTagSlug($name));
            })->first();

        if ($tag) {
            $this->tags()->detach($tag);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClassName()
    {
        if (isset(static::$entityNamespace)) {
            return static::$entityNamespace;
        }

        return $this->tags()->getMorphClass();
    }

    /**
     * {@inheritdoc}
     */
    public function generateTagSlug($name, $separator = '-')
    {
        // Convert all dashes/underscores into separator
        $flip = $separator == '-' ? '_' : '-';

        $name = preg_replace('!['.preg_quote($flip).']+!u', $separator, $name);

        // Replace @ with the word 'at'
        $name = str_replace('@', $separator.'at'.$separator, $name);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $name = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($name));

        // Replace all separator characters and whitespace by a single separator
        $name = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $name);

        return trim($name, $separator);
    }
}
