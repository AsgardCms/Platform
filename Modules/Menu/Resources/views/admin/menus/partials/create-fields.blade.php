<div class='form-group{{ $errors->has('name') ? ' has-error' : '' }}'>
    {!! Form::label('name', trans('menu::menu.form.name')) !!}
    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.name')]) !!}
    {!! $errors->first('Name', '<span class="help-block">:message</span>') !!}
</div>
