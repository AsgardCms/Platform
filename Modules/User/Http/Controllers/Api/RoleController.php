<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Transformers\RoleTransformer;

class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $role;

    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

    public function index(Request $request)
    {
        return RoleTransformer::collection($this->role->serverPaginationFilteringFor($request));
    }
}
