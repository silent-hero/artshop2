<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/animate.css_3.5.2.css')}}">
        <link rel="stylesheet" href="{{asset('css/slick.css')}}">
        <link rel="stylesheet" href="{{asset('css/slick-theme.css')}}">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">

        <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
        <script src="{{asset('js/tether_1.4.0.min.js')}}"></script>
        <script src="{{asset('js/bootstrap_4.0.0-alpha.6.min.js')}}"></script>
        <script src="{{asset('js/slick.min.js')}}"></script>
        <script src="{{asset('js/fontawesome_v5.0.6.js')}}"></script>
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        <script src="{{asset('js/app.js')}}"></script>
        <title>{{config("app.name", "Artshop")}}</title>
    </head>
    <body>

        <div class="content intro-wrapper">
            @yield("content")
        </div>

    <!-- Scripts -->
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        if($(".ckeditor").length)
        {
            CKEDITOR.replaceClass = 'ckeditor';
        }
    </script>

    </body>
</html>