@extends('user::layouts.account')

@section('title')
Login | @parent
@stop

@section('content')
<div class="header">Sign In</div>
@include('flash::message')
{!! Form::open(array('route' => 'login.post')) !!}
    <div class="body bg-gray">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input type="email" name="email" class="form-control"
                   placeholder="Email" value="{{ Input::old('email')}}"/>
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" name="password"
                   class="form-control" placeholder="Password"
                   value="{{ Input::old('password')}}"/>
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group">
            <input type="checkbox" name="remember_me" id="remember_me"/> <label for="remember_me">Remember me</label>
        </div>
    </div>
    <div class="footer">
        <button type="submit" class="btn bg-olive btn-block">Sign me in</button>
        <p><a href="{{URL::route('reset')}}">I forgot my password</a></p>
        <a href="{{URL::route('register')}}" class="text-center">Register a new membership</a>
    </div>
</form>

@stop
