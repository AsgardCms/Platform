<?php

namespace Modules\Workshop\Scaffold\Module\Generators;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class EntityGenerator extends Generator
{
    /**
     * @var \Illuminate\Contracts\Console\Kernel
     */
    protected $artisan;

    public function __construct(Filesystem $finder, Repository $config)
    {
        parent::__construct($finder, $config);
        $this->artisan = app('Illuminate\Contracts\Console\Kernel');
    }

    protected $views = [
        'index-view.stub' => 'Resources/views/admin/$ENTITY_NAME$/index.blade',
        'create-view.stub' => 'Resources/views/admin/$ENTITY_NAME$/create.blade',
        'edit-view.stub' => 'Resources/views/admin/$ENTITY_NAME$/edit.blade',
        'create-fields.stub' => 'Resources/views/admin/$ENTITY_NAME$/partials/create-fields.blade',
        'edit-fields.stub' => 'Resources/views/admin/$ENTITY_NAME$/partials/edit-fields.blade',
    ];

    /**
     * Generate the given entities
     *
     * @param array $entities
     * @param bool $regenerateSidebar
     */
    public function generate(array $entities, $regenerateSidebar = true)
    {
        $entityType = strtolower($this->entityType);
        $entityTypeStub = "entity-{$entityType}.stub";

        if ($regenerateSidebar === true) {
            $this->generateSidebarListener($entities);
        }

        foreach ($entities as $entity) {
            $this->writeFile(
                $this->getModulesPath("Entities/$entity"),
                $this->getContentForStub($entityTypeStub, $entity)
            );
            $this->writeFile(
                $this->getModulesPath("Entities/{$entity}Translation"),
                $this->getContentForStub("{$entityType}-entity-translation.stub", $entity)
            );
            if ($this->entityType == 'Eloquent') {
                $this->generateMigrationsFor($entity);
            }
            $this->generateRepositoriesFor($entity);
            $this->generateControllerFor($entity);
            $this->generateRequestsFor($entity);
            $this->generateViewsFor($entity);
            $this->generateLanguageFilesFor($entity);
            $this->appendBindingsToServiceProviderFor($entity);
            $this->appendResourceRoutesToRoutesFileFor($entity);
            $this->appendPermissionsFor($entity);
            $this->appendSidebarLinksFor($entity);
            $this->appendBackendTranslations($entity);
        }
    }

    /**
     * Generate the repositories for the given entity
     *
     * @param string $entity
     */
    private function generateRepositoriesFor($entity)
    {
        if (! $this->finder->isDirectory($this->getModulesPath('Repositories/' . $this->entityType))) {
            $this->finder->makeDirectory($this->getModulesPath('Repositories/' . $this->entityType));
        }

        $entityType = strtolower($this->entityType);
        $this->writeFile(
            $this->getModulesPath("Repositories/{$entity}Repository"),
            $this->getContentForStub('repository-interface.stub', $entity)
        );
        $this->writeFile(
            $this->getModulesPath("Repositories/Cache/Cache{$entity}Decorator"),
            $this->getContentForStub('cache-repository-decorator.stub', $entity)
        );
        $this->writeFile(
            $this->getModulesPath("Repositories/{$this->entityType}/{$this->entityType}{$entity}Repository"),
            $this->getContentForStub("{$entityType}-repository.stub", $entity)
        );
    }

    /**
     * Generate the controller for the given entity
     *
     * @param string $entity
     */
    private function generateControllerFor($entity)
    {
        $path = $this->getModulesPath('Http/Controllers/Admin');
        if (! $this->finder->isDirectory($path)) {
            $this->finder->makeDirectory($path);
        }
        $this->writeFile(
            $this->getModulesPath("Http/Controllers/Admin/{$entity}Controller"),
            $this->getContentForStub('admin-controller.stub', $entity)
        );
    }

    /**
     * Generate the requests for the given entity
     *
     * @param string $entity
     */
    private function generateRequestsFor($entity)
    {
        $path = $this->getModulesPath('Http/Requests');
        if (! $this->finder->isDirectory($path)) {
            $this->finder->makeDirectory($path);
        }
        $this->writeFile(
            $this->getModulesPath("Http/Requests/Create{$entity}Request"),
            $this->getContentForStub('create-request.stub', $entity)
        );
        $this->writeFile(
            $this->getModulesPath("Http/Requests/Update{$entity}Request"),
            $this->getContentForStub('update-request.stub', $entity)
        );
    }

    /**
     * Generate views for the given entity
     *
     * @param string $entity
     */
    private function generateViewsFor($entity)
    {
        $lowerCasePluralEntity = strtolower(str_plural($entity));
        $this->finder->makeDirectory($this->getModulesPath("Resources/views/admin/{$lowerCasePluralEntity}/partials"), 0755, true);

        foreach ($this->views as $stub => $view) {
            $view = str_replace('$ENTITY_NAME$', $lowerCasePluralEntity, $view);
            $this->writeFile(
                $this->getModulesPath($view),
                $this->getContentForStub($stub, $entity)
            );
        }
    }

    /**
     * Generate language files for the given entity
     * @param string $entity
     */
    private function generateLanguageFilesFor($entity)
    {
        $lowerCaseEntity = str_plural(strtolower($entity));
        $path = $this->getModulesPath('Resources/lang/en');
        if (!$this->finder->isDirectory($path)) {
            $this->finder->makeDirectory($path);
        }
        $this->writeFile(
            $this->getModulesPath("Resources/lang/en/{$lowerCaseEntity}"),
            $this->getContentForStub('lang-entity.stub', $entity)
        );
    }

