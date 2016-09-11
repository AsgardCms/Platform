<?php

namespace Modules\Tag\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface TaggableInterface
{
    /**
     * The Eloquent tag entity name.
     * @var string
     */
    public static function getEntityNamespace();

    /**
     * Returns the Eloquent tags entity name.
     * @return string
     */
    public static function getTagsModel();

    /**
     * Sets the Eloquent tags entity name.
     * @param string $model
     * @return void
     */
    public static function setTagsModel($model);

    /**
     * Get all the entities with the given tag(s)
     * Optionally specify the column on which
     * to perform the search operation.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $tags
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereTag(Builder $query, $tags, $type = 'slug');

    /**
     * Get all the entities with one of the given tag(s)
     * Optionally specify the column on which
     * to perform the search operation.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $tags
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithTag(Builder $query, $tags, $type = 'slug');

    /**
     * Define the eloquent morphMany relationship
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags();

    /**
     * Returns all the tags under the current entity namespace.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function allTags();

    /**
     * Creates a new model instance.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function createTagsModel();

    /**
     * Syncs the given tags.
     * @param  string|array $tags
     * @param  string $type
     * @return bool
     */
    public function setTags($tags, $type = 'name');

    /**
     * Detaches multiple tags from the entity or if no tags are
     * passed, removes all the attached tags from the entity.
     * @param  string|array|null $tags
     * @return bool
     */
    public function untag($tags = null);

    /**
     * Detaches the given tag from the entity.
     * @param  string  $name
     * @return void
     */
    public function removeTag($name);

    /**
     * Attaches multiple tags to the entity.
     * @param  string|array  $tags
     * @return bool
     */
    public function tag($tags);

    /**
     * Attaches the given tag to the entity.
     * @param  string $name
     * @return void
     */
    public function addTag($name);
}
