<div class="box-body">
    {!! Form::i18nInput('name', trans('tag::tags.name'), $errors, $lang, null, ['data-slug' => 'source']) !!}
    {!! Form::i18nInput('slug', trans('tag::tags.slug'), $errors, $lang, null, ['data-slug' => 'target']) !!}
</div>
