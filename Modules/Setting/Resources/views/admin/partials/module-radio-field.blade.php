<?php $settingName = $module . '_' . $setting; ?>
<div class="checkbox">
    <?php foreach($moduleInfo['options'] as $value => $optionName): ?>
        <label for="{{ $optionName . "[$lang]" }}">
                <input id="{{ $optionName . "[$lang]" }}"
                        name="{{ $settingName . "[$lang]" }}"
                        type="radio"
                        class="flat-blue"
                        {{ isset($settings[$settingName]) && (bool)$settings[$settingName]->translate($lang)->value == $value ? 'checked' : '' }}
                        value="{{ $value }}" />
                {{ $optionName }}
        </label>
    <?php endforeach; ?>
</div>
