<?php $settingName = strtolower($currentModule) . '::' . $setting; ?>
<div class="checkbox">
    <label for="{{ $settingName . "[$lang]" }}">
        <input id="{{ $settingName . "[$lang]" }}"
                name="{{ $settingName . "[$lang]" }}"
                type="checkbox"
                class="flat-blue"
                {{ isset($dbSettings[$settingName]) && (bool)$dbSettings[$settingName]->translate($lang)->value == true ? 'checked' : '' }}
                value="1" />
        {{ $moduleInfo['description'] }}
    </label>
</div>
