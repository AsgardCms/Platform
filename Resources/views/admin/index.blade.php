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
<link href="{!! Module::asset('media', 'css/dropzone.css') !!}" rel="stylesheet" type="text/css" />
<style>
.dropzone {
    border: 1px dashed #CCC;
    min-height: 227px;
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
<script>
$( document ).ready(function() {
    $(".dropzone").dropzone({
        url: $(this).attr('action')
    });
});
</script>
@stop
