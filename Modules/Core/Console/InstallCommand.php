<?php namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\RoleRepository;
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
	 * @var RoleRepository
	 */
	private $role;

	/**
	 * Create a new command instance.
	 *
	 * @param UserRepository $user
	 * @param RoleRepository $role
	 * @return \Modules\Core\Console\InstallCommand
	 */
    public function __construct($user, $role)
    {
        parent::__construct();
		$this->user = $user;
		$this->role = $role;
	}

    /**
     * Execute the actions
     *
     * @return mixed
     */
    public function fire()
    {
		$this->info('Starting the installation process...');

		$this->runMigrations();

		$this->runSeeds();

		$this->createFirstUser();

		$this->blockMessage('Success!', 'Platform ready! You can now login with your username and password at /backend');
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
	 * Run the migrations
     */
	private function runMigrations()
	{
		$this->call('migrate', ['--package' => 'cartalyst/sentinel']);

		$this->info('Application migrated!');
	}

	/**
	 * Run the seeds
     */
	private function runSeeds()
	{
		$this->call('module:seed', ['module' => 'User']);

		$this->info('Application seeded!');
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
}
