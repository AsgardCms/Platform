@if ($editor->getEditorCssPartial() !== null)
    @if (Cache::store('array')->add('textareaCssLoaded', true, 100))
        @include($editor->getEditorCssPartial())
    @endif
@endif

<div class='{{ $errors->has("{$lang}.{$fieldName}") ? ' has-error' : '' }}'>
    {!! Form::label("{$lang}[{$fieldName}]", $labelName) !!}
    <textarea class="{{ $editor->getEditorClass() }}" name="{{$lang}}[{{$fieldName}}]" id="{{$lang}}[{{$fieldName}}]" rows="10" cols="80">{{ $slot }}</textarea>
    {!! $errors->first("{$lang}.{$fieldName}", '<span class="help-block">:message</span>') !!}
</div>

@if ($editor->getEditorJsPartial() !== null)
    @if (Cache::store('array')->add('textareaJsLoaded', true, 100))
        @include($editor->getEditorJsPartial())
    @endif
@endif


