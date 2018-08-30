<?php

namespace Modules\Workshop\Tests;

use Modules\Workshop\Scaffold\Module\Exception\ModuleExistsException;
use Modules\Workshop\Scaffold\Module\ModuleScaffold;

class ModuleScaffoldTest extends BaseTestCase
{
    /**
     * @var ModuleScaffold
     */
    protected $scaffold;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;
    /**
     * @var string Path to the module under test
     */
    protected $testModulePath;
    /**
     * @var string The name of the module under test
     */
    protected $testModuleName;
    /**
     * @var string The sanitized name of the module under test
     */
    protected $testModuleSanitizedName;

    public function setUp()
    {
        $this->testModuleName = 'Testing_The-TestModule';
        $this->testModuleSanitizedName = 'TestingTheTestModule';
        $this->testModulePath = __DIR__ . "/../Modules/{$this->testModuleSanitizedName}";
        $this->cleanUp();

        parent::setUp();

        $this->finder = $this->app['files'];
        $this->scaffold = $this->app['asgard.module.scaffold'];
    }

    /**
     * Recursively remove the given directory
     * @param string $dir
     * @return bool
     */
    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    /**
     *
     */
    private function cleanUp()
    {
        if (file_exists(__DIR__ . '/../Modules/')) {
            self::delTree(__DIR__ . '/../Modules/');
        }
        mkdir(__DIR__ . '/../Modules/', 0777);
    }

    /**
     * Scaffold a test module using eloquent
     * @param array $entities
     * @param array $valueObjects
     */
    private function scaffoldModuleWithEloquent(array $entities = ['Post'], array $valueObjects = [])
    {
        $this->scaffoldModule('Eloquent', $entities, $valueObjects);
    }

    /**
     * Scaffold a test module using doctrine
     * @param array $entities
     * @param array $valueObjects
     */
    private function scaffoldModuleWithDoctrine(array $entities = ['Post'], array $valueObjects = [])
    {
        $this->scaffoldModule('Doctrine', $entities, $valueObjects);
    }

    /**
     * @param $type
     * @param $entities
     * @param $valueObjects
     * @throws ModuleExistsException
     */
    private function scaffoldModule($type, $entities, $valueObjects)
    {
        $this->scaffold
            ->vendor('asgardcms')
            ->name($this->testModuleName)
            ->setEntityType($type)
            ->withEntities($entities)
            ->withValueObjects($valueObjects)
            ->scaffold();
    }

    public function tearDown()
    {
        if (file_exists(__DIR__ . '/../Modules/')) {
            self::delTree(__DIR__ . '/../Modules/');
        }
    }

