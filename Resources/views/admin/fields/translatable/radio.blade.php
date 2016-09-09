<div class="checkbox">
    <?php foreach ($moduleInfo['options'] as $value => $optionName): ?>
        <?php $oldValue = (isset($dbSettings[$settingName]) && $dbSettings[$settingName]->hasTranslation($lang)) ? $dbSettings[$settingName]->translate($lang)->value : ''; ?>
        <label for="{{ $optionName . "[$lang]" }}">
                <input id="{{ $optionName . "[$lang]" }}"
                        name="{{ $settingName . "[$lang]" }}"
                        type="radio"
                        class="flat-blue"
                        {{ isset($dbSettings[$settingName]) && (bool)$oldValue == $value ? 'checked' : '' }}
                        value="{{ $value }}" />
                {{ trans($optionName) }}
        </label>
    <?php endforeach; ?>
</div>
