<?php

namespace Modules\Core\Console\Installers\Scripts\UserProviders;

use Modules\Core\Console\Installers\SetupScript;

class SentryInstaller extends ProviderInstaller implements SetupScript
{
    /**
     * @var string
     */
    protected $driver = 'Sentry';

    /**
     * Check if the user driver is correctly registered.
     * @return bool
     */
    public function checkIsInstalled()
    {
        return class_exists('Cartalyst\Sentry\SentryServiceProvider');
    }

    /**
     * Not called
     * @return mixed
     */
    public function composer()
    {
        $this->application->register('Cartalyst\Sentry\SentryServiceProvider');
    }

    /**
     * @return mixed
     */
    public function publish()
    {
        if ($this->command->option('verbose')) {
            return $this->command->call('vendor:publish', ['--provider' => 'Cartalyst\Sentry\SentryServiceProvider']);
        }

        return $this->command->callSilent('vendor:publish', ['--provider' => 'Cartalyst\Sentry\SentryServiceProvider']);
    }

    /**
     * @return mixed
     */
    public function migrate()
    {
        if ($this->command->option('verbose')) {
            return $this->command->call('migrate');
        }

        return $this->command->callSilent('migrate');
    }

    /**
     * @return mixed
     */
    public function configure()
    {
        $this->replaceCartalystUserModelConfiguration(
            'Cartalyst\Sentry\Users\Eloquent\User',
            $this->driver
        );

        $this->bindUserRepositoryOnTheFly('Sentry');
    }

    /**
     * @return mixed
     */
    public function seed()
    {
        if ($this->command->option('verbose')) {
            return $this->command->call('db:seed', ['--class' => 'Modules\User\Database\Seeders\SentryGroupSeedTableSeeder']);
        }

        return $this->command->callSilent('db:seed', ['--class' => 'Modules\User\Database\Seeders\SentryGroupSeedTableSeeder']);
    }

    /**
     * @param $password
     * @return mixed
     */
    public function getHashedPassword($password)
    {
        return $password;
    }
}
