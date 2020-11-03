<?php

namespace Modules\Media\Support\Collection;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as BaseCollection;

class NestedFoldersCollection extends Collection
{
    private $total;
    private $parentColumn;
    private $removeItemsWithMissingAncestor = true;
    private $indentChars = '&nbsp;&nbsp;&nbsp;&nbsp;';

    public function __construct($items = [])
    {
        parent::__construct($items);
        $this->parentColumn = 'folder_id';
        $this->total = count($items);
    }

    /**
     * Nest items.
     * @return mixed NestableCollection
     */
    public function nest()
    {
        $parentColumn = $this->parentColumn;
        if (! $parentColumn) {
            return $this;
        }
        // Set id as keys.
        $this->items = $this->getDictionary();
        $keysToDelete = [];
        // Add empty collection to each items.
        $collection = $this->each(function ($item) {
            if (! $item->items) {
                $item->items = app(BaseCollection::class);
            }
        });
        // Remove items with missing ancestor.
        if ($this->removeItemsWithMissingAncestor) {
            $collection = $this->reject(function ($item) use ($parentColumn) {
                if ($item->$parentColumn) {
                    $missingAncestor = $this->anAncestorIsMissing($item);

                    return $missingAncestor;
                }
            });
        }
        // Add items to children collection.
        foreach ($collection->items as $key => $item) {
            if ($item->$parentColumn && isset($collection[$item->$parentColumn])) {
                $collection[$item->$parentColumn]->items->push($item);
                $keysToDelete[] = $item->id;
            }
        }
        // Delete moved items.
        $this->items = array_values(Arr::except($collection->items, $keysToDelete));

        return $this;
    }

    /**
     * Recursive function that flatten a nested Collection
     * with characters (default is four spaces).
     * @param string $column
     * @param BaseCollection|null $collection
     * @param int $level
     * @param array &$flattened
     * @param string|null $indentChars
     * @param string|boolen|null $parent_string
     * @return array
     */
    public function listsFlattened(
        $column = 'title',
        BaseCollection $collection = null,
        $level = 0,
        array &$flattened = [],
        $indentChars = null,
        $parent_string = null
    ) {
        $collection = $collection ?: $this;
        $indentChars = $indentChars ?: $this->indentChars;
        foreach ($collection as $item) {
            if ($parent_string) {
                $item_string = ($parent_string === true) ? $item->$column : $parent_string . $indentChars . $item->$column;
            } else {
                $item_string = str_repeat($indentChars, $level) . $item->$column;
            }
            $flattened[$item->id] = $item_string;
            if ($item->items) {
                $this->listsFlattened(
                    $column,
                    $item->items,
                    $level + 1,
                    $flattened,
                    $indentChars,
                    ($parent_string) ? $item_string : null
                );
            }
        }

        return $flattened;
    }

    /**
     * Returns a fully qualified version of listsFlattened.
     * @param BaseCollection|null $collection
     * @param string $column
     * @param int $level
     * @param array &$flattened
     * @param string $indentChars
     * @return array
     */
    public function listsFlattenedQualified(
        $column = 'title',
        BaseCollection $collection = null,
        $level = 0,
        array &$flattened = [],
        $indentChars = null
    ) {
        return $this->listsFlattened($column, $collection, $level, $flattened, $indentChars, true);
    }

    /**
     * Change the default indent characters when flattening lists.
     * @param string $indentChars
     * @return $this
     */
    public function setIndent(string $indentChars)
    {
        $this->indentChars = $indentChars;

        return $this;
    }

    /**
     * Force keeping items that have a missing ancestor.
     * @return NestableCollection
     */
    public function noCleaning()
    {
        $this->removeItemsWithMissingAncestor = false;

        return $this;
    }

    /**
     * Check if an ancestor is missing.
     * @param $item
     * @return bool
     */
    public function anAncestorIsMissing($item)
    {
        $parentColumn = $this->parentColumn;
        if (! $item->$parentColumn) {
            return false;
        }
        if (! $this->has($item->$parentColumn)) {
            return true;
        }
        $parent = $this[$item->$parentColumn];

        return $this->anAncestorIsMissing($parent);
    }

    /**
     * Get total items in nested collection.
     * @return int
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * Get total items for laravel 4 compatibility.
     * @return int
     */
    public function getTotal()
    {
        return $this->total();
    }
}
