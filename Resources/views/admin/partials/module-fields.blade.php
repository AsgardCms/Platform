<?php foreach($moduleSettings as $setting => $description): ?>
    <?php $settingName = $module . '_' . $setting; ?>
    <div class='form-group'>
        {!! Form::label($settingName . "[$lang]", $description) !!}
        <?php if (isset($settings[$settingName])): ?>
            {!! Form::text($settingName . "[$lang]", Input::old($settingName . "[$lang]", $settings[$settingName]->translate($lang)->value), ['class' => 'form-control', 'placeholder' => $description]) !!}
        <?php else: ?>
            {!! Form::text($settingName . "[$lang]", Input::old($settingName . "[$lang]"), ['class' => 'form-control', 'placeholder' => $description]) !!}
        <?php endif; ?>
    </div>
<?php endforeach; ?>
