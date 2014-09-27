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
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                    @include('flash::message')
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::open(['route' => 'dashboard.workbench.generate.index', 'method' => 'post']) !!}
                            <div class="box-body">
                                <h4>Generate a new module</h4>
                                <div class='form-group{{ $errors->has('name') ? ' has-error' : '' }}'>
                                    {!! Form::label('name', 'Module Name:') !!}
                                    {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Module Name']) !!}
                                    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-flat">Generate new module</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-6">
                            {!! Form::open(['route' => 'dashboard.workbench.install.index', 'method' => 'post']) !!}
                            <div class="box-body">
                                <h4>Install a module by vendor/name</h4>
                                <div class='form-group{{ $errors->has('vendorName') ? ' has-error' : '' }}'>
                                    {!! Form::label('vendorName', 'vendor/name of the module:') !!}
                                    {!! Form::text('vendorName', Input::old('vendorName'), ['class' => 'form-control', 'placeholder' => 'Module Name']) !!}
                                    {!! $errors->first('vendorName', '<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="checkbox">
                                    <label for="subtree">
                                        <input id="subtree" name="subtree" type="checkbox" class="flat-blue" value="true" /> Install as a subtree?
                                    </label>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-flat">Install new module</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2-2">
                    @include('flash::message')
                    {!! Form::open(['route' => 'dashboard.workbench.migrate.index', 'method' => 'post']) !!}
                        <div class="box-body">
                            <div class='form-group{{ $errors->has('module') ? ' has-error' : '' }}'>
                                {!! Form::label('module', 'Module Name:') !!}
                                {!! Form::text('module', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Module Name']) !!}
                                {!! $errors->first('module', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-flat">Migrate</button>
                        </div>
                    {!! Form::close() !!}
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