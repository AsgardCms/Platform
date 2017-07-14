@if ($editor->getEditorCssPartial() !== null)
    @include($editor->getEditorCssPartial())
@endif

<div class='{{ $errors->has("{$lang}.body") ? ' has-error' : '' }}'>
    {!! Form::label("{$lang}[body]", trans('page::pages.form.body')) !!}
    <textarea class="{{ $editor->getEditorClass() }}" name="{{$lang}}[body]" rows="10" cols="80">{{ $slot }}</textarea>
    {!! $errors->first("{$lang}.body", '<span class="help-block">:message</span>') !!}
</div>

@if ($editor->getEditorJsPartial() !== null)
    @include($editor->getEditorJsPartial())
@endif

