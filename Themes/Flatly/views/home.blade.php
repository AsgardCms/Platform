@extends('layouts.master')

@section('title')
    {{ $page->title }} | @parent
@endsection

@section('meta')
    <meta name="title" content="{{ $page->meta_title}}" />
    <meta name="description" content="{{ $page->meta_description }}" />
@endsection

@section('content')
    <div class="row">
        <h1>{{ $page->title }}</h1>
        {!! $page->body !!}
    </div>
@endsection
