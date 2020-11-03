<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Permissions\PermissionManager;

class FullRoleTransformer extends JsonResource
{
    public function toArray($request)
    {
        $permissionsManager = app(PermissionManager::class);
        $permissions = $this->buildPermissionList($permissionsManager->all());

        $data = [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'created_at' => $this->resource->created_at,
            'permissions' => $permissions,
            'users' => UserTransformer::collection($this->whenLoaded('users')),
            'urls' => [],
        ];
        if ($this->resource->id) {
            $data['urls'] = [
                'delete_url' => route('api.user.role.destroy', $this->resource->id),
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
                    $list[strtolower($key) . '.' . $lastKey] = current_permission_value_for_roles($this->resource, $key, $lastKey);
                }
            }
        }

        return $list;
    }
}
