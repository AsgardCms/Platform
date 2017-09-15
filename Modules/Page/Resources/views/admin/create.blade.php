@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('page::pages.create page') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.page.page.index') }}">{{ trans('page::pages.pages') }}</a></li>
        <li class="active">{{ trans('page::pages.create page') }}</li>
    </ol>
@stop

@push('css-stack')
    <style>
        .checkbox label {
            padding-left: 0;
        }
        .error {
            color: #dd4b39 !important;
        }
    </style>
@endpush

@section('content')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('page::pages.navigation.back to index') }}</dd>
    </dl>
@stop

@push('js-stack')
    <script>
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.page.page.index') ?>" }
                ]
            });
        });
    </script>
@endpush
