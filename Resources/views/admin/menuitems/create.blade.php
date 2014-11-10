@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('menu::menu.titles.create menu item') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="{{ URL::route('dashboard.menu.index') }}">{{ trans('menu::menu.breadcrumb.menu') }}</a></li>
    <li>{{ trans('menu::menu.breadcrumb.create menu item') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['dashboard.menuitem.store', $menu->id], 'method' => 'post']) !!}
<div class="row">
    <div class="col-md-12">

    </div>
</div>
{!! Form::close() !!}
@stop