    /** @test */
    public function it_should_generate_module_folder()
    {
        // Run
        $this->scaffoldModuleWithEloquent();

        // Assert
        $this->assertTrue($this->finder->isDirectory($this->testModulePath));

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_eloquent_entities_with_translations()
    {
        // Run
        $this->scaffoldModuleWithEloquent(['Category', 'Post']);

        // Assert
        $entities = $this->finder->allFiles($this->testModulePath . '/Entities');
        $this->assertCount(4, $entities);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_doctrine_entities_with_translations()
    {
        // Run
        $this->scaffoldModuleWithDoctrine(['Category', 'Post']);

        // Assert
        $entities = $this->finder->allFiles($this->testModulePath . '/Entities');
        $this->assertCount(4, $entities);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_translation_entities()
    {
        // Run
        $this->scaffoldModuleWithEloquent();

        // Assert
        $entity = $this->finder->isFile($this->testModulePath . '/Entities/Post.php');
        $translationEntity = $this->finder->isFile($this->testModulePath . '/Entities/PostTranslation.php');
        $this->assertTrue($entity);
        $this->assertTrue($translationEntity);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_cache_decorators()
    {
        // Run
        $this->scaffoldModuleWithEloquent(['Category', 'Post']);

        // Assert
        $categoryDecorator = $this->finder->isFile($this->testModulePath . '/Repositories/Cache/CacheCategoryDecorator.php');
        $postDecorator = $this->finder->isFile($this->testModulePath . '/Repositories/Cache/CachePostDecorator.php');
        $this->assertTrue($categoryDecorator);
        $this->assertTrue($postDecorator);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_repository_interfaces()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $interface = $this->finder->isFile($this->testModulePath . '/Repositories/PostRepository.php');
        $interface2 = $this->finder->isFile($this->testModulePath . '/Repositories/CategoryRepository.php');

        $this->assertTrue($interface);
        $this->assertTrue($interface2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_eloquent_repositories()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $repository = $this->finder->isFile($this->testModulePath . '/Repositories/Eloquent/EloquentPostRepository.php');
        $repository2 = $this->finder->isFile($this->testModulePath . '/Repositories/Eloquent/EloquentCategoryRepository.php');

        $this->assertTrue($repository);
        $this->assertTrue($repository2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_doctrine_repositories()
    {
        $this->scaffoldModuleWithDoctrine(['Post', 'Category']);

        $repository = $this->finder->isFile($this->testModulePath . '/Repositories/Doctrine/DoctrinePostRepository.php');
        $repository2 = $this->finder->isFile($this->testModulePath . '/Repositories/Doctrine/DoctrineCategoryRepository.php');

        $this->assertTrue($repository);
        $this->assertTrue($repository2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_views()
    {
        $this->scaffoldModuleWithEloquent();

        $view1 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/index.blade.php');
        $view2 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/create.blade.php');
        $view3 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/edit.blade.php');
        $view4 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/partials/create-fields.blade.php');
        $view5 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/partials/edit-fields.blade.php');

        $this->assertTrue($view1);
        $this->assertTrue($view2);
        $this->assertTrue($view3);
        $this->assertTrue($view4);
        $this->assertTrue($view5);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_language_files()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $languageFile1 = $this->finder->isFile($this->testModulePath . '/Resources/lang/en/posts.php');
        $languageFile2 = $this->finder->isFile($this->testModulePath . '/Resources/lang/en/categories.php');

        $this->assertTrue($languageFile1);
        $this->assertTrue($languageFile2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_service_providers()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Providers/RouteServiceProvider.php');
        $file2 = $this->finder->isFile($this->testModulePath . "/Providers/{$this->testModuleSanitizedName}ServiceProvider.php");

        $this->assertTrue($file1);
        $this->assertTrue($file2);

        $this->cleanUp();
    }

    /** @test */
    public function it_generates_service_provider_with_content()
    {
        $this->scaffoldModuleWithEloquent();

        $file = $this->finder->get($this->testModulePath . "/Providers/{$this->testModuleSanitizedName}ServiceProvider.php");

        $sidebarEventListenerName = "Register{$this->testModuleSanitizedName}Sidebar";
        $this->assertTrue(str_contains(
            $file,
            '$this->loadMigrationsFrom(__DIR__ . \'/../Database/Migrations\');'
        ), 'Migrations arent loaded');

        $this->assertTrue(str_contains(
            $file,
            '$this->app[\'events\']->listen(BuildingSidebar::class, ' . $sidebarEventListenerName . '::class);'
        ), 'Sidebar event handler was not present');

        $this->assertTrue(str_contains(
            $file,
            '$this->app[\'events\']->listen(LoadingBackendTranslations::class,'
        ), 'Translations registering was not present');

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_controllers()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $file1 = $this->finder->isFile($this->testModulePath . '/Http/Controllers/Admin/PostController.php');
        $file2 = $this->finder->isFile($this->testModulePath . '/Http/Controllers/Admin/CategoryController.php');

        $this->assertTrue($file1);
        $this->assertTrue($file2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_requests()
    {
        $this->scaffoldModuleWithEloquent(['Post']);

        $file1 = $this->finder->isFile($this->testModulePath . '/Http/Requests/CreatePostRequest.php');
        $file2 = $this->finder->isFile($this->testModulePath . '/Http/Requests/UpdatePostRequest.php');

        $this->assertTrue($file1);
        $this->assertTrue($file2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_routes_file()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Http/backendRoutes.php');

        $this->assertTrue($file1);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_sidebar_event_handler()
    {
        $this->scaffoldModuleWithEloquent();

        $file = $this->finder->isFile($this->testModulePath . "/Events/Handlers/Register{$this->testModuleSanitizedName}Sidebar.php");

        $this->assertTrue($file);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_filled_sidebar_event_handler()
    {
        $this->scaffoldModuleWithEloquent();

        $file = $this->finder->get($this->testModulePath . "/Events/Handlers/Register{$this->testModuleSanitizedName}Sidebar.php");

        $this->assertTrue(str_contains($file, '$menu->group'));
        $this->assertTrue(str_contains($file, "class Register{$this->testModuleSanitizedName}Sidebar"));

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_empty_sidebar_event_handler_if_no_entities()
    {
        $this->scaffoldModule('Eloquent', [], []);

        $file = $this->finder->get($this->testModulePath . "/Events/Handlers/Register{$this->testModuleSanitizedName}Sidebar.php");

        $this->assertFalse(str_contains($file, '$menu->group'));
        $this->assertTrue(str_contains($file, 'return $menu'));

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_permissions_config_file()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Config/permissions.php');

        $this->assertTrue($file1);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_value_objects()
    {
        $this->scaffoldModuleWithEloquent(['Post'], ['Price', 'TimeRange']);

        $file1 = $this->finder->isFile($this->testModulePath . '/ValueObjects/Price.php');
        $file2 = $this->finder->isFile($this->testModulePath . '/ValueObjects/TimeRange.php');

        $this->assertTrue($file1);
        $this->assertTrue($file2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_throw_exception_if_module_exists()
    {
        $this->expectException(ModuleExistsException::class);

        $this->scaffoldModuleWithEloquent();
        $this->scaffoldModuleWithEloquent();

        $this->assertEquals(ModuleExistsException::class, $this->getExpectedException());
    }

    /** @test */
    public function it_should_generate_migrations_for_eloquent()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $migrations = $this->finder->allFiles($this->testModulePath . '/Database/Migrations');

        $this->assertCount(4, $migrations);
    }

    /** @test */
    public function it_should_generate_composer_json_file()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->finder->isFile($this->testModulePath . '/composer.json');

        $this->assertTrue($composerJson);
    }

    /** @test */
    public function it_should_use_correct_vendor_name_in_composer_json()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->getComposerFile();

        $this->assertEquals('asgardcms/testingthetestmodule', $composerJson->name);
    }

    /** @test */
    public function it_should_change_the_type_to_asgard_module()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->getComposerFile();

        $this->assertEquals('asgard-module', $composerJson->type);
    }

    /** @test */
    public function it_should_add_composers_installers_to_require_key()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->getComposerFile();
        $key = 'composer/installers';

        $this->assertTrue(isset($composerJson->require->$key));
    }

    /** @test */
    public function it_should_add_require_dev_to_composer_json()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->getComposerFile();
        $key = 'require-dev';

        $this->assertTrue(isset($composerJson->$key));
    }

    /** @test */
    public function it_should_add_autoload_dev_to_composer_json()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->getComposerFile();
        $key = 'autoload-dev';

        $this->assertTrue(isset($composerJson->$key));
    }

    /** @test */
    public function it_adds_minimun_stability_key_in_composer_file()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->getComposerFile();
        $key = 'minimum-stability';

        $this->assertTrue(isset($composerJson->$key));
        $this->assertEquals('stable', $composerJson->$key);
    }

    /** @test */
    public function it_adds_prefer_stable_key_in_composer_file()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->getComposerFile();
        $key = 'prefer-stable';

        $this->assertTrue(isset($composerJson->$key));
        $this->assertTrue($composerJson->$key);
    }

    /** @test */
    public function it_sets_module_order_to_1_in_module_json_file()
    {
        $this->scaffoldModuleWithEloquent();

        $moduleJson = $this->getModuleFile();

        $this->assertEquals(1, $moduleJson->order);
    }

    /** @test */
    public function it_should_use_core_messages()
    {
        $this->scaffoldModuleWithEloquent(['Post']);

        $controllerContents = $this->getAdminControllerFile('Post');
        $lowercaseModuleName = strtolower($this->testModuleSanitizedName);

        $matches = [
            "withSuccess(trans('core::core.messages.resource created', ['name' => trans('{$lowercaseModuleName}::posts.title.posts')]));",
            "withSuccess(trans('core::core.messages.resource updated', ['name' => trans('{$lowercaseModuleName}::posts.title.posts')]));",
            "withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('{$lowercaseModuleName}::posts.title.posts')]));",
        ];

        foreach ($matches as $match) {
            $this->assertContains($match, $controllerContents);
        }

        $this->cleanUp();
    }

    /** @test */
    public function it_can_overwrite_stub_files_with_custom_ones()
    {
        config()->set('asgard.workshop.config.custom-stubs-folder', __DIR__ . '/stubs');

        $this->scaffoldModuleWithEloquent();

        $path = $this->testModulePath . '/Http/backendRoutes.php';
        $file = $this->finder->get($path);
        $this->assertTrue($this->finder->isFile($path));
        $this->assertContains('overwritten by custom config', $file);

        $this->cleanUp();
    }

    /** @test */
    public function it_add_default_version_on_module_json_file()
    {
        $this->scaffoldModuleWithEloquent();

        $moduleFile = $this->getModuleFile();

        $this->assertEquals('1.0.0', $moduleFile->version);
    }

    /**
     * Get the contents of composer.json file
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getComposerFile()
    {
        $composerJson = $this->finder->get($this->testModulePath . '/composer.json');

        return json_decode($composerJson);
    }

    /**
     * Get the contents of module.json file
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getModuleFile()
    {
        $moduleFile = $this->finder->get($this->testModulePath . '/module.json');

        return json_decode($moduleFile);
    }

    /**
     * Get a Controller
     * @param string $controllerName
     * @return mixed
     */
    private function getAdminControllerFile($controllerName = 'Post')
    {
        $controllerFile = $this->finder->get("{$this->testModulePath}/Http/Controllers/Admin/{$controllerName}Controller.php");

        return $controllerFile;
    }
}
