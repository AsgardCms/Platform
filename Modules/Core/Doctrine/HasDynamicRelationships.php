<?php

namespace Modules\Core\Doctrine;

use ReflectionClass;

trait HasDynamicRelationships
{
    /**
    * Dynamically retrieve attributes on the model.
    *
    * @param string $key
    * @return mixed
    */
    public function __get($key)
    {
        if ($this->setDynamicRelation($key)) {
            return $this->getRelation($key)->get();
        }

        return parent::__get($key);
    }

    /**
    * Handle dynamic method calls into the model.
    *
    * @param string $method
    * @param array $parameters
    * @return mixed
    */
    public function __call($method, $parameters)
    {
        if ($this->setDynamicRelation($method)) {
            return $this->getRelation($method);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Set dynamic relationship to the model.
     *
     * @param string $key
     * @return bool
     */
    protected function setDynamicRelation($key)
    {
        $model = strtolower((new ReflectionClass($this))->getShortName());
        
        $config = "asgard.{$model}.config.relations.{$key}";

        if (config()->has($config)) {
            $closure = config()->get($config);
            $relation = call_user_func($closure->bindTo($this, static::class));
            $this->setRelation($key, $relation);

            return true;
        }
    }
}
