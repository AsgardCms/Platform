<?php

namespace Modules\User\Database\Seeders;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

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
            'core.sidebar.group' => true,
            /* Dashboard */
            'dashboard.index' => true,
            'dashboard.update' => true,
            'dashboard.reset' => true,
            /* Workbench */
            'workshop.sidebar.group' => true,
            'workshop.modules.index' => true,
            'workshop.modules.show' => true,
            'workshop.modules.update' => true,
            'workshop.modules.disable' => true,
            'workshop.modules.enable' => true,
            'workshop.modules.publish' => true,
            'workshop.themes.index' => true,
            'workshop.themes.show' => true,
            'workshop.themes.publish' => true,
            /* Roles */
            'user.roles.index' => true,
            'user.roles.create' => true,
            'user.roles.edit' => true,
            'user.roles.destroy' => true,
            /* Users */
            'user.users.index' => true,
            'user.users.create' => true,
            'user.users.edit' => true,
            'user.users.destroy' => true,
            /* API keys */
            'account.api-keys.index' => true,
            'account.api-keys.create' => true,
            'account.api-keys.destroy' => true,
            /* Menu */
            'menu.menus.index' => true,
            'menu.menus.create' => true,
            'menu.menus.edit' => true,
            'menu.menus.destroy' => true,
            'menu.menuitems.index' => true,
            'menu.menuitems.create' => true,
            'menu.menuitems.edit' => true,
            'menu.menuitems.destroy' => true,
            /* Media */
            'media.medias.index' => true,
            'media.medias.create' => true,
            'media.medias.edit' => true,
            'media.medias.destroy' => true,
            'media.folders.index' => true,
            'media.folders.create' => true,
            'media.folders.edit' => true,
            'media.folders.destroy' => true,
            /* Settings */
            'setting.settings.index' => true,
            'setting.settings.edit' => true,
            /* Page */
            'page.pages.index' => true,
            'page.pages.create' => true,
            'page.pages.edit' => true,
            'page.pages.destroy' => true,
            /* Translation */
            'translation.translations.index' => true,
            'translation.translations.edit' => true,
            'translation.translations.export' => true,
            'translation.translations.import' => true,
            /* Tags */
            'tag.tags.index' => true,
            'tag.tags.create' => true,
            'tag.tags.edit' => true,
            'tag.tags.destroy' => true,
        ];
        $group->save();
    }
}
