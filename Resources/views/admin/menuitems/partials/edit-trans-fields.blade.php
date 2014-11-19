<div class='form-group{{ $errors->has("{$lang}[title]") ? ' has-error' : '' }}'>
    {!! Form::label("{$lang}[title]", trans('menu::menu.form.title')) !!}
    {!! Form::text("{$lang}[title]", Input::old("{$lang}[title]", $menuItem->translate($lang)->title), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.title')]) !!}
    {!! $errors->first("{$lang}[title]", '<span class="help-block">:message</span>') !!}
</div>
<div class="form-group">
    {!! Form::label("{$lang}[uri]", trans('menu::menu.form.uri')) !!}
    <div class='input-group{{ $errors->has("{$lang}[uri]") ? ' has-error' : '' }}'>
        <span class="input-group-addon">/{{ $lang }}/</span>
        {!! Form::text("{$lang}[uri]", Input::old("{$lang}[uri]", $menuItem->translate($lang)->uri), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.uri')]) !!}
        {!! $errors->first("{$lang}[uri]", '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="checkbox">
    <label for="{{$lang}}[status]">
        <input id="{{$lang}}[status]"
                name="{{$lang}}[status]"
                type="checkbox"
                class="flat-blue"
                {{ (bool)$menuItem->translate($lang)->status ? 'checked' : '' }}
                value="1" />
        {{ trans('menu::menu.form.status') }}
    </label>
</div>
