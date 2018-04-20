<!doctype html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title>{{ config('app.name') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Online Attendance System" name="description"/>
    <meta content="Sourav Das - Tict Bca1518" name="author"/>
    <link rel="icon" href="{{ asset("/img/Tict_logo.png") }}">

    <link href="{{ asset("css/loader.css") }}" rel="stylesheet">
    <div class="loader" id="loader">Loading...</div>
    <script src="{{ asset("js/loader.js") }}" type="text/javascript"></script>


    <link rel="stylesheet" href="{{ asset("/assets/materialize/css/materialize.min.css") }}" media="screen,projection"/>

    <link href="{{ asset("/assets/css/bootstrap.css") }}" rel="stylesheet"/>


    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("css/font-awesome.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/css/animate.css") }}">
    <!-- Custom Styles-->
    <link href="{{ asset("/assets/js/dataTables/dataTables.bootstrap.css") }}" rel="stylesheet"/>

    <link href="{{ asset("/assets/css/custom-styles.css") }}" rel="stylesheet"/>
    <link href="{{ asset("css/table.css") }}" rel="stylesheet">


    @yield('style')
</head>
<body>
<div id="page-body" style="display:none;height:100%;">
    @yield('body')
</div>
<script src="{{ asset("js/jquery-3.2.1.min.js") }}" type="text/javascript"></script>
<script>
    function loding_click_event_function(event) {
        var href = this.href;
        if (href !== undefined) {
            if (href.indexOf('#') < 0 && href.length > 0) MY_click_load_wait();
            window.location = href;
        }
    }

    //$(window).bind("beforeunload", loding_click_event_function);
    $('[href]').click(loding_click_event_function);
</script>

<!-- Bootstrap Js -->
<script src="{{ asset("/js/bootstrap.min.js") }}"></script>
<script src="{{ asset("/assets/materialize/js/materialize.min.js") }}"></script>
<script src="{{ asset("/assets/js/jquery.metisMenu.js") }}"></script>

<script src="{{ asset("/assets/js/dataTables/jquery.dataTables.js") }}"></script>
<script src="{{ asset("/assets/js/dataTables/dataTables.bootstrap.js") }}"></script>

<!-- Custom Js -->
<script src="{{ asset("/assets/js/custom-scripts.js") }}"></script>
<script src="{{ asset("js/myJS.js") }}" type="text/javascript"></script>
@yield('script')
</body>
</html>