@extends('core::layouts.master')

@section('content-header')
<h1>
    Settings
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cog"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
</ol>
@stop

@section('content')
@include('flash::message')
{!! Form::open(['route' => ['dashboard.setting.store'], 'method' => 'post']) !!}
<div class="row">
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">General settings</h3>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="{{ App::getLocale() == 'en' ? 'active' : '' }}"><a href="#tab_1-1" data-toggle="tab">{{ trans('core::core.tab.english') }}</a></li>
                        <li class="{{ App::getLocale() == 'fr' ? 'active' : '' }}"><a href="#tab_2-2" data-toggle="tab">{{ trans('core::core.tab.french') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1-1">
                            @include('setting::admin.partials.fields', ['lang' => 'en'])
                        </div>
                        <div class="tab-pane" id="tab_2-2">
                            @include('setting::admin.partials.fields', ['lang' => 'fr'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('user::button.create') }}</button>
                <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('dashboard.user.index')}}"><i class="fa fa-times"></i> {{ trans('user::button.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-info">
            <div class="box-header"><h3 class="box-title">Module Settings</h3></div>
            <div class="box-body">
                <ul>
                    <li><a href="">Module 1</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop
