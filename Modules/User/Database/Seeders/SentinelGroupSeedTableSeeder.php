<?php namespace Modules\User\Database\Seeders;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SentinelGroupSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $groups = Sentinel::getRoleRepository();

        // Create an Admin group
        $groups->createModel()->create(
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ]
        );

        // Create an Users group
        $groups->createModel()->create(
            [
                'name' => 'User',
                'slug' => 'user',
            ]
        );

        // Save the permissions
        $group = Sentinel::findRoleBySlug('admin');
        $group->permissions = [
            'dashboard.index' => true,
            /* Workbench */
            'workbench.index' => true,
            'workbench.generate' => true,
            'workbench.migrate' => true,
            'workbench.install' => true,
            'workbench.seed' => true,
            'modules.index' => true,
            'modules.store' => true,
            'generate.generate' => true,
            'install.install' => true,
            'migrate.migrate' => true,
            'seed.seed' => true,
            /* Roles */
            'roles.index' => true,
            'roles.create' => true,
            'roles.store' => true,
            'roles.edit' => true,
            'roles.update' => true,
            'roles.destroy' => true,
            /* Users */
            'users.index' => true,
            'users.create' => true,
            'users.store' => true,
            'users.edit' => true,
            'users.update' => true,
            'users.destroy' => true,
            /* Menu */
            'menus.index' => true,
            'menus.create' => true,
            'menus.store' => true,
            'menus.edit' => true,
            'menus.update' => true,
            'menus.destroy' => true,
            'menuitem.index' => true,
            'menuitem.create' => true,
            'menuitem.store' => true,
            'menuitem.edit' => true,
            'menuitem.update' => true,
            'menuitem.destroy' => true,
            /* Media */
            'media.index' => true,
            'media.create' => true,
            'media.store' => true,
            'media.edit' => true,
            'media.update' => true,
            'media.destroy' => true,
            /* Settings */
            'settings.index' => true,
        ];
        $group->save();

        $group = Sentinel::findRoleBySlug('user');
        $group->permissions = [
            'dashboard.index' => true
        ];
        $group->save();
    }

}
