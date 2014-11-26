<?php namespace Modules\Core\Console;

use Dotenv;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Services\Composer;
use Modules\User\Repositories\UserRepository;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'asgard:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Asgard CMS';

    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @var Filesystem
     */
    private $finder;
    /**
     * @var Application
     */
    private $app;
    /**
     * @var Composer
     */
    private $composer;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $user
     * @param Filesystem $finder
     * @param Application $app
     * @param Composer $composer
     */
    public function __construct($user, Filesystem $finder, Application $app, Composer $composer)
    {
        parent::__construct();
        $this->user = $user;
        $this->finder = $finder;
        $this->app = $app;
        $this->composer = $composer;
    }

    /**
     * Execute the actions
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Starting the installation process...');

        $this->configureDatabase();

        $userDriver = $this->choice('Which user driver do you wish to use?', ['Sentinel', 'Sentry'], 'Sentry');
        $userDriver = "run{$userDriver}UserCommands";
        $this->$userDriver();

        $this->runMigrations();

        $this->publishAssets();

        $this->publishConfigurations();

        $this->blockMessage(
            'Success!',
            'Platform ready! You can now login with your username and password at /backend'
        );
    }

	/**
	 *
	 */
	private function runSentinelUserCommands()
	{
        $this->info('Requiring Sentinel package, this may take some time...');
        $this->handleComposerForSentinel();

        $this->info('Running Sentinel migrations...');
		$this->runSentinelMigrations();

        $this->call('db:seed', ['--class' => 'Modules\User\Database\Seeders\SentinelGroupSeedTableSeeder']);

        $this->replaceUserRepositoryBindings('Sentinel');
        $this->bindUserRepositoryOnTheFly('Sentinel');

        $this->call('publish:config', ['package' => 'cartalyst/sentinel']);
        $this->replaceCartalystUserModelConfiguration('Cartalyst\Sentinel\Users\EloquentUser', 'Sentinel');

        $this->createFirstUser();

		$this->info('User commands done.');
	}

    /**
     * Create the first user that'll have admin access
     */
    private function createFirstUser()
    {
        $this->line('Creating an Admin user account...');

        $firstname = $this->ask('Enter your first name');
        $lastname = $this->ask('Enter your last name');
        $email = $this->ask('Enter your email address');
        $password = $this->secret('Enter a password');

        $userInfo = [
            'first_name' => $firstname,
            'last_name' => $lastname,
            'email' => $email,
            'password' => Hash::make($password),
        ];
        $user = app('Modules\User\Repositories\UserRepository');
        $user->createWithRoles($userInfo, [1]);

        $this->info('Admin account created!');
    }

	/**
	 * Run migrations specific to Sentinel
     */
	private function runSentinelMigrations()
	{
		$this->call('migrate', ['--package' => 'cartalyst/sentinel']);
	}

    /**
     * Run the migrations
     */
    private function runMigrations()
    {
        $this->call('module:migrate', ['module' => 'Setting']);
        $this->call('module:migrate', ['module' => 'Menu']);
        $this->call('module:migrate', ['module' => 'Media']);

        $this->info('Application migrated!');
    }

    private function publishConfigurations()
    {
        $this->call('publish:config', ['package' => 'dimsav/laravel-translatable']);
        $this->call('publish:config', ['package' => 'mcamara/laravel-localization']);
        $this->call('publish:config', ['package' => 'pingpong/modules']);
    }

    /**
     * Symfony style block messages
     * @param $title
     * @param $message
     * @param string $style
     */
    protected function blockMessage($title, $message, $style = 'info')
    {
        $formatter = $this->getHelperSet()->get('formatter');
        $errorMessages = [$title, $message];
        $formattedBlock = $formatter->formatBlock($errorMessages, $style, true);
        $this->line($formattedBlock);
    }

    /**
     * Publish the CMS assets
     */
    private function publishAssets()
    {
        $this->call('module:publish', ['module' => 'Core']);
        $this->call('module:publish', ['module' => 'Media']);
        $this->call('module:publish', ['module' => 'Menu']);
    }

    /**
     * Configuring the database information
     */
    private function configureDatabase()
    {
        // Ask for credentials
        $databaseName = $this->ask('Enter your database name');
        $databaseUsername = $this->ask('Enter your database username');
        $databasePassword = $this->secret('Enter your database password');

        $this->setLaravelConfiguration($databaseName, $databaseUsername, $databasePassword);
        $this->configureEnvironmentFile($databaseName, $databaseUsername, $databasePassword);
    }

    /**
     * Writing the environment file
     * @param $databaseName
     * @param $databaseUsername
     * @param $databasePassword
     */
    private function configureEnvironmentFile($databaseName, $databaseUsername, $databasePassword)
    {
        Dotenv::makeMutable();

        $environmentFile = $this->finder->get('.env.example');

        $search = [
            "DB_USERNAME=homestead",
            "DB_PASSWORD=homestead"
        ];

        $replace = [
            "DB_USERNAME=$databaseUsername",
            "DB_PASSWORD=$databasePassword" . PHP_EOL
        ];
        $newEnvironmentFile = str_replace($search, $replace, $environmentFile);
        $newEnvironmentFile .= "DB_NAME=$databaseName";

        // Write the new environment file
        $this->finder->put('.env', $newEnvironmentFile);
        // Delete the old environment file
        $this->finder->delete('env.example');

        $this->info('Environment file written');

        Dotenv::makeImmutable();
    }

    /**
     * Set DB credentials to laravel config
     * @param $databaseName
     * @param $databaseUsername
     * @param $databasePassword
     */
    private function setLaravelConfiguration($databaseName, $databaseUsername, $databasePassword)
    {
        $this->laravel['config']['database.connections.mysql.database'] = $databaseName;
        $this->laravel['config']['database.connections.mysql.username'] = $databaseUsername;
        $this->laravel['config']['database.connections.mysql.password'] = $databasePassword;
    }

    /**
     * Find and replace the correct repository bindings with the given driver
     * @param string $driver
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function replaceUserRepositoryBindings($driver)
    {
        $path = 'Modules/User/Providers/UserServiceProvider.php';
        $userServiceProvider = $this->finder->get($path);
        $userServiceProvider = str_replace('Sentry', $driver, $userServiceProvider);
        $this->finder->put($path, $userServiceProvider);
    }

    /**
     * Set the correct repository binding on the fly for the current request
     * @param $driver
     */
    private function bindUserRepositoryOnTheFly($driver)
    {
        $this->app->bind(
            'Modules\User\Repositories\UserRepository',
            "Modules\\User\\Repositories\\$driver\\{$driver}UserRepository"
        );
        $this->app->bind(
            'Modules\User\Repositories\RoleRepository',
            "Modules\\User\\Repositories\\$driver\\{$driver}RoleRepository"
        );
        $this->app->bind(
            'Modules\Core\Contracts\Authentication',
            "Modules\\User\\Repositories\\$driver\\{$driver}Authentication"
        );
    }

    /**
     * Replaced the model in the cartalyst configuration file
     * @param string $search
     * @param string $Driver
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function replaceCartalystUserModelConfiguration($search, $Driver)
    {
        $driver = strtolower($Driver);
        $path = "config/packages/cartalyst/{$driver}/config.php";

        $config = $this->finder->get($path);
        $config = str_replace($search, "Modules\\User\\Entities\\{$Driver}User", $config);
        $this->finder->put($path, $config);
    }

    /**
     * Install sentinel and remove sentry
     * Set the required Service Providers and Aliases in config/app.php
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function handleComposerForSentinel()
    {
        $this->composer->enableOutput($this);
        $this->composer->install('cartalyst/sentinel:~1.0');

        // Search and replace SP and Alias in config/app.php
        $appConfig = $this->finder->get('config/app.php');
        $appConfig = str_replace(
            [
                "#'Cartalyst\\Sentinel\\Laravel\\SentinelServiceProvider',",
                "'Cartalyst\\Sentry\\SentryServiceProvider',",
                "#'Activation' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Activation',",
                "#'Reminder' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Reminder',",
                "#'Sentinel' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Sentinel',",
                "'Sentry' => 'Cartalyst\\Sentry\\Facades\\Laravel\\Sentry',"
            ],
            [
                "'Cartalyst\\Sentinel\\Laravel\\SentinelServiceProvider',",
                "#'Cartalyst\\Sentry\\SentryServiceProvider',",
                "'Activation' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Activation',",
                "'Reminder' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Reminder',",
                "'Sentinel' => 'Cartalyst\\Sentinel\\Laravel\\Facades\\Sentinel',",
                "#'Sentry' => 'Cartalyst\\Sentry\\Facades\\Laravel\\Sentry',"
            ],
            $appConfig
        );
        $this->finder->put('config/app.php', $appConfig);

        $this->composer->remove('cartalyst/sentry');
    }

}
