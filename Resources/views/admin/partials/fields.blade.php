<div class='form-group{{ $errors->has("site-name[$lang]") ? ' has-error' : '' }}'>
    {!! Form::label("site-name[$lang]", 'Site name:') !!}
    {!! Form::text("site-name[$lang]", Input::old("site-name[$lang]"), ['class' => 'form-control', 'placeholder' => 'Site name']) !!}
    {!! $errors->first("site-name[$lang]", '<span class="help-block">:message</span>') !!}
</div>
<div class='form-group{{ $errors->has("site-description[$lang]") ? ' has-error' : '' }}'>
    {!! Form::label("site-description[$lang]", 'Site-description:') !!}
    {!! Form::textarea("site-description[$lang]", Input::old("site-description[$lang]"), ['class' => 'form-control', 'placeholder' => 'Site description']) !!}
    {!! $errors->first("site-description[$lang]", '<span class="help-block">:message</span>') !!}
</div>
