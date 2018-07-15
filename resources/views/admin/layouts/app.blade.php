<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Admin interface of BEAM-Messenger</title>

    <link href="{{ asset("css/admin.css ") }}" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        @include('admin.layouts.navbar');

        @include('admin.layouts.left_sidebar');

        <div class="content-wrapper">
            <section class="content-header">
                @yield('content_header')
            </section>

            <section class="content">
                @yield('content')
            </section>
        </div>

        <aside class="control-sidebar control-sidebar-dark">
            @include('admin.layouts.right_sidebar')
        </aside>
        <div class="control-sidebar-bg"></div>
    </div>

    <script src="{{ asset("js/admin.js ") }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
        crossorigin="anonymous"></script>
</body>

</html>