<div class='form-group'>
    {!! Form::label($settingName . "[$lang]", $moduleInfo['description']) !!}
    <?php if (isset($dbSettings[$settingName])): ?>
        {!! Form::input('number', $settingName . "[$lang]", Input::old($settingName . "[$lang]", $dbSettings[$settingName]->translate($lang)->value), ['class' => 'form-control', 'placeholder' => $moduleInfo['description']]) !!}
    <?php else: ?>
        {!! Form::input('number', $settingName . "[$lang]", Input::old($settingName . "[$lang]"), ['class' => 'form-control', 'placeholder' => $moduleInfo['description']]) !!}
    <?php endif; ?>
</div>
