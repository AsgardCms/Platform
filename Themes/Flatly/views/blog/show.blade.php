@extends('layouts.master')

@section('title')
    {{ $post->title }} | @parent
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
        <span class="linkBack">
            <a href="{{ URL::route($currentLocale . '.blog') }}"><i class="glyphicon glyphicon-chevron-left"></i> Back to post list</a>
        </span>
        <h1>{{ $post->title }}</h1>
        <span class="date">{{ $post->created_at->format('d-m-Y') }}</span>

        {!! $post->content !!}

            <p>
                <?php if ($previous = $post->present()->previous): ?>
                    <a href="{{ route(locale() . '.blog.slug', [$previous->slug]) }}">Previous</a>
                <?php endif; ?>
                <?php if ($next = $post->present()->next): ?>
                    <a href="{{ route(locale() . '.blog.slug', [$next->slug]) }}">Next</a>
                <?php endif; ?>
            </p>
        </div>
    </div>
@stop
