@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('setting::settings.title.settings') }}
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li class="active"><i class="fa fa-cog"></i> {{ trans('setting::settings.breadcrumb.settings') }}</li>
</ol>
@stop

@section('content')
@include('flash::message')
{!! Form::open(['route' => ['dashboard.setting.store'], 'method' => 'post']) !!}
<div class="row">
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">{{ trans('setting::settings.title.general settings') }}</h3>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="{{ App::getLocale() == 'en' ? 'active' : '' }}"><a href="#tab_1-1" data-toggle="tab">{{ trans('core::core.tab.english') }}</a></li>
                        <li class="{{ App::getLocale() == 'fr' ? 'active' : '' }}"><a href="#tab_2-2" data-toggle="tab">{{ trans('core::core.tab.french') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{ App::getLocale() == 'en' ? 'active' : '' }}" id="tab_1-1">
                            @include('setting::admin.partials.fields', ['lang' => 'en'])
                        </div>
                        <div class="tab-pane {{ App::getLocale() == 'fr' ? 'active' : '' }}" id="tab_2-2">
                            @include('setting::admin.partials.fields', ['lang' => 'fr'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.create') }}</button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-info">
            <div class="box-header"><h3 class="box-title">{{ trans('setting::settings.title.module settings') }}</h3></div>
            <div class="box-body">
                <ul>
                    <?php foreach($modulesWithSettings as $module => $settings): ?>
                        <li><a href="{{ URL::route('dashboard.module.settings', [strtolower($module)]) }}">{{ $module }}</a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop
