@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('menu::menu.titles.edit menu') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="{{ URL::route('dashboard.menu.index') }}">{{ trans('menu::menu.breadcrumb.menu') }}</a></li>
    <li>{{ trans('menu::menu.breadcrumb.edit menu') }}</li>
</ol>
@stop

@section('styles')
<link href="{!! Module::asset('core', 'css/vendor/iCheck/flat/blue.css') !!}" rel="stylesheet" type="text/css" />
<link href="{!! Module::asset('menu', 'css/nestable.css') !!}" rel="stylesheet" type="text/css" />
@stop

@section('content')
{!! Form::open(['route' => ['dashboard.menu.update', $menu->id], 'method' => 'put']) !!}
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                <a href="{{ URL::route('dashboard.menuitem.create', [$menu->id]) }}" class="btn btn-primary btn-flat">
                    <i class="fa fa-pencil"></i> {{ trans('menu::menu.button.create menu item') }}
                </a>
            </div>
        </div>
        <?php if (!$menuItems->isEmpty()): ?>
            <div class="box box-info" style="overflow: hidden;">
                <div class="box-body">
                    <div class="dd">
                        <ol class="dd-list">
                            <?php foreach($menuItems as $menuItem): ?>
                                <li class="dd-item" data-id="{{ $menuItem->id }}">
                                    <a href="" class="btn btn-sm btn-info" style="float: left; margin-right: 15px">Edit</a>
                                    <div class="dd-handle" style="margin-left: 50px;">{{ $menuItem->title }}</div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">{{ trans('core::core.title.translatable fields') }}</h3>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <?php $i = 0; ?>
                        <?php foreach(LaravelLocalization::getSupportedLocales() as $locale => $language): ?>
                            <?php $i++; ?>
                            <li class="{{ App::getLocale() == $locale ? 'active' : '' }}">
                                <a href="#tab_{{ $i }}" data-toggle="tab">{{ trans('core::core.tab.'. strtolower($language['name'])) }}</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content">
                        <?php $i = 0; ?>
                        <?php foreach(LaravelLocalization::getSupportedLocales() as $locale => $language): ?>
                            <?php $i++; ?>
                            <div class="tab-pane {{ App::getLocale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                                @include('menu::admin.menus.partials.edit-trans-fields', ['lang' => $locale])
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">{{ trans('core::core.title.non translatable fields') }}</h3>
            </div>
            <div class="box-body">
                @include('menu::admin.menus.partials.edit-fields')
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
            <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('dashboard.menu.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop

@section('scripts')
<script>
$( document ).ready(function() {
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });

    $('input[type="checkbox"]').on('ifChecked', function(){
      $(this).parent().find('input[type=hidden]').remove();
    });

    $('input[type="checkbox"]').on('ifUnchecked', function(){
      var name = $(this).attr('name'),
          input = '<input type="hidden" name="' + name + '" value="0" />';
      $(this).parent().append(input);
    });
});
</script>
<script src="{!! Module::asset('menu', 'js/jquery.nestable.js') !!}"></script>
<script>
$( document ).ready(function() {
    $('.dd').nestable();
    $('.dd').on('change', function() {
        var data = $('.dd').nestable('serialize');
        console.log(data);
    });
});
</script>
@stop
