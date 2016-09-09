<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Translation\Providers\TranslationServiceProvider;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\LaravelModulesServiceProvider;

class AsgardServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (class_exists(TranslationServiceProvider::class)) {
            $this->app->register(TranslationServiceProvider::class);
        }

        $this->app->register(LaravelModulesServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Module', Module::class);
    }
}
