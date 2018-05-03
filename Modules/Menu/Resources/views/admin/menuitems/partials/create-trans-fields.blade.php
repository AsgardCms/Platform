<div class='form-group{{ $errors->has("{$lang}[title]") ? ' has-error' : '' }}'>
    {!! Form::label("{$lang}[title]", trans('menu::menu.form.title')) !!}
    {!! Form::text("{$lang}[title]", old("{$lang}[title]"), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.title'), 'autofocus']) !!}
    {!! $errors->first("{$lang}[title]", '<span class="help-block">:message</span>') !!}
</div>
<div class="form-group link-type-depended link-internal">
    {!! Form::label("{$lang}[uri]", trans('menu::menu.form.uri')) !!}
    <div class='input-group{{ $errors->has("{$lang}[uri]") ? ' has-error' : '' }}'>
        <span class="input-group-addon">/{{ $lang }}/</span>
        {!! Form::text("{$lang}[uri]", old("{$lang}[uri]"), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.uri')]) !!}
        {!! $errors->first("{$lang}[uri]", '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has("{$lang}[url]") ? ' has-error' : '' }} link-type-depended link-external">
    {!! Form::label("{$lang}[url]", trans('menu::menu.form.url')) !!}
    {!! Form::text("{$lang}[url]", old("{$lang}[url]"), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.url')]) !!}
    {!! $errors->first("{$lang}[url]", '<span class="help-block">:message</span>') !!}
</div>
<div class="form-group{{ $errors->has("{$lang}[description]") ? ' has-error' : '' }}">
    {!! Form::label("{$lang}[description]", trans('menu::menu.form.description')) !!}
    {!! Form::text("{$lang}[description]", old("{$lang}[description]"), ['class' => 'form-control', 'placeholder' => trans('menu::menu.form.description')]) !!}
    {!! $errors->first("{$lang}[description]", '<span class="help-block">:message</span>') !!}
</div>

<div class="checkbox">
    <label for="{{$lang}}[status]">
        <input id="{{$lang}}[status]"
                name="{{$lang}}[status]"
                type="checkbox"
                class="flat-blue"
                value="1" />
        {{ trans('menu::menu.form.status') }}
    </label>
</div>
