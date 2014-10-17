<?php namespace Modules\User\Database\Seeders;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
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
            'workbench.index' => true,
            'workbench.generate' => true,
            'workbench.migrate' => true,
            'workbench.install' => true,
            'workbench.seed' => true,
            'modules.index' => true,
            'modules.store' => true,
            'roles.index' => true,
            'roles.create' => true,
            'roles.store' => true,
            'roles.edit' => true,
            'roles.update' => true,
            'roles.destroy' => true,
            'users.index' => true,
            'users.create' => true,
            'users.store' => true,
            'users.edit' => true,
            'users.update' => true,
            'users.destroy' => true,
        ];
        $group->save();

        $group = Sentinel::findRoleBySlug('user');
        $group->permissions = [
            'dashboard.index' => true
        ];
        $group->save();
    }

}
