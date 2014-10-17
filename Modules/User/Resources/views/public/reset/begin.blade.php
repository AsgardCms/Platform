@extends('user::layouts.account')

@section('title')
Reset password | @parent
@stop

@section('content')
<div class="header">Reset Password</div>
@include('flash::message')
{!! Form::open(array('route' => 'reset.post')) !!}
    <div class="body bg-gray">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input type="email" name="email" class="form-control"
                   placeholder="Email" value="{{ Input::old('email')}}" required=""/>
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
    <div class="footer">
        <button type="submit" class="btn bg-olive btn-block">Reset</button>
        <p><a href="{{URL::route('login')}}">I remembered my password.</a></p>
    </div>
{!! Form::close(); !!}

@stop
