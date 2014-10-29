<div class='form-group{{ $errors->has("alt_attribute[{$lang}]") ? ' has-error' : '' }}'>
    {!! Form::label("alt_attribute[{$lang}]", trans('media::media.form.alt_attribute')) !!}
    {!! Form::text("alt_attribute[{$lang}]", Input::old("alt_attribute[{$lang}]", $file->translate($lang)->alt_attribute), ['class' => 'form-control', 'placeholder' => trans('media::media.form.alt_attribute')]) !!}
    {!! $errors->first("alt_attribute[{$lang}]", '<span class="help-block">:message</span>') !!}
</div>

<div class='form-group{{ $errors->has("description[{$lang}]") ? ' has-error' : '' }}'>
    {!! Form::label("description[{$lang}]", trans('media.media.form.description')) !!}
    {!! Form::textarea("description[{$lang}]", Input::old("description[{$lang}]", $file->translate($lang)->description), ['class' => 'form-control', 'placeholder' => trans('media::media.form.description')]) !!}
    {!! $errors->first("description[{$lang}]", '<span class="help-block">:message</span>') !!}
</div>

<div class='form-group{{ $errors->has("keywords[{$lang}]") ? ' has-error' : '' }}'>
    {!! Form::label("keywords[{$lang}]", trans('media::media.form.keywords')) !!}
    {!! Form::text("keywords[{$lang}]", Input::old("keywords[{$lang}]", $file->translate($lang)->keywords), ['class' => 'form-control', 'placeholder' => trans('media::media.form.keywords')]) !!}
    {!! $errors->first("keywords[{$lang}]", '<span class="help-block">:message</span>') !!}
</div>
