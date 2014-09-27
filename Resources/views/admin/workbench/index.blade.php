@extends('core::layouts.master')

@section('content-header')
<h1>
    Module helpers
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Workbench</li>
</ol>
@stop

@section('styles')
<link href="{{{ core_asset('css/vendor/iCheck/flat/blue.css') }}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">Generator</a></li>
                <li><a href="#tab_2-2" data-toggle="tab">Migrations</a></li>
                <li><a href="#tab_3-3" data-toggle="tab">Seeds</a></li>
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

@section('scripts')
<script>
$( document ).ready(function() {
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
});
</script>
@stop