@extends('core::layouts.master')

@section('content-header')
<h1>
    New Role
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class=""><a href="{{ URL::route('dashboard.role.index') }}">Roles</a></li>
    <li class="active">New</li>
</ol>
@stop

@section('styles')
<link href="{{{ core_asset('css/vendor/iCheck/flat/blue.css') }}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        {!! Form::open(['route' => 'dashboard.role.store', 'method' => 'post']) !!}
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">Data</a></li>
                <li class=""><a href="#tab_2-2" data-toggle="tab">Permissions</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                    <div class="box-body">
                        <div class="row">
                            @include('flash::message')
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    {!! Form::label('name', 'Role name:') !!}
                                    {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'First name']) !!}
                                    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                    {!! Form::label('slug', 'Role slug:') !!}
                                    {!! Form::text('slug', Input::old('slug'), ['class' => 'form-control', 'placeholder' => 'Last name']) !!}
                                    {!! $errors->first('slug', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2-2">
                    @include('user::admin.partials.permissions-create')
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">Create</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('dashboard.role.index')}}"><i class="fa fa-times"></i> Cancel</a>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
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
