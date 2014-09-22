<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
        Admin
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="{{{ core_asset('css/vendor/bootstrap.min.css') }}}" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="{{{ core_asset('css/vendor/font-awesome.min.css') }}}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{{ core_asset('css/vendor/ionicons.min.css') }}}" rel="stylesheet" type="text/css" />
    <link href="{{{ core_asset('css/vendor/alertify/alertify.core.css') }}}" rel="stylesheet" type="text/css" />
    <link href="{{{ core_asset('css/vendor/alertify/alertify.default.css') }}}" rel="stylesheet" type="text/css" />
    <link href="{{{ core_asset('css/vendor/iCheck/minimal/blue.css') }}}" rel="stylesheet" type="text/css" />
    <link href="{{{ core_asset('css/vendor/datatables/dataTables.bootstrap.css') }}}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{{ core_asset('css/AdminLTE.css') }}}" rel="stylesheet" type="text/css" />
    <script src="{{{ core_asset('js/vendor/jquery.min.js') }}}"></script>
    @section('styles')
    @show

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-blue">
<header class="header">
    <a href="#" class="logo">
        @section('title')
        SocialDashy
        @show
    </a>
    @include('core::partials.top-nav')
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
    @include('core::partials.sidebar-nav')

    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('content-header')
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<script src="{{{ core_asset('js/vendor/bootstrap.min.js') }}}" type="text/javascript"></script>
<script src="{{{ core_asset('js/vendor/alertify/alertify.js') }}}" type="text/javascript"></script>
<script src="{{{ core_asset('js/vendor/iCheck/icheck.min.js') }}}" type="text/javascript"></script>
<script src="{{{ core_asset('js/vendor/datatables/jquery.dataTables.js') }}}" type="text/javascript"></script>
<script src="{{{ core_asset('js/vendor/datatables/dataTables.bootstrap.js') }}}" type="text/javascript"></script>
<script src="{{{ core_asset('js/app.js') }}}" type="text/javascript"></script>
@section('scripts')
@show
</body>
</html>