    /**
     * Generate migrations file for eloquent entities
     *
     * @param string $entity
     */
    private function generateMigrationsFor($entity)
    {
        usleep(250000);
        $lowercasePluralEntityName = strtolower(str_plural($entity));
        $lowercaseModuleName = strtolower($this->name);
        $migrationName = $this->getDateTimePrefix() . "create_{$lowercaseModuleName}_{$lowercasePluralEntityName}_table";
        $this->writeFile(
            $this->getModulesPath("Database/Migrations/{$migrationName}"),
            $this->getContentForStub('create-table-migration.stub', $entity)
        );
        usleep(250000);
        $lowercaseEntityName = strtolower($entity);
        $migrationName = $this->getDateTimePrefix() . "create_{$lowercaseModuleName}_{$lowercaseEntityName}_translations_table";
        $this->writeFile(
            $this->getModulesPath("Database/Migrations/{$migrationName}"),
            $this->getContentForStub('create-translation-table-migration.stub', $entity)
        );
    }

    /**
     * Append the IoC bindings for the given entity to the Service Provider
     *
     * @param  string                                       $entity
     * @throws FileNotFoundException
     */
    private function appendBindingsToServiceProviderFor($entity)
    {
        $moduleProviderContent = $this->finder->get($this->getModulesPath("Providers/{$this->name}ServiceProvider.php"));
        $binding = $this->getContentForStub('bindings.stub', $entity);
        $moduleProviderContent = str_replace('// add bindings', $binding, $moduleProviderContent);
        $this->finder->put($this->getModulesPath("Providers/{$this->name}ServiceProvider.php"), $moduleProviderContent);
    }

    /**
     * Append the routes for the given entity to the routes file
     *
     * @param  string                                       $entity
     * @throws FileNotFoundException
     */
    private function appendResourceRoutesToRoutesFileFor($entity)
    {
        $routeContent = $this->finder->get($this->getModulesPath('Http/backendRoutes.php'));
        $content = $this->getContentForStub('route-resource.stub', $entity);
        $routeContent = str_replace('// append', $content, $routeContent);
        $this->finder->put($this->getModulesPath('Http/backendRoutes.php'), $routeContent);
    }

    /**
     * @param  string                                       $entity
     * @throws FileNotFoundException
     */
    private function appendPermissionsFor($entity)
    {
        $permissionsContent = $this->finder->get($this->getModulesPath('Config/permissions.php'));
        $content = $this->getContentForStub('permissions-append.stub', $entity);
        $permissionsContent = str_replace('// append', $content, $permissionsContent);
        $this->finder->put($this->getModulesPath('Config/permissions.php'), $permissionsContent);
    }

    /**
     * @param string $entity
     */
    private function appendSidebarLinksFor($entity)
    {
        $sidebarComposerContent = $this->finder->get($this->getModulesPath("Events/Handlers/Register{$this->name}Sidebar.php"));
        $content = $this->getContentForStub('append-sidebar-extender.stub', $entity);
        $sidebarComposerContent = str_replace('// append', $content, $sidebarComposerContent);

        $this->finder->put($this->getModulesPath("Events/Handlers/Register{$this->name}Sidebar.php"), $sidebarComposerContent);
    }

    /**
     * @param string $entity
     */
    private function appendBackendTranslations($entity)
    {
        $moduleProviderContent = $this->finder->get($this->getModulesPath("Providers/{$this->name}ServiceProvider.php"));

        $translations = $this->getContentForStub('translations-append.stub', $entity);
        $moduleProviderContent = str_replace('// append translations', $translations, $moduleProviderContent);
        $this->finder->put($this->getModulesPath("Providers/{$this->name}ServiceProvider.php"), $moduleProviderContent);
    }

    /**
     * Generate a filled sidebar view composer
     * Or an empty one of no entities
     * @param $entities
     */
    private function generateSidebarExtender($entities)
    {
        if (count($entities) > 0) {
            $firstModuleName = $entities[0];

            return $this->writeFile(
                $this->getModulesPath('Sidebar/SidebarExtender'),
                $this->getContentForStub('sidebar-extender.stub', $firstModuleName)
            );
        }

        return $this->writeFile(
            $this->getModulesPath('Sidebar/SidebarExtender'),
            $this->getContentForStub('empty-sidebar-view-composer.stub', 'abc')
        );
    }

    /**
     * Generate a sidebar event listener
     * @param $entities
     */
    public function generateSidebarListener($entities)
    {
        $name = "Register{$this->name}Sidebar";

        if (count($entities) > 0) {
            return $this->writeFile(
                $this->getModulesPath("Events/Handlers/$name"),
                $this->getContentForStub('sidebar-listener.stub', $name)
            );
        }

        return $this->writeFile(
            $this->getModulesPath("Events/Handlers/$name"),
            $this->getContentForStub('sidebar-listener-empty.stub', $name)
        );
    }

    /**
     * Get the current time with microseconds
     * @return string
     */
    private function getDateTimePrefix()
    {
        $t = microtime(true);
        $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
        $d = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));

        return $d->format("Y_m_d_Hisu_");
    }
}
