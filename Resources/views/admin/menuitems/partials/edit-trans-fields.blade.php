<div class='form-group{{ $errors->has("title[{$lang}]") ? ' has-error' : '' }}'>
    {!! Form::label("title[{$lang}]", trans('menu::menu.form.title')) !!}
    {!! Form::text("title[{$lang}]", Input::old("title[{$lang}]", $menuItem->translate($lang)->title), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.title')]) !!}
    {!! $errors->first("title[{$lang}]", '<span class="help-block">:message</span>') !!}
</div>
<div class="form-group">
    {!! Form::label("uri[{$lang}]", trans('menu::menu.form.uri')) !!}
    <div class='input-group{{ $errors->has("uri[{$lang}]") ? ' has-error' : '' }}'>
        <span class="input-group-addon">/{{ $lang }}/</span>
        {!! Form::text("uri[{$lang}]", Input::old("uri[{$lang}]", $menuItem->translate($lang)->uri), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.uri')]) !!}
        {!! $errors->first("uri[{$lang}]", '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="checkbox">
    <label for="status[{{$lang}}]">
        <input id="status[{{$lang}}]"
                name="status[{{$lang}}]"
                type="checkbox"
                class="flat-blue"
                {{ (bool)$menuItem->translate($lang)->status ? 'checked' : '' }}
                value="1" />
        {{ trans('menu::menu.form.status') }}
    </label>
</div>
