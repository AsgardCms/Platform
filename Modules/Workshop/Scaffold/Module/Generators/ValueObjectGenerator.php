<?php

namespace Modules\Workshop\Scaffold\Module\Generators;

class ValueObjectGenerator extends Generator
{
    /**
     * Generate the given files
     *
     * @param  array $valueObjects
     * @return void
     */
    public function generate(array $valueObjects)
    {
        if (! $this->finder->isDirectory($this->getModulesPath('ValueObjects'))) {
            $this->finder->makeDirectory($this->getModulesPath('ValueObjects'));
        }

        foreach ($valueObjects as $valueObject) {
            $this->writeFile(
                $this->getModulesPath("ValueObjects/$valueObject"),
                $this->getContentForStub('value-object.stub', $valueObject)
            );
        }
    }
}
