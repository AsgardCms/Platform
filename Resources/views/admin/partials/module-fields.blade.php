<?php foreach($moduleSettings as $setting => $description): ?>
    <div class='form-group'>
        {!! Form::label($setting . "[$lang]", $description) !!}
        <?php if (count($settings) >= 1): ?>
            {!! Form::text($setting . "[$lang]", Input::old($setting . "[$lang]", $settings[$setting]->translate($lang)->value), ['class' => 'form-control', 'placeholder' => $description]) !!}
        <?php else: ?>
            {!! Form::text($setting . "[$lang]", Input::old($setting . "[$lang]"), ['class' => 'form-control', 'placeholder' => $description]) !!}
        <?php endif; ?>
    </div>
<?php endforeach; ?>
