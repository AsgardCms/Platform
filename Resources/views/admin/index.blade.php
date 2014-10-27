@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('media::media.title.media') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><i class="fa fa-camera"></i> {{ trans('media::media.breadcrumb.media') }}</li>
</ol>
@stop

@section('styles')
<style>
.dropzone {
    border: 1px dashed #CCC;
    position: relative;
    min-height: 227px;
    margin-bottom: 20px;
    display: block;
}
.dz-message {
    font-size: 24px;
    color: #CCC;
    text-align: center;
    left: 50%;
    top: 50%;
    width: 260px;
    height: 70px;
    margin: -35px 0 0 -130px;
    position: absolute;
    z-index: 1000;
}
</style>
@stop

@section('content')
<div class="row col-md-12">
    <form action="{{ URL::route('api.media.store')}}" method="POST" class="dropzone" id="my-awesome-dropzone">
        {!! Form::token() !!}
    </form>
</div>

<div class="row">
    <div class="col-md-12">
        @include('flash::message')
        <p>media!</p>
    </div>
</div>
@stop

@section('scripts')
<script src="{!! Module::asset('media', 'js/dropzone.js') !!}"></script>

@stop
