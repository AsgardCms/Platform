<div class="box-body">
    <div class="box-body">
        <div class='form-group{{ $errors->has("{$lang}.title") ? ' has-error' : '' }}'>
            {!! Form::label("{$lang}[title]", trans('page::pages.form.title')) !!}
            <?php $old = $page->hasTranslation($lang) ? $page->translate($lang)->title : '' ?>
            {!! Form::text("{$lang}[title]", old("{$lang}.title", $old), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('page::pages.form.title')]) !!}
            {!! $errors->first("{$lang}.title", '<span class="help-block">:message</span>') !!}
        </div>
        <div class='form-group{{ $errors->has("{$lang}[slug]") ? ' has-error' : '' }}'>
            {!! Form::label("{$lang}[slug]", trans('page::pages.form.slug')) !!}
            <?php $old = $page->hasTranslation($lang) ? $page->translate($lang)->slug : '' ?>
            {!! Form::text("{$lang}[slug]", old("{$lang}.slug", $old), ['class' => 'form-control slug', 'data-slug' => 'target', 'placeholder' => trans('page::pages.form.slug')]) !!}
            {!! $errors->first("{$lang}.slug", '<span class="help-block">:message</span>') !!}
        </div>

        <?php $old = $page->hasTranslation($lang) ? $page->translate($lang)->body : '' ?>
        @editor('body', trans('page::pages.form.body'), old("$lang.body", $old), $lang)

        <?php if (config('asgard.page.config.partials.translatable.edit') !== []): ?>
            <?php foreach (config('asgard.page.config.partials.translatable.edit') as $partial): ?>
                @include($partial)
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="box-group" id="accordion">
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-primary">
            <div class="box-header">
                <h4 class="box-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo-{{$lang}}">
                        {{ trans('page::pages.form.meta_data') }}
                    </a>
                </h4>
            </div>
            <div style="height: 0px;" id="collapseTwo-{{$lang}}" class="panel-collapse collapse">
                <div class="box-body">
                    <div class='form-group{{ $errors->has("{$lang}[meta_title]") ? ' has-error' : '' }}'>
                        {!! Form::label("{$lang}[meta_title]", trans('page::pages.form.meta_title')) !!}
                        <?php $old = $page->hasTranslation($lang) ? $page->translate($lang)->meta_title : '' ?>
                        {!! Form::text("{$lang}[meta_title]", old("{$lang}.meta_title", $old), ['class' => "form-control", 'placeholder' => trans('page::pages.form.meta_title')]) !!}
                        {!! $errors->first("{$lang}[meta_title]", '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class='form-group{{ $errors->has("{$lang}[meta_description]") ? ' has-error' : '' }}'>
                        {!! Form::label("{$lang}[meta_description]", trans('page::pages.form.meta_description')) !!}
                        <?php $old = $page->hasTranslation($lang) ? $page->translate($lang)->meta_description : '' ?>
                        <textarea class="form-control" name="{{$lang}}[meta_description]" rows="10" cols="80">{{ old("$lang.meta_description", $old) }}</textarea>
                        {!! $errors->first("{$lang}[meta_description]", '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="panel box box-primary">
            <div class="box-header">
                <h4 class="box-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFacebook-{{$lang}}">
                        {{ trans('page::pages.form.facebook_data') }}
                    </a>
                </h4>
            </div>
            <div style="height: 0px;" id="collapseFacebook-{{$lang}}" class="panel-collapse collapse">
                <div class="box-body">
                    <div class='form-group{{ $errors->has("{$lang}[og_title]") ? ' has-error' : '' }}'>
                        {!! Form::label("{$lang}[og_title]", trans('page::pages.form.og_title')) !!}
                        <?php $old = $page->hasTranslation($lang) ? $page->translate($lang)->og_title : '' ?>
                        {!! Form::text("{$lang}[og_title]", old("{$lang}.og_title", $old), ['class' => "form-control", 'placeholder' => trans('page::pages.form.og_title')]) !!}
                        {!! $errors->first("{$lang}[og_title]", '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class='form-group{{ $errors->has("{$lang}[og_description]") ? ' has-error' : '' }}'>
                        {!! Form::label("{$lang}[og_description]", trans('page::pages.form.og_description')) !!}
                        <?php $old = $page->hasTranslation($lang) ? $page->translate($lang)->og_description : '' ?>
                        <textarea class="form-control" name="{{$lang}}[og_description]" rows="10" cols="80">{{ old("$lang.og_description", $old) }}</textarea>
                        {!! $errors->first("{$lang}[og_description]", '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group{{ $errors->has("{$lang}[og_type]") ? ' has-error' : '' }}">
                        <label>{{ trans('page::pages.form.og_type') }}</label>
                        <?php $oldType = $page->hasTranslation($lang) ? $page->translate($lang)->og_type : '' ?>
                        <?php $oldType = null !== old("$lang.og_type") ? old("$lang.og_type") : $oldType; ?>
                        <select class="form-control" name="{{ $lang }}[og_type]">
                            <option value="website" {{ $oldType == 'website' ? 'selected' : ''}}>
                                {{ trans('page::pages.facebook-types.website') }}
                            </option>
                            <option value="product" {{ $oldType == 'product' ? 'selected' : ''}}>
                                {{ trans('page::pages.facebook-types.product') }}
                            </option>
                            <option value="article" {{ $oldType == 'article' ? 'selected' : ''}}>
                                {{ trans('page::pages.facebook-types.article') }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
