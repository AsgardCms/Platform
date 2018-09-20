<?php $defaultValue = isset($moduleInfo['default']) ? $moduleInfo['default']: ''; ?>
<div class='form-group'>
    {!! Form::label($settingName, trans($moduleInfo['description'])) !!}
    <?php if (isset($dbSettings[$settingName]) && $dbSettings[$settingName]->plainValue !== null): ?>
        {!! Form::textarea($settingName, old($settingName, $dbSettings[$settingName]->plainValue) ?: $defaultValue, ['class' => 'form-control', 'placeholder' => trans($moduleInfo['description'])]) !!}
    <?php else: ?>
        {!! Form::textarea($settingName, old($settingName, $defaultValue), ['class' => 'form-control', 'placeholder' => trans($moduleInfo['description'])]) !!}
    <?php endif; ?>
</div>
