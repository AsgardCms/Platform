<?php namespace Modules\User\Database\Seeders;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SentryGroupSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Create an Admin group
        Sentry::createGroup(array(
            'name'        => 'Admin',
            'permissions' => [
                'dashboard.index' => 1,
                /* Workbench */
                'workbench.index' => 1,
                'workbench.generate' => 1,
                'workbench.migrate' => 1,
                'workbench.install' => 1,
                'workbench.seed' => 1,
                'modules.index' => 1,
                'modules.store' => 1,
                'generate.generate' => 1,
                'install.install' => 1,
                'migrate.migrate' => 1,
                'seed.seed' => 1,
                /* Roles */
                'roles.index' => 1,
                'roles.create' => 1,
                'roles.store' => 1,
                'roles.edit' => 1,
                'roles.update' => 1,
                'roles.destroy' => 1,
                /* Users */
                'users.index' => 1,
                'users.create' => 1,
                'users.store' => 1,
                'users.edit' => 1,
                'users.update' => 1,
                'users.destroy' => 1,
                /* Menu */
                'menus.index' => 1,
                'menus.create' => 1,
                'menus.store' => 1,
                'menus.edit' => 1,
                'menus.update' => 1,
                'menus.destroy' => 1,
                'menuitem.index' => 1,
                'menuitem.create' => 1,
                'menuitem.store' => 1,
                'menuitem.edit' => 1,
                'menuitem.update' => 1,
                'menuitem.destroy' => 1,
                /* Media */
                'media.index' => 1,
                'media.create' => 1,
                'media.store' => 1,
                'media.edit' => 1,
                'media.update' => 1,
                'media.destroy' => 1,
            ],
        ));

        // Create an Users group
        Sentry::createGroup(array(
            'name'        => 'User',
            'permissions' => [
                'dashboard.index' => 1,
            ],
        ));

    }

}
