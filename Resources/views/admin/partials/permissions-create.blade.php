<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <?php foreach($permissions as $name => $value): ?>
                <h3>{{ $name }} Module</h3>
                <?php foreach($value as $subPermissionTitle => $permissionName): ?>
                    <h4>{{ ucfirst($subPermissionTitle) }}</h4>
                    <?php foreach($permissionName as $permissionAction): ?>
                        <div class="checkbox">
                            <label for="<?php echo "$subPermissionTitle.$permissionAction" ?>">
                                <input id="<?php echo "$subPermissionTitle.$permissionAction" ?>" name="permissions[<?php echo "$subPermissionTitle.$permissionAction" ?>]" type="checkbox" class="flat-blue" value="true" /> {{ ucfirst($permissionAction) }}
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
