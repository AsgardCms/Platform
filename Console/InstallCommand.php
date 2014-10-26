<?php namespace Modules\Core\Console;

use Dotenv;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\UserRepository;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'platform:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Platform CMS';

    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @var Filesystem
     */
    private $finder;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $user
     * @param Filesystem $finder
     * @return \Modules\Core\Console\InstallCommand
     */
    public function __construct($user, Filesystem $finder)
    {
        parent::__construct();
        $this->user = $user;
        $this->finder = $finder;
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

		if ($this->confirm('Do you wish to init sentinel and create its first user? [yes|no]')) {
			$this->runUserCommands();
		}

        $this->runMigrations();

        $this->publishAssets();

        $this->blockMessage(
            'Success!',
            'Platform ready! You can now login with your username and password at /backend'
        );
    }

	/**
	 *
	 */
	private function runUserCommands()
	{
		$this->runSentinelMigrations();
		$this->runUserSeeds();
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
        $this->user->createWithRoles($userInfo, ['admin']);

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

        $this->info('Application migrated!');
    }

	private function runUserSeeds()
	{
		$this->call('module:seed', ['module' => 'User']);
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

}
