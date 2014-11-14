<div class="checkbox">
    <?php foreach($moduleInfo['options'] as $value => $optionName): ?>
        <label for="{{ $optionName . "[$lang]" }}">
                <input id="{{ $optionName . "[$lang]" }}"
                        name="{{ $settingName . "[$lang]" }}"
                        type="radio"
                        class="flat-blue"
                        {{ isset($dbSettings[$settingName]) && (bool)$dbSettings[$settingName]->translate($lang)->value == $value ? 'checked' : '' }}
                        value="{{ $value }}" />
                {{ $optionName }}
        </label>
    <?php endforeach; ?>
</div>
