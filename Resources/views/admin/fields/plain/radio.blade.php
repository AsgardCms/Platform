<?php $settingName = strtolower($currentModule) . '::' . $setting; ?>
<div class="checkbox">
    <?php foreach($moduleInfo['options'] as $value => $optionName): ?>
        <label for="{{ $optionName }}">
                <input id="{{ $optionName }}"
                        name="{{ $settingName }}"
                        type="radio"
                        class="flat-blue"
                        {{ isset($dbSettings[$settingName]) && (bool)$dbSettings[$settingName]->value == $value ? 'checked' : '' }}
                        value="{{ $value }}" />
                {{ $optionName }}
        </label>
    <?php endforeach; ?>
</div>
