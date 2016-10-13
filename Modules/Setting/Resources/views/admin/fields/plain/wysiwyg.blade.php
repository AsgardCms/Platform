<div class='form-group'>
    {!! Form::label($settingName, trans($moduleInfo['description'])) !!}
    <?php if (isset($dbSettings[$settingName]) && $dbSettings[$settingName]->plainValue !== null): ?>
        {!! Form::textarea($settingName, old($settingName, $dbSettings[$settingName]->plainValue), ['class' => 'form-control ckeditor', 'placeholder' => trans($moduleInfo['description'])]) !!}
    <?php else: ?>
        {!! Form::textarea($settingName, old($settingName), ['class' => 'form-control ckeditor', 'placeholder' => trans($moduleInfo['description'])]) !!}
    <?php endif; ?>
</div>
