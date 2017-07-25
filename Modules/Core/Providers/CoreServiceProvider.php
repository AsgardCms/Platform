<?php

namespace Modules\Core\Providers;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Blade\AsgardEditorDirective;
use Modules\Core\Console\DeleteModuleCommand;
use Modules\Core\Console\DownloadModuleCommand;
use Modules\Core\Console\InstallCommand;
use Modules\Core\Console\PublishModuleAssetsCommand;
use Modules\Core\Console\PublishThemeAssetsCommand;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\EditorIsRendering;
use Modules\Core\Events\Handlers\RegisterCoreSidebar;
use Modules\Core\Foundation\Theme\ThemeManager;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Nwidart\Modules\Module;

class CoreServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The filters base class name.
     *
     * @var array
     */
    protected $middleware = [
        'Core' => [
            'permissions'           => 'PermissionMiddleware',
            'auth.admin'            => 'AdminMiddleware',
            'public.checkLocale'    => 'PublicMiddleware',
            'localizationRedirect'  => 'LocalizationMiddleware',
            'can' => 'Authorization',
        ],
    ];

    public function boot()
    {
        $this->publishConfig('core', 'available-locales');
        $this->publishConfig('core', 'config');
        $this->publishConfig('core', 'core');
        $this->publishConfig('core', 'settings');
        $this->publishConfig('core', 'permissions');

        $this->registerMiddleware($this->app['router']);
        $this->registerModuleResourceNamespaces();

        $this->bladeDirectives();
        $this->app['events']->listen(EditorIsRendering::class, config('asgard.core.core.wysiwyg-handler'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('asgard.isInstalled', function () {
            return true === env('INSTALLED', false);
        });
        $this->app->singleton('asgard.onBackend', function () {
            return $this->onBackend();
        });

        $this->registerCommands();
        $this->registerServices();
        $this->setLocalesConfigurations();

        $this->app->bind('core.asgard.editor', function () {
            return new AsgardEditorDirective();
        });

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('core', RegisterCoreSidebar::class)
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    /**
     * Register the filters.
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $module => $middlewares) {
            foreach ($middlewares as $name => $middleware) {
                $class = "Modules\\{$module}\\Http\\Middleware\\{$middleware}";

                $router->aliasMiddleware($name, $class);
            }
        }
    }

    /**
     * Register the console commands
     */
    private function registerCommands()
    {
        $this->commands([
            InstallCommand::class,
            PublishThemeAssetsCommand::class,
            PublishModuleAssetsCommand::class,
            DownloadModuleCommand::class,
            DeleteModuleCommand::class,
        ]);
    }

    private function registerServices()
    {
        $this->app->singleton(ThemeManager::class, function ($app) {
            $path = $app['config']->get('asgard.core.core.themes_path');

            return new ThemeManager($app, $path);
        });

        $this->app->singleton('asgard.ModulesList', function () {
            return [
                'block',
                'blog',
                'core',
                'dashboard',
                'media',
                'menu',
                'notification',
                'page',
                'setting',
                'tag',
                'translation',
                'user',
                'workshop',
            ];
        });
    }

    /**
     * Register the modules aliases
     */
    private function registerModuleResourceNamespaces()
    {
        $themes = [];

        // Saves about 20ms-30ms at loading
        if ($this->app['config']->get('asgard.core.core.enable-theme-overrides') === true) {
            $themeManager = app(ThemeManager::class);

            $themes = [
                'backend' => $themeManager->find(config('asgard.core.core.admin-theme'))->getPath(),
                'frontend' => $themeManager->find(setting('core::template', null, 'Flatly'))->getPath(),
            ];
        }

        foreach ($this->app['modules']->getOrdered() as $module) {
            $this->registerViewNamespace($module, $themes);
            $this->registerLanguageNamespace($module);
        }
    }

    /**
     * Register the view namespaces for the modules
     * @param Module $module
     * @param array $themes
     */
    protected function registerViewNamespace(Module $module, array $themes)
    {
        $hints = [];
        $moduleName = $module->getLowerName();

        if (is_core_module($moduleName)) {
            $configFile = 'config';
            $configKey = 'asgard.' . $moduleName . '.' . $configFile;

            $this->mergeConfigFrom($module->getExtraPath('Config' . DIRECTORY_SEPARATOR . $configFile . '.php'), $configKey);
            $moduleConfig = $this->app['config']->get($configKey . '.useViewNamespaces');

            if (count($themes) > 0) {
                if ($themes['backend'] !== null && array_get($moduleConfig, 'backend-theme') === true) {
                    $hints[] = $themes['backend'] . '/views/modules/' . $moduleName;
                }
                if ($themes['frontend'] !== null && array_get($moduleConfig, 'frontend-theme') === true) {
                    $hints[] = $themes['frontend'] . '/views/modules/' . $moduleName;
                }
            }
            if (array_get($moduleConfig, 'resources') === true) {
                $hints[] = base_path('resources/views/asgard/' . $moduleName);
            }
        }

        $hints[] = $module->getPath() . '/Resources/views';

        $this->app['view']->addNamespace($moduleName, $hints);
    }

    /**
     * Register the language namespaces for the modules
     * @param Module $module
     */
    protected function registerLanguageNamespace(Module $module)
    {
        $moduleName = $module->getLowerName();

        $langPath = base_path("resources/lang/$moduleName");
        $secondPath = base_path("resources/lang/translation/$moduleName");

        if ($moduleName !== 'translation' && $this->hasPublishedTranslations($langPath)) {
            return $this->loadTranslationsFrom($langPath, $moduleName);
        }
        if ($this->hasPublishedTranslations($secondPath)) {
            return $this->loadTranslationsFrom($secondPath, $moduleName);
        }
        if ($this->moduleHasCentralisedTranslations($module)) {
            return $this->loadTranslationsFrom($this->getCentralisedTranslationPath($module), $moduleName);
        }

        return $this->loadTranslationsFrom($module->getPath() . '/Resources/lang', $moduleName);
    }

    /**
     * @param $file
     * @param $package
     * @return string
     */
    private function getConfigFilename($file)
    {
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($file));
    }

    /**
     * Set the locale configuration for
     * - laravel localization
     * - laravel translatable
     */
    private function setLocalesConfigurations()
    {
        if ($this->app['asgard.isInstalled'] === false || $this->app->runningInConsole() === true) {
            return;
        }

        $localeConfig = $this->app['cache']
            ->tags('setting.settings', 'global')
            ->remember("asgard.locales", 120,
                function () {
                    return DB::table('setting__settings')->whereName('core::locales')->first();
                }
            );
        if ($localeConfig) {
            $locales = json_decode($localeConfig->plainValue);
            $availableLocales = [];
            foreach ($locales as $locale) {
                $availableLocales = array_merge($availableLocales, [$locale => config("available-locales.$locale")]);
            }

            $laravelDefaultLocale = $this->app->config->get('app.locale');

            if (! in_array($laravelDefaultLocale, array_keys($availableLocales))) {
                $this->app->config->set('app.locale', array_keys($availableLocales)[0]);
            }
            $this->app->config->set('laravellocalization.supportedLocales', $availableLocales);
            $this->app->config->set('translatable.locales', $locales);
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    private function hasPublishedTranslations($path)
    {
        return is_dir($path);
    }

    /**
     * Does a Module have it's Translations centralised in the Translation module?
     * @param Module $module
     * @return bool
     */
    private function moduleHasCentralisedTranslations(Module $module)
    {
        return is_dir($this->getCentralisedTranslationPath($module));
    }

    /**
     * Get the absolute path to the Centralised Translations for a Module (via the Translations module)
     * @param Module $module
     * @return string
     */
    private function getCentralisedTranslationPath(Module $module)
    {
        $path = config('modules.paths.modules') . '/Translation';

        return $path . "/Resources/lang/{$module->getLowerName()}";
    }

    /**
     * List of Custom Blade Directives
     */
    public function bladeDirectives()
    {
        if (app()->environment() === 'testing') {
            return;
        }

        /**
         * Set variable.
         * Usage: @set($variable, value)
         */
        Blade::directive('set', function ($expression) {
            list($variable, $value) = $this->getArguments($expression);

            return "<?php {$variable} = {$value}; ?>";
        });

        $this->app['blade.compiler']->directive('editor', function ($value) {
            return "<?php echo AsgardEditorDirective::show([$value]); ?>";
        });
    }

    /**
     * Checks if the current url matches the configured backend uri
     * @return bool
     */
    private function onBackend()
    {
        $url = app(Request::class)->url();
        if (str_contains($url, config('asgard.core.core.admin-prefix'))) {
            return true;
        }

        return false;
    }

    /**
     * Get argument array from argument string.
     * @param $argumentString
     * @return array
     */
    private function getArguments($argumentString)
    {
        return str_getcsv($argumentString, ',', "'");
    }
}
