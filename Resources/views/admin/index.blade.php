@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('media::media.title.media') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="#"><i class="fa fa-dashboard"></i> {{ trans('media::media.breadcrumb.media') }}</a></li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('flash::message')
        <p>media!</p>
    </div>
</div>
@stop
