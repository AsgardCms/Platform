@extends('core::layouts.master')

@section('content-header')
<h1>
    New User
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class=""><a href="{{ URL::route('dashboard.user.index') }}">Users</a></li>
    <li class="active">New</li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-<?php echo $errors->first() ? 'danger' : 'info'; ?>">
            {!! Form::open(['route' => 'dashboard.user.store', 'method' => 'post']) !!}
            <div class="box-body">
                <div class="row">
                    @include('flash::message')
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            {!! Form::label('first_name', 'First name:') !!}
                            {!! Form::text('first_name', Input::old('first_name'), ['class' => 'form-control', 'placeholder' => 'First name']) !!}
                            {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            {!! Form::label('last_name', 'Last name:') !!}
                            {!! Form::text('last_name', Input::old('last_name'), ['class' => 'form-control', 'placeholder' => 'Last name']) !!}
                            {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', 'Email:') !!}
                            {!! Form::email('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => 'Email address']) !!}
                            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', 'Password:') !!}
                            {!! Form::password('password', ['class' => 'form-control']) !!}
                            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            {!! Form::label('password_confirmation', 'Password confirmation:') !!}
                            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                            {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Role(s)</label>
                            <select multiple="" class="form-control" name="roles[]">
                                <?php foreach($roles as $role): ?>
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat">Create</button>
                <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('dashboard.user.index')}}"><i class="fa fa-times"></i> Cancel</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
