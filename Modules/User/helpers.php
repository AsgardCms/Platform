<?php

use Illuminate\Support\Arr;

if (function_exists('current_permission_value') === false) {
    function current_permission_value($model, $permissionTitle, $permissionAction)
    {
        $value = Arr::get($model->permissions, "$permissionTitle.$permissionAction");
        if ($value === true) {
            return 1;
        }
        if ($value === false) {
            return -1;
        }

        return 0;
    }
}

if (function_exists('current_permission_value_for_roles') === false) {
    function current_permission_value_for_roles($model, $permissionTitle, $permissionAction)
    {
        $value = Arr::get($model->permissions, "$permissionTitle.$permissionAction");
        if ($value === true) {
            return 1;
        }

        return -1;
    }
}
