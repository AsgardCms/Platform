@php
    $setting = isset($dbSettings[$settingName]) ? $dbSettings[$settingName] : null;
@endphp

@mediaSingle($settingName, $setting, null, trans($moduleInfo['description']))
