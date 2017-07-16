@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('user::users.api-keys') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('user::users.api-keys') }}</li>
    </ol>

@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.account.api.create') }}" class="btn btn-primary btn-flat">
                        <i class="fa fa-plus"></i> {{ trans('user::users.generate new api key') }}
                    </a>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('user::users.your api keys') }}</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-4">
                        <?php if ($tokens->isEmpty() === false): ?>
                            <ul class="list-unstyled">
                                <?php foreach ($tokens as $token): ?>
                                    <li style="margin-bottom: 20px;">
                                        {!! Form::open(['route' => ['admin.account.api.destroy', $token->id], 'method' => 'delete', 'class' => '']) !!}
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-danger btn-flat" onclick="return confirm('{{ trans('user::users.delete api key confirm') }}')">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </button>
                                            </span>
                                            <input type="text" class="form-control api-key" readonly value="{{ $token->access_token }}" >
                                            <span class="input-group-btn">
                                                <a href="#" class="btn btn-default btn-flat jsClipboardButton">
                                                    <i class="fa fa-clipboard" aria-hidden="true"></i>
                                                </a>
                                            </span>
                                        </div>
                                        {!! Form::close() !!}
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>{{ trans('user::users.you have no api keys') }} <a href="{{ route('admin.account.api.create') }}">{{ trans('user::users.generate one') }}</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col (MAIN) -->
    </div>

    @include('core::partials.delete-modal')
@stop

@push('js-stack')
    <script>
        new Clipboard('.jsClipboardButton', {
            target: function(trigger) {
                return $(trigger).parent().parent().find('.api-key')[0];
            }
        });
    </script>
@endpush
