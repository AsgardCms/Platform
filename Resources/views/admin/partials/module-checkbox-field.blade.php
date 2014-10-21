<?php $settingName = $module . '_' . $setting; ?>
<div class="checkbox">
    <label for="{{ $settingName . "[$lang]" }}">
        <input id="{{ $settingName . "[$lang]" }}"
                name="{{ $settingName . "[$lang]" }}"
                type="checkbox"
                class="flat-blue"
                {{ isset($settings[$settingName]) && (bool)$settings[$settingName]->translate($lang)->value == true ? 'checked' : '' }}
                value="1" />
        {{ $moduleInfo['description'] }}
    </label>
</div>
