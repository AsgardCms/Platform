@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('core::core.error 404') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="current">{{ trans('core::core.error 404') }}</li>
    </ol>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-yellow" style="line-height: 0.6; margin-top: 0;"> 404</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> {{ trans('core::core.error 404 title') }}</h3>
            <p>{!! trans('core::core.error 404 description') !!}</p>
        </div>
        <!-- /.error-content -->
    </div>
@stop
