<?php

namespace Modules\User\Permissions;

use Nwidart\Modules\Module;

class PermissionManager
{
    /**
     * @var Module
     */
    private $module;

    /**
     */
    public function __construct()
    {
        $this->module = app('modules');
    }

    /**
     * Get the permissions from all the enabled modules
     * @return array
     */
    public function all()
    {
        $permissions = [];
        foreach ($this->module->enabled() as $enabledModule) {
            $configuration = config(strtolower('asgard.' . $enabledModule->getName()) . '.permissions');
            if ($configuration) {
                $permissions[$enabledModule->getName()] = $configuration;
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
            if ($this->getState($checkedPermission) !== null) {
                $cleanedPermissions[$permissionName] = $this->getState($checkedPermission);
            }
        }

        return $cleanedPermissions;
    }

    /**
     * @param $checkedPermission
     * @return bool
     */
    protected function getState($checkedPermission)
    {
        if ($checkedPermission === '1') {
            return true;
        }

        if ($checkedPermission === '-1') {
            return false;
        }

        return null;
    }

    /**
     * Are all of the permissions passed of false value?
     * @param array $permissions    Permissions array
     * @return bool
     */
    public function permissionsAreAllFalse(array $permissions)
    {
        $uniquePermissions = array_unique($permissions);

        if (count($uniquePermissions) > 1) {
            return false;
        }

        $uniquePermission = reset($uniquePermissions);

        $cleanedPermission = $this->getState($uniquePermission);

        return $cleanedPermission === false;
    }
}
