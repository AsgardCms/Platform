<?php use Illuminate\Support\Str; ?>
<?php foreach($settings as $settingName => $moduleInfo): ?>
    <?php $fieldView = Str::contains($moduleInfo['view'], '::') ? $moduleInfo['view'] : "setting::admin.fields.translatable.{$moduleInfo['view']}" ?>
    @include($fieldView, [
        'lang' => $locale,
        'settings' => $settings,
        'setting' => $settingName,
        'moduleInfo' => $moduleInfo,
        'settingName' => strtolower($currentModule) . '::' . $settingName
    ])
<?php endforeach;
