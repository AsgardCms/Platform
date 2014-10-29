@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('media::media.title.edit media') }} <small>{{ $file->name }}</small>
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="{{ URL::route('dashboard.media.index') }}">{{ trans('media::media.title.media') }}</a></li>
    <li class="active">{{ trans('media::media.title.edit media') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['dashboard.media.update', $file->id], 'method' => 'put']) !!}
<div class="row">
    <div class="col-md-12">
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
                <div class="row">
                    @include('flash::message')
                </div>
                <?php $i = 0; ?>
                <?php foreach(LaravelLocalization::getSupportedLocales() as $locale => $language): ?>
                    <?php $i++; ?>
                    <div class="tab-pane {{ App::getLocale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                        @include('media::admin.partials.edit-fields', [
                            'lang' => $locale
                        ])
                    </div>
                <?php endforeach; ?>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('dashboard.media.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                </div>
            </div>
        </div> {{-- end nav-tabs-custom --}}
    </div>
</div>

{!! Form::close() !!}
@stop
