@extends('layouts.account')
@section('title')
    {{ trans('user::auth.register') }} | @parent
@stop

@section('content')
<div class="header">{{ trans('user::auth.register') }}</div>
@include('flash::message')
{!! Form::open(array('route' => 'register.post')) !!}
    <div class="body bg-gray">
        <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
            {!! Form::label('email', trans('user::auth.email')) !!}
            {!! Form::text('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => trans('user::auth.email')]) !!}
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
            {!! Form::label('password', trans('user::auth.password')) !!}
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('user::auth.password')]) !!}
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
            {!! Form::label('password_confirmation', trans('user::auth.password confirmation')) !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('user::auth.password confirmation')]) !!}
            {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
    <div class="footer">
        <button type="submit" class="btn btn-info btn-block">{{ trans('user::auth.register me')}}</button>
        <a href="{{ URL::route('login') }}" class="text-center">{{ trans('user::auth.I already have a membership') }}</a>
    </div>
{!! Form::close() !!}
@stop
