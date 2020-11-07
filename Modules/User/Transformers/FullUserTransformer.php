<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Permissions\PermissionManager;

class FullUserTransformer extends JsonResource
{
    public function toArray($request)
    {
        $permissionsManager = app(PermissionManager::class);
        $permissions = $this->buildPermissionList($permissionsManager->all());

        $data = [
            'id' => $this->resource->id,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'email' => $this->resource->email,
            'is_activated' => $this->resource->isActivated(),
            'last_login' => $this->resource->last_login,
            'created_at' => $this->resource->created_at,
            'permissions' => $permissions,
            'roles' => $this->resource->roles->pluck('id'),
            'urls' => [],
        ];
        if ($this->resource->id) {
            $data['urls'] = [
                'delete_url' => route('api.user.user.destroy', $this->resource->id),
            ];
        }

        return $data;
    }

    private function buildPermissionList(array $permissionsConfig): array
    {
        $list = [];

        if ($permissionsConfig === null) {
            return $list;
        }

        foreach ($permissionsConfig as $mainKey => $subPermissions) {
            foreach ($subPermissions as $key => $permissionGroup) {
                foreach ($permissionGroup as $lastKey => $description) {
                    $list[strtolower($key) . '.' . $lastKey] = current_permission_value($this->resource, $key, $lastKey);
                }
            }
        }

        return $list;
    }
}
