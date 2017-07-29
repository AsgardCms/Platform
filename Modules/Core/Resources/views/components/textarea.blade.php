@if ($editor->getEditorCssPartial() !== null)
    @if (Cache::store('array')->add('textareaCssLoaded', true, 100))
        @include($editor->getEditorCssPartial())
    @endif
@endif

<div class='{{ $errors->has($fieldName) ? ' has-error' : '' }}'>
    {!! Form::label($fieldName, $labelName) !!}
    <textarea class="{{ $editor->getEditorClass() }}" name="{{ $fieldName }}" id="{{ $fieldName }}" rows="10" cols="80">{{ $slot }}</textarea>
    {!! $errors->first($fieldName, '<span class="help-block">:message</span>') !!}
</div>

@if ($editor->getEditorJsPartial() !== null)
    @if (Cache::store('array')->add('textareaJsLoaded', true, 100))
        @include($editor->getEditorJsPartial())
    @endif
@endif


