<?php $defaultValue = isset($moduleInfo['default']) ? $moduleInfo['default']: ''; ?>
<div class='form-group'>
    {!! Form::label($settingName . "[$lang]", trans($moduleInfo['description'])) !!}
    <?php if (isset($dbSettings[$settingName])): ?>
        <?php $value = $dbSettings[$settingName]->hasTranslation($lang) ? $dbSettings[$settingName]->translate($lang)->value : ''; ?>
        {!! Form::text($settingName . "[$lang]", old($settingName . "[$lang]", $value) ?: $defaultValue, ['class' => 'form-control', 'placeholder' => trans($moduleInfo['description'])]) !!}
    <?php else: ?>
        {!! Form::text($settingName . "[$lang]", old($settingName . "[$lang]", $defaultValue), ['class' => 'form-control', 'placeholder' => trans($moduleInfo['description'])]) !!}
    <?php endif; ?>
</div>
