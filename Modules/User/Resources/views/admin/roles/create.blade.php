@extends('layouts.master')

@section('content-header')
@stop

@section('content')
@stop
@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('user::roles.navigation.back to index') }}</dd>
    </dl>
@stop

@push('js-stack')
@endpush
