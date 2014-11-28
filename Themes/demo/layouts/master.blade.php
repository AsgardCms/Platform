<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    @section('meta')
    <meta name="description" content="Nicolas Widart's Personal Portfolio. My name is Nicolas Widart, and I'm a web developper. I love the Laravel framework and new technologies." />
    @show
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
    @section('title')
       Site title
    @show
   </title>
   <link rel="shortcut icon" href="{{ theme_url() }}/favicon.ico }}">

   <?php if (App::environment() == 'development'): ?>
       <link rel="stylesheet" href="{{ theme_url() }}/css/dist/bootstrap.min.css" media="screen">
       <link rel="stylesheet" href="{{ theme_url() }}/css/prism.css">
       <link rel="stylesheet" href="{{ theme_url() }}/css/bootswatch.css">
   <?php else: ?>
       <link rel="stylesheet" href="{{ theme_url() }}/css/dist/all.min.css">
   <?php endif; ?>
</head>
<body>

@include('partials.navigation')

<div class="container">
    @yield('content')
</div>
@include('partials.footer')

<?php if (App::environment() == 'development'): ?>
    <script src="{{ theme_url() }}/js/dist/jquery.min.js"></script>
    <script src="{{ theme_url() }}/js/bootstrap.min.js"></script>
    <script src="{{ theme_url() }}/js/prism.js"></script>
<?php else: ?>
    <script src="{{ theme_url() }}/js/dist/all.min.js"></script>
<?php endif; ?>
@yield('scripts')
</body>
</html>
