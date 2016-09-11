@extends('layouts.master')

@section('content-header')
    <h1>
        <small>
            <a href="{{ route('admin.workshop.modules.index') }}" data-toggle="tooltip"
               title="" data-original-title="{{ trans('core::core.back') }}">
                <i class="fa fa-reply"></i>
            </a>
        </small>
        {{ $module->name }} <small>{{ trans('workshop::modules.module') }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('user::users.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.workshop.modules.index') }}">{{ trans('workshop::modules.breadcrumb.modules') }}</a></li>
        <li class="active">{{ trans('workshop::modules.viewing module') }} {{ $module->name }}</li>
    </ol>
@stop

@section('styles')
    <style>
        .module-type {
            text-align: center;
        }
        .module-type span {
            display: block;
        }
        .module-type i {
            font-size: 124px;
        }
        form {
            display: inline;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="box-tools pull-right">
                        <?php $status = $module->enabled() ? 'disable' : 'enable'; ?>
                        <button class="btn btn-box-tool jsPublishAssets" data-toggle="tooltip"
                                title="" data-original-title="{{ trans("workshop::modules.publish assets") }}">
                            <i class="fa fa-cloud-upload"></i>
                            {{ trans("workshop::modules.publish assets") }}
                        </button>
                            <?php $routeName = $module->enabled() ? 'disable' : 'enable' ?>
                        {!! Form::open(['route' => ["admin.workshop.modules.$routeName", $module->getName()], 'method' => 'post']) !!}
                            <button class="btn btn-box-tool" data-toggle="tooltip" type="submit"
                                    title="" data-original-title="{{ trans("workshop::modules.{$status}") }}">
                                <i class="fa fa-toggle-{{ $module->enabled() ? 'on' : 'off' }}"></i>
                                {{ trans("workshop::modules.{$status}") }}
                            </button>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 module-details">
                            <div class="module-type pull-left">
                                <i class="fa fa-cube"></i>
                                <span>{{ $module->version }}</span>
                            </div>
                            <h2>{{ ucfirst($module->getName()) }}</h2>
                            <p>{{ $module->getDescription() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($changelog) && count($changelog['versions'])): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-bars"></i> {{ trans('workshop::modules.changelog')}}</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @include('workshop::admin.modules.partials.changelog')
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
@stop

@section('scripts')
    <script>
        $( document ).ready(function() {
            $('.jsPublishAssets').on('click',function (event) {
                event.preventDefault();
                var $self = $(this);
                $self.find('i').toggleClass('fa-cloud-upload fa-refresh fa-spin');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('api.workshop.module.publish', [$module->getName()]) }}',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function() {
                        $self.find('i').toggleClass('fa-cloud-upload fa-refresh fa-spin');
                    }
                });
            });
        });
    </script>
@stop
