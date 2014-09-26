@extends('core::layouts.master')

@section('content-header')
<h1>
    Updating Role <small>{{ $role->name }}</small>
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class=""><a href="{{ URL::route('dashboard.role.index') }}">Roles</a></li>
    <li class="active">Update</li>
</ol>
@stop

@section('styles')
<link href="{{{ core_asset('css/vendor/iCheck/flat/blue.css') }}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        {!! Form::open(['route' => ['dashboard.role.update', $role->id], 'method' => 'put']) !!}
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">Data</a></li>
                <li class=""><a href="#tab_2-2" data-toggle="tab">Permissions</a></li>
                <li class=""><a href="#tab_3-3" data-toggle="tab">Users</a></li>
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
                                    {!! Form::text('name', Input::old('name', $role->name), ['class' => 'form-control', 'placeholder' => 'First name']) !!}
                                    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                    {!! Form::label('slug', 'Role slug:') !!}
                                    {!! Form::text('slug', Input::old('slug', $role->slug), ['class' => 'form-control', 'placeholder' => 'Last name']) !!}
                                    {!! $errors->first('slug', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2-2">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php foreach($permissions as $name => $value): ?>
                                    <h3>{{ $name }} Module</h3>
                                    <?php foreach($value as $subPermissionTitle => $permissionName): ?>
                                        <h4>{{ ucfirst($subPermissionTitle) }}</h4>
                                        <?php foreach($permissionName as $permissionAction): ?>
                                            <div class="checkbox">
                                                <label for="<?php echo "$subPermissionTitle.$permissionAction" ?>">
                                                    <input id="<?php echo "$subPermissionTitle.$permissionAction" ?>" name="permissions[<?php echo "$subPermissionTitle.$permissionAction" ?>]" type="checkbox" class="flat-blue" <?php echo $role->hasAccess("$subPermissionTitle.$permissionAction") ? 'checked' : '' ?> value="true" /> {{ ucfirst($permissionAction) }}
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3-3">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Users with this role</h3>
                                <ul>
                                    <?php foreach($role->users()->get() as $user): ?>
                                        <li>
                                            <a href="{{ URL::route('dashboard.user.edit', [$user->id]) }}">{{ $user->present()->fullname() }}</a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-flat">Update</button>
                <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('dashboard.role.index')}}"><i class="fa fa-times"></i> Cancel</a>
                <div class="clearfix"></div>
            </div><!-- /.tab-content -->
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