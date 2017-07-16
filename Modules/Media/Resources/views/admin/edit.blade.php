@extends('layouts.master')

@section('content-header')
<h1>
    {{ trans('media::media.title.edit media') }} <small>{{ $file->filename }}</small>
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="{{ URL::route('admin.media.media.index') }}">{{ trans('media::media.title.media') }}</a></li>
    <li class="active">{{ trans('media::media.title.edit media') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['admin.media.media.update', $file->id], 'method' => 'put']) !!}
<div class="row">
    <div class="col-md-8">
        <div class="nav-tabs-custom">
            @include('partials.form-tab-headers')
            <div class="tab-content">
                <?php $i = 0; ?>
                <?php foreach (LaravelLocalization::getSupportedLocales() as $locale => $language): ?>
                    <?php ++$i; ?>
                    <div class="tab-pane {{ App::getLocale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                        @include('media::admin.partials.edit-fields', ['lang' => $locale])
                    </div>
                <?php endforeach; ?>
                <hr>
                @tags('asgardcms/media', $file)
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('admin.media.media.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                </div>
            </div>
        </div> {{-- end nav-tabs-custom --}}
    </div>
    <div class="col-md-4">
        <?php if ($file->isImage()): ?>
            <img src="{{ $file->path }}" alt="" style="width: 100%;"/>
        <?php else: ?>
            <i class="fa fa-file" style="font-size: 50px;"></i>
        <?php endif; ?>
    </div>
</div>


<?php if ($file->isImage()): ?>
<div class="row">
    <div class="col-md-12">
        <h3>Thumbnails</h3>

        <ul class="list-unstyled">
            <?php foreach ($thumbnails as $thumbnail): ?>
                <li style="float:left; margin-right: 10px">
                    <img src="{{ Imagy::getThumbnail($file->path, $thumbnail->name()) }}" alt=""/>
                    <p class="text-muted" style="text-align: center">{{ $thumbnail->name() }} ({{ $thumbnail->size() }})</p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>
{!! Form::close() !!}
@stop


@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop

@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index', ['name' => 'media']) }}</dd>
    </dl>
@stop

@push('js-stack')
    <script>
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.media.media.index') ?>" }
                ]
            });
        });
    </script>
@endpush
