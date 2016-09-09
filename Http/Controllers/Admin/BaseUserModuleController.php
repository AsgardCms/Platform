<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Permissions\PermissionManager;

abstract class BaseUserModuleController extends AdminBaseController
{
    /**
     * @var PermissionManager
     */
    protected $permissions;

    /**
     * @param Request $request
     * @return array
     */
    protected function mergeRequestWithPermissions(Request $request)
    {
        $permissions = $this->permissions->clean($request->permissions);

        return array_merge($request->all(), ['permissions' => $permissions]);
    }
}
