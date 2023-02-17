<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="app-url" content="{{ config('app.url') }}">

    <title>{{ config('app.name', 'Report App') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/font.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link href="{{ asset('css/print.css') }}" rel="stylesheet" crossorigin="anonymous">
</head>

<body>
    <header>
        <div class="left-div">
            <img src="{{ asset('img/nexthop-logo.png') }}" width="50px">
            <img src="{{ asset('img/itp.png') }}" width="140px" class="p-l-10">
        </div>
        <div class="right-div">
            <div>Next Hop IT Service Provider</div>
            <div>Route To Future</div>
            <div>For Your Business</div>
        </div>
    </header>
    <footer>
        <div class="pagenum" style="float: right"></div>
        <div>Building 371, Room 302, Yar Zar Dirit Housing, Botataung Township, Yangon, Myanmar</div>
        <div>Tel: 09-5012101, 09-73129351, 09-5033257, Email : info@thenexthop.net , Website : <a href="https://www.thenexthop.net/">www.thenexthop.net</a></div>
    </footer>
    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        @yield('content')
    </main>
</body>

</html>