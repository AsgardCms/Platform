@extends('layouts.master')

@section('content-header')
<h1>
    {{ trans('workshop::workbench.title') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('user::users.breadcrumb.home') }}</a></li>
    <li class="active">{{ trans('workshop::workbench.title') }}</li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">{{ trans('workshop::workbench.tab.generators') }}</a></li>
                <li><a href="#tab_2-2" data-toggle="tab">{{ trans('workshop::workbench.tab.migrations') }}</a></li>
                <li><a href="#tab_3-3" data-toggle="tab">{{ trans('workshop::workbench.tab.seeds') }}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                    @include('workshop::admin.workbench.tabs.generate')
                </div>
                <div class="tab-pane" id="tab_2-2">
                    @include('workshop::admin.workbench.tabs.migrate')
                </div>
                <div class="tab-pane" id="tab_3-3">
                    @include('workshop::admin.workbench.tabs.seed')
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js-stack')
<script>
$( document ).ready(function() {
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
});
</script>
@endpush
