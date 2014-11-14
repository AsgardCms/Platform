<div class='form-group'>
    {!! Form::label($settingName, $moduleInfo['description']) !!}
    <?php if (isset($dbSettings[$settingName])): ?>
        {!! Form::textarea($settingName, Input::old($settingName, $dbSettings[$settingName]->plainValue), ['class' => 'form-control', 'placeholder' => $moduleInfo['description']]) !!}
    <?php else: ?>
        {!! Form::textarea($settingName, Input::old($settingName), ['class' => 'form-control', 'placeholder' => $moduleInfo['description']]) !!}
    <?php endif; ?>
</div>
