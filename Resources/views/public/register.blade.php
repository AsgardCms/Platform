@extends('user::layouts.account')
@section('title')
Register | @parent
@stop

@section('content')
<div class="header">Register New Membership</div>
@include('flash::message')
{!! Form::open(array('route' => 'register.post')) !!}
    <div class="body bg-gray">
        <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
            {!! Form::label('email', 'Email:') !!}
            {!! Form::text('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
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
        <button type="submit" class="btn bg-olive btn-block">Sign me up</button>
        <a href="{{ URL::route('login') }}" class="text-center">I already have a membership</a>
    </div>
{!! Form::close() !!}
@stop
