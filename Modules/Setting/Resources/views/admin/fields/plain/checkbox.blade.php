<div class="checkbox">
    <label for="{{ $settingName }}">
        <input id="{{ $settingName }}"
                name="{{ $settingName }}"
                type="checkbox"
                class="flat-blue"
                {{ isset($dbSettings[$settingName]) && (bool)$dbSettings[$settingName]->plainValue == true ? 'checked' : '' }}
                value="1" />
        {{ trans($moduleInfo['description']) }}
    </label>
</div>
