<?php

namespace Modules\User\Entities;

interface UserInterface
{
    /**
     * Checks if the user is in the given role.
     * @param  mixed $role
     * @return bool
     */
    public function inRole($role);

    /**
     * Checks if a user belongs to the given Role ID
     * @param  int $roleId
     * @return bool
     */
    public function hasRoleId($roleId);

    /**
     * Checks if a user belongs to the given Role Slug
     * @param  string $slug
     * @return bool
     */
    public function hasRoleSlug($slug);

    /**
     * Checks if a user belongs to the given Role Name
     * @param  string $name
     * @return bool
     */
    public function hasRoleName($name);

    /**
     * Check if the current user is activated
     * @return bool
     */
    public function isActivated();

    /**
     * Get the first available api key
     * @return string
     */
    public function getFirstApiKey();

    /**
     * Check if the user has access to the given permission name
     * @param string $permission
     * @return boolean
     */
    public function hasAccess($permission);
}
