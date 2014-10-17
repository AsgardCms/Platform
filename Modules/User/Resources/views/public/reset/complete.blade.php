@extends('user::layouts.account')

@section('title')
Reset password | @parent
@stop

@section('content')
<div class="header">Reset Password</div>
@include('flash::message')
{!! Form::open() !!}
    <div class="body bg-gray">
        <div class="form-group{{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
            {!! Form::label('password', 'Password:') !!}
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
            {!! Form::label('password_confirmation', 'Password Confirmation:') !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password Confirmation']) !!}
            {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
    <div class="footer">
        <button type="submit" class="btn bg-olive btn-block">Reset</button>
    </div>
{!! Form::close(); !!}

@stop
