<div class="form-group">
    <label for="{{ $settingName }}">{{ $moduleInfo['description'] }}</label>
    <select class="form-control" name="{{ $settingName }}" id="{{ $settingName }}">
        <?php foreach(Config::get('core::config.front-themes') as $theme): ?>
            <option value="{{ $theme }}" {{ isset($dbSettings[$settingName]) && $dbSettings[$settingName]->plainValue == $theme ? 'selected' : '' }}>
                {{ ucfirst($theme) }}
            </option>
        <?php endforeach; ?>
    </select>
</div>
