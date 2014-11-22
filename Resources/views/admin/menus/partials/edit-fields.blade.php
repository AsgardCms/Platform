<div class='form-group{{ $errors->has('name') ? ' has-error' : '' }}'>
    {!! Form::label('name', trans('menu::menu.form.name')) !!}
    {!! Form::text('name', Input::old('name', $menu->name), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.name')]) !!}
    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
</div>
<div class="checkbox {{ $errors->has('primary') ? ' has-error' : '' }}">
    <label for="primary">
        <input id="primary"
                name="primary"
                type="checkbox"
                class="flat-blue"
                {{ ((bool)$menu->primary) ? 'checked' : '' }}
                value="1" />
        {{ trans('menu::menu.form.primary') }}
        {!! $errors->first('primary', '<span class="help-block">:message</span>') !!}
    </label>
</div>
