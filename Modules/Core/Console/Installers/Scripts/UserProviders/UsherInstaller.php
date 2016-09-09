<?php

namespace Modules\Core\Console\Installers\Scripts\UserProviders;

use Modules\Core\Console\Installers\SetupScript;

class UsherInstaller extends ProviderInstaller implements SetupScript
{
    /**
     * @var string
     */
    protected $driver = 'Usher';

    /**
     * Check if the user driver is correctly registered.
     * @return bool
     */
    public function checkIsInstalled()
    {
        return class_exists('Maatwebsite\Usher\UsherServiceProvider')
            && class_exists('Mitch\LaravelDoctrine\LaravelDoctrineServiceProvider');
    }

    /**
     * Not called
     * @return mixed
     */
    public function composer()
    {
        $this->application->register('Maatwebsite\Usher\UsherServiceProvider');
    }

    /**
     * @return mixed
     */
    public function publish()
    {
        if ($this->command->option('verbose')) {
            $this->command->call('vendor:publish', ['--provider' => 'Maatwebsite\Usher\UsherServiceProvider']);

            return $this->command->call('vendor:publish', ['--provider' => 'Mitch\LaravelDoctrine\LaravelDoctrineServiceProvider']);
        }

        $this->command->callSilent('vendor:publish', ['--provider' => 'Maatwebsite\Usher\UsherServiceProvider']);

        return $this->command->callSilent('vendor:publish', ['--provider' => 'Mitch\LaravelDoctrine\LaravelDoctrineServiceProvider']);
    }

    /**
     * @return mixed
     */
    public function migrate()
    {
        if ($this->command->option('verbose')) {
            return $this->command->call('doctrine:schema:update');
        }

        return $this->command->callSilent('doctrine:schema:update');
    }

    /**
     * @return mixed
     */
    public function configure()
    {
        $path = base_path("config/usher.php");

        $config = $this->finder->get($path);

        $config = str_replace('Maatwebsite\Usher\Domain\Users\UsherUser', "Modules\\User\\Entities\\{$this->driver}\\User", $config);
        $config = str_replace('Maatwebsite\Usher\Domain\Roles\UsherRole', "Modules\\User\\Entities\\{$this->driver}\\Role", $config);

        $this->finder->put($path, $config);

        // Doctrine config
        $path = base_path("config/doctrine.php");

        $config = $this->finder->get($path);

        $config = str_replace('// Paths to entities here...', 'base_path(\'Modules/User/Entities/Usher\')', $config);

        $this->finder->put($path, $config);

        $this->bindUserRepositoryOnTheFly('Usher');

        $this->application->register('Maatwebsite\Usher\UsherServiceProvider');
        $this->application->register('Mitch\LaravelDoctrine\LaravelDoctrineServiceProvider');
    }

    /**
     * @return mixed
     */
    public function seed()
    {
        if ($this->command->option('verbose')) {
            return $this->command->call('db:seed', ['--class' => 'Modules\User\Database\Seeders\UsherTableSeeder']);
        }

        return $this->command->callSilent('db:seed', ['--class' => 'Modules\User\Database\Seeders\UsherTableSeeder']);
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
