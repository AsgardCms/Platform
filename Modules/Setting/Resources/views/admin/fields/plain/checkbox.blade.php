<?php $settingName = strtolower($currentModule) . '::' . $setting; ?>
<div class="checkbox">
    <label for="{{ $settingName }}">
        <input id="{{ $settingName }}"
                name="{{ $settingName }}"
                type="checkbox"
                class="flat-blue"
                {{ isset($dbSettings[$settingName]) && (bool)$dbSettings[$settingName]->value == true ? 'checked' : '' }}
                value="1" />
        {{ $moduleInfo['description'] }}
    </label>
</div>
