@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('menu::menu.titles.menu') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li class="active">{{ trans('menu::menu.breadcrumb.menu') }}</li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">

    </div>
</div>
@stop
