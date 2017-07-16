@extends('layouts.master')

@section('content-header')
<h1>
    {{ trans('user::users.title.edit-profile') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li class="active">{{ trans('user::users.breadcrumb.edit-profile') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['admin.account.profile.update'], 'method' => 'put']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#account_tab" data-toggle="tab">{{ trans('user::users.tabs.data') }}</a></li>
                <li class=""><a href="#password_tab" data-toggle="tab">{{ trans('user::users.tabs.new password') }}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="account_tab">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                {{ Form::normalInput('first_name', trans('user::users.form.first-name'), $errors, $user) }}
                            </div>
                            <div class="col-md-4">
                                {{ Form::normalInput('last_name', trans('user::users.form.last-name'), $errors, $user) }}
                            </div>
                            <div class="col-md-4">
                                {{ Form::normalInputOfType('email', 'email', trans('user::users.form.email'), $errors, $user) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="password_tab">
                    <div class="box-body">
                        <h4>{{ trans('user::users.new password setup') }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                {{ Form::normalInputOfType('password', 'password', trans('user::users.form.new password'), $errors) }}
                            </div>
                            <div class="col-md-6">
                                {{ Form::normalInputOfType('password', 'password_confirmation', trans('user::users.form.new password confirmation'), $errors) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop
@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
@stop

@push('js-stack')
<script>
$( document ).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
});
</script>
@endpush
