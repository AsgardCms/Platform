@extends('core::layouts.master')

@section('content-header')
<h1>
    Module management
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Modules</li>
</ol>
@stop

@section('styles')
<link href="{{{ core_asset('css/vendor/iCheck/flat/blue.css') }}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
{!! Form::open(['route' => 'dashboard.user.store', 'method' => 'post']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">Module list</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                    <ul>
                        @foreach($modules as $module)
                            <li>
                                <div class="checkbox">
                                    <label for="{{ $module }}">
                                        <input id="{{ $module }}" name="modules[]" type="checkbox" class="flat-blue" <?php echo Module::active($module) ? 'checked' : '' ?> value="true" /> {{ $module }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
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
});
</script>
@stop