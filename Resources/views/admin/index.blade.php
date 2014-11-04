@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('menu::menu.titles.menu') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li class="active">{{ trans('menu::menu.breadcrumb.menu') }}</li>
</ol>
@stop

@section('styles')
<link href="{!! Module::asset('menu', 'css/nestable.css') !!}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="btn-group pull-right">
            <a href="{{ URL::route('dashboard.menu.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                <i class="fa fa-pencil"></i> {{ trans('menu::menu.button.create menu item') }}
            </a>
        </div>
        <div class="dd">
            <ol class="dd-list">
                <li class="dd-item" data-id="1">
                    <div class="dd-handle">Item 1</div>
                </li>
                <li class="dd-item" data-id="2">
                    <div class="dd-handle">Item 2</div>
                </li>
                <li class="dd-item" data-id="3">
                    <div class="dd-handle">Item 3</div>
                    <ol class="dd-list">
                        <li class="dd-item" data-id="4">
                            <div class="dd-handle">Item 4</div>
                        </li>
                        <li class="dd-item" data-id="5">
                            <div class="dd-handle">Item 5</div>
                        </li>
                    </ol>
                </li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{!! Module::asset('menu', 'js/jquery.nestable.js') !!}"></script>
<script>
$( document ).ready(function() {
    $('.dd').nestable();
    $('.dd').on('change', function() {
        var data = $('.dd').nestable('serialize');
        console.log(data);
    });
});
</script>
@stop
