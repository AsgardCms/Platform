<div class="box-body">
    {!! Form::i18nInput('name', 'Name', $errors, $lang, $tag, ['data-slug' => 'source']) !!}
    {!! Form::i18nInput('slug', 'Slug', $errors, $lang, $tag, ['data-slug' => 'target']) !!}
</div>
