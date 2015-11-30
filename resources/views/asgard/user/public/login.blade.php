@extends('layouts.account')

@section('title')
    {{ trans('user::auth.login') }} | @parent
@stop

@section('content')
    <div class="login-logo">
        <a href="{{ url('/') }}">{{ setting('core::site-name') }}</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('user::auth.sign in welcome message') }}</p>
        @include('flash::message')

        {!! Form::open(['route' => 'login.post']) !!}
            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control"
                       name="email" placeholder="{{ trans('user::auth.email') }}" value="{{ old('email')}}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control"
                       name="password" placeholder="Password" value="{{ old('password')}}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> {{ trans('user::auth.remember me') }}
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        {{ trans('user::auth.login') }}
                    </button>
                </div>
            </div>
        </form>

        <a href="{{ route('reset')}}">{{ trans('user::auth.forgot password') }}</a><br>
        <a href="{{ route('register')}}" class="text-center">{{ trans('user::auth.register')}}</a>
    </div>
@stop
