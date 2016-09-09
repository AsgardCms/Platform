<div class="form-group">
    <label for="{{ $settingName }}">{{ trans($moduleInfo['description']) }}</label>
    <select class="form-control" name="{{ $settingName }}" id="{{ $settingName }}">
        <?php foreach ($themes as $name => $theme): ?>
            <option value="{{ $name }}" {{ isset($dbSettings[$settingName]) && $dbSettings[$settingName]->plainValue == $name ? 'selected' : '' }}>
                {{ $theme->getName() }}
            </option>
        <?php endforeach; ?>
    </select>
</div>
