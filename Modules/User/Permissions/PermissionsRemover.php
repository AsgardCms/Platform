<?php

namespace Modules\User\Permissions;

use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;

class PermissionsRemover
{
    /**
     * @var string
     */
    private $moduleName;
    /**
     * @var RoleRepository
     */
    private $role;
    /**
     * @var UserRepository
     */
    private $user;

    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
        $this->role = app(RoleRepository::class);
        $this->user = app(UserRepository::class);
    }

    public function removeAll()
    {
        $permissions = $this->buildPermissionList();

        if (count($permissions) === 0) {
            return;
        }

        foreach ($this->role->all() as $role) {
            foreach ($permissions as $permission) {
                $role->removePermission($permission);
                $role->save();
            }
        }
        foreach ($this->user->all() as $user) {
            foreach ($permissions as $permission) {
                $user->removePermission($permission);
                $user->save();
            }
        }
    }

    /**
     * @return array
     */
    private function buildPermissionList()
    {
        $permissionsConfig = config('asgard.' . strtolower($this->moduleName) . '.permissions');
        $list = [];

        if ($permissionsConfig === null) {
            return $list;
        }

        foreach ($permissionsConfig as $mainKey => $subPermissions) {
            foreach ($subPermissions as $key => $description) {
                $list[] = $mainKey . '.' . $key;
            }
        }

        return $list;
    }
}
