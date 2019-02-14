<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TrashGuard') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/tg_black.png') }}" />
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/linearicons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/themify-icons/themify-icons.css') }}">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    <!-- Bootstrap JS -->
    <style type="text/css">
    /*Scroll*/
    ::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #c5c5c5;
    }

    ::-webkit-scrollbar
    {
        width: 3px;
        background-color: #c5c5c5;
    }

    ::-webkit-scrollbar-thumb
    {   
        border-radius: 10px;
        background-color: #f1ca2e;
    }
    </style>
</head>
<body>
    <div id="app">
        
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
    <script>
        const __root_url    = "{{ url('/') }}";
    </script>
    <script src="{{ asset('js/app.js') }}" ></script>
    @yield('scripts')
</body>
</html>
