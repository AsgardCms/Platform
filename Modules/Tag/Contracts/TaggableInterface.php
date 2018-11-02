<?php

namespace Modules\Tag\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface TaggableInterface
{
    /**
     * The Eloquent tag entity name.
     */
    public static function getEntityNamespace(): string;

    /**
     * Returns the Eloquent tags entity name.
     */
    public static function getTagsModel(): string;

    /**
     * Sets the Eloquent tags entity name.
     */
    public static function setTagsModel(string $model);

    /**
     * Get all the entities with the given tag(s)
     * Optionally specify the column on which
     * to perform the search operation.
     *
     * @param string|array $tags
     */
    public function scopeWhereTag(Builder $query, $tags, string $type = 'slug'): Builder;

    /**
     * Get all the entities with one of the given tag(s)
     * Optionally specify the column on which
     * to perform the search operation.
     *
     * @param string|array $tags
     */
    public function scopeWithTag(Builder $query, $tags, string $type = 'slug'): Builder;

    /**
     * Define the eloquent morphMany relationship
     */
    public function tags(): MorphToMany;

    /**
     * Returns all the tags under the current entity namespace.
     */
    public static function allTags(): Builder;

    /**
     * Creates a new model instance.
     */
    public static function createTagsModel(): Model;

    /**
     * Syncs the given tags.
     *
     * @param  string|array $tags
     */
    public function setTags($tags, string $type = 'name'): bool;

    /**
     * Detaches multiple tags from the entity or if no tags are
     * passed, removes all the attached tags from the entity.
     *
     * @param  string|array|null $tags
     */
    public function untag($tags = null): bool;

    /**
     * Detaches the given tag from the entity.
     */
    public function removeTag(string $name);

    /**
     * Attaches multiple tags to the entity.
     *
     * @param  string|array $tags
     */
    public function tag($tags): bool;

    /**
     * Attaches the given tag to the entity.
     */
    public function addTag(string $name);
}
