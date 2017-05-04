@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('core::core.error 500') }}
    </h1>
    <ol class="breadcrumb">
    	<li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="current">{{ trans('core::core.error 500') }}</li>
    </ol>
@stop


@section('content')
    <div class="error-page">
        <h2 class="headline text-red" style="line-height: 0.6; margin-top: 0;"> 500</h2>

        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> {{ trans('core::core.error 500 title') }}</h3>
            <p>{!! trans('core::core.error 500 description') !!}</p>
        </div>
        <!-- /.error-content -->
    </div>
@stop
