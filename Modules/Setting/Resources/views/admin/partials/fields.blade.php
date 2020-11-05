<?php foreach ($settings as $settingName => $moduleInfo): ?>

    <?php $type = Arr::get($moduleInfo, 'translatable', false) ? 'translatable' : 'plain' ?>
    <?php $fieldView = Str::contains($moduleInfo['view'], '::') ? $moduleInfo['view'] : "setting::admin.fields.$type.{$moduleInfo['view']}" ?>
    <?php $locale = isset($locale) ? $locale : '' ?>
    @include($fieldView, [
        'lang' => $locale,
        'settings' => $settings,
        'setting' => $settingName,
        'moduleInfo' => $moduleInfo,
        'settingName' => strtolower($currentModule) . '::' . $settingName
    ])
<?php endforeach;
