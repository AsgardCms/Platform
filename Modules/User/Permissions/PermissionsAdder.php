<?php

namespace Modules\User\Permissions;

use Modules\User\Repositories\RoleRepository;

final class PermissionsAdder
{
    /**
     * @var string
     */
    private $moduleName;
    /**
     * @var RoleRepository
     */
    private $role;

    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
        $this->role = app(RoleRepository::class);
    }

    public function addAll()
    {
        $permissions = $this->buildPermissionList();

        $role = $this->role->find(1);
        foreach ($permissions as $permission) {
            $role->addPermission($permission);
            $role->save();
        }
    }

    /**
     * @return array
     */
    private function buildPermissionList() : array
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
