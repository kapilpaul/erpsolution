<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('assets/img/basic/favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'ERP') - ERP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    @stack('header_top_css')
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <style>
        .loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #F5F8FA;
            z-index: 9998;
            text-align: center;
        }

        .plane-container {
            position: absolute;
            top: 50%;
            left: 50%;
        }
    </style>

    @stack('header_bottom_css')

    <!-- Js -->


    <!--
    --- Head Part - Use Jquery anywhere at page.
    --- http://writing.colin-gourlay.com/safely-using-ready-before-including-jquery/
    -->
    <script>(function (w, d, u) {
            w.readyQ = [];
            w.bindReadyQ = [];

            function p(x, y) {
                if (x == "ready") {
                    w.bindReadyQ.push(y);
                } else {
                    w.readyQ.push(x);
                }
            };var a = {ready: p, bind: p};
            w.$ = w.jQuery = function (f) {
                if (f === d || f === u) {
                    return a
                } else {
                    p(f)
                }
            }
        })(window, document)</script>

    @stack('header_js')
</head>
<body class="light">
    <!-- Pre loader -->
    <div id="loader" class="loader">
        <div class="plane-container">
            <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>