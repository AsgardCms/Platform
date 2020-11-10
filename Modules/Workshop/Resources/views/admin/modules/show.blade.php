@extends('layouts.master')

@section('content-header')
    <h1>
        <small>
            <a href="{{ route('admin.workshop.modules.index') }}" data-toggle="tooltip"
               title="" data-original-title="{{ trans('core::core.back') }}">
                <i class="fa fa-reply"></i>
            </a>
        </small>
        {{ $module->getName() }} <small>{{ trans('workshop::modules.module') }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('user::users.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.workshop.modules.index') }}">{{ trans('workshop::modules.breadcrumb.modules') }}</a></li>
        <li class="active">{{ trans('workshop::modules.viewing module') }} {{ $module->getName() }}</li>
    </ol>
@stop

@push('css-stack')
    <style>
        .module-title-wrap {
            display: flex;
            align-items: center;
        }
        .module-type {
            text-align: center;
            margin-right: 10px;
        }
        .module-type span {
            display: block;
        }
        .module-type i {
            font-size: 124px;
        }
        .module-title {
            margin: 0;
        }
        form {
            display: inline;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="box-tools pull-right">
                        <?php $status = $module->isEnabled() ? 'disable' : 'enable'; ?>
                        <button class="btn btn-box-tool jsPublishAssets" data-toggle="tooltip"
                                title="" data-original-title="{{ trans("workshop::modules.publish assets") }}">
                            <i class="fa fa-cloud-upload"></i>
                            {{ trans("workshop::modules.publish assets") }}
                        </button>
                            <?php $routeName = $module->isEnabled() ? 'disable' : 'enable' ?>
                        {!! Form::open(['route' => ["admin.workshop.modules.$routeName", $module->getName()], 'method' => 'post']) !!}
                            <button class="btn btn-box-tool" data-toggle="tooltip" type="submit"
                                    title="" data-original-title="{{ trans("workshop::modules.{$status}") }}">
                                <i class="fa fa-toggle-{{ $module->isEnabled() ? 'on' : 'off' }}"></i>
                                {{ trans("workshop::modules.{$status}") }}
                            </button>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 module-details">
                            <div class="module-title-wrap">
                                <div class="module-type">
                                    <i class="fa fa-cube"></i>
                                    <span>{{ module_version($module) }}</span>
                                </div>
                                <h2 class="module-title">{{ ucfirst($module->getName()) }}</h2>
                            </div>
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

@push('js-stack')
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
@endpush
