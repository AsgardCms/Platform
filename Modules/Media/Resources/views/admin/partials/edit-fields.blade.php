<?php $altAttribute = isset($file->translate($lang)->alt_attribute) ? $file->translate($lang)->alt_attribute : '' ?>
<div class='form-group{{ $errors->has("{$lang}[alt_attribute]") ? ' has-error' : '' }}'>
    {!! Form::label("{$lang}[alt_attribute]", trans('media::media.form.alt_attribute')) !!}
    {!! Form::text("{$lang}[alt_attribute]", old("{$lang}[alt_attribute]", $altAttribute), ['class' => 'form-control', 'placeholder' => trans('media::media.form.alt_attribute')]) !!}
    {!! $errors->first("{$lang}[alt_attribute]", '<span class="help-block">:message</span>') !!}
</div>
<?php $description = isset($file->translate($lang)->description) ? $file->translate($lang)->description : '' ?>
<div class='form-group{{ $errors->has("{$lang}[description]") ? ' has-error' : '' }}'>
    {!! Form::label("{$lang}[description]", trans('media::media.form.description')) !!}
    {!! Form::textarea("{$lang}[description]", old("{$lang}[description]", $description), ['class' => 'form-control', 'placeholder' => trans('media::media.form.description')]) !!}
    {!! $errors->first("{$lang}[description]", '<span class="help-block">:message</span>') !!}
</div>
<?php $keywords = isset($file->translate($lang)->keywords) ? $file->translate($lang)->keywords : '' ?>
<div class='form-group{{ $errors->has("{$lang}[keywords]") ? ' has-error' : '' }}'>
    {!! Form::label("{$lang}[keywords]", trans('media::media.form.keywords')) !!}
    {!! Form::text("{$lang}[keywords]", old("{$lang}[keywords]", $keywords), ['class' => 'form-control', 'placeholder' => trans('media::media.form.keywords')]) !!}
    {!! $errors->first("{$lang}[keywords]", '<span class="help-block">:message</span>') !!}
</div>
