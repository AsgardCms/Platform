<?php namespace Modules\Core\Permissions;

use Illuminate\Support\Facades\Config;
use Pingpong\Modules\Module;

class PermissionManager
{
    /**
     * @var Module
     */
    private $module;

    /**
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Get the permissions from all the enabled modules
     * @return array
     */
    public function all()
    {
        $permissions = [];
        foreach ($this->module->enabled() as $enabledModule) {
            $configuration = Config::get(strtolower($enabledModule) . '::permissions');
            if ($configuration) {
                $permissions[$enabledModule] = $configuration;
            }
        }
        return $permissions;
    }

    /**
     * Return a correctly type casted permissions array
     * @param $permissions
     * @return array
     */
    public function clean($permissions)
    {
        if (!$permissions) {
            return [];
        }
        $cleanedPermissions = [];
        foreach ($permissions as $permissionName => $checkedPermission) {
            $cleanedPermissions[$permissionName] = (bool)$checkedPermission;
        }
        return $cleanedPermissions;
    }
}
