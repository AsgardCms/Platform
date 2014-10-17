<!DOCTYPE html>
<html class="bg-black">
<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
        Website
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="{{{ Module::asset('core', 'css/vendor/bootstrap.min.css') }}}" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="{{{ Module::asset('core', 'css/vendor/font-awesome.min.css') }}}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{{ Module::asset('core', 'css/vendor/ionicons.min.css') }}}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{{ Module::asset('core', 'css/AdminLTE.css') }}}" rel="stylesheet" type="text/css" />
    <link href="{{{ Module::asset('core', 'css/vendor/alertify/alertify.core.css') }}}" rel="stylesheet" type="text/css" />
    <link href="{{{ Module::asset('core', 'css/vendor/alertify/alertify.default.css') }}}" rel="stylesheet" type="text/css" />

    <script src="{{{ Module::asset('core', 'js/vendor/jquery.min.js') }}}"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="bg-black">

<div class="form-box" id="login-box">
    @yield('content')
</div>

<!-- Bootstrap -->
<script src="{{{ Module::asset('core', 'js/vendor/bootstrap.min.js') }}}" type="text/javascript"></script>
<script src="{{{ Module::asset('core', 'js/vendor/alertify/alertify.js') }}}" type="text/javascript"></script>
</body>
</html>
