<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\User\Permissions\PermissionManager;

class PermissionsController extends Controller
{
    /**
     * @var PermissionManager
     */
    private $permissionManager;

    public function __construct(PermissionManager $permissionManager)
    {
        $this->permissionManager = $permissionManager;
    }

    public function index()
    {
        return response()->json([
            'permissions' => $this->permissionManager->all(),
        ]);
    }
}
