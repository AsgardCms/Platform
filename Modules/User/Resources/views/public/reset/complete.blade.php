@extends('layouts.account')

@section('title')
    {{ trans('user::auth.reset password') }} | @parent
@stop

@section('content')
    <div class="login-logo">
        <a href="{{ url('/') }}">{{ setting('core::site-name') }}</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('user::auth.reset password') }}</p>
        @include('partials.notifications')

        {!! Form::open() !!}
        <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control" autofocus
                   name="password" placeholder="{{ trans('user::auth.password') }}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('user::auth.password confirmation') }}">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat pull-right">
                    {{ trans('user::auth.reset password') }}
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop
