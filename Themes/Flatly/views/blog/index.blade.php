@extends('layouts.master')

@section('title')
    Blog posts | @parent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Blog</h1>
            @if (isset($posts))
            <ul>
                @foreach($posts as $post)
                    <li>
                        <span class="date">{{ $post->created_at->format('d-m-Y') }}</span>
                        <h3><a href="{{ URL::route($currentLocale . '.blog.slug', [$post->slug]) }}">{{ $post->title }}</a></h3>
                    </li>
                    <div class="clearfix"></div>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
@endsection
