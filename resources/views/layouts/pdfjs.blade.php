<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="app-url" content="{{ config('app.url') }}">

    <title>{{ config('app.name', 'Report App') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Styles go here */
        .page-header,
        .page-header-space {
            height: 100px;
        }

        .page-footer,
        .page-footer-space {
            height: 50px;
        }

        .page-header {
            display: table-header-group;
            position: fixed;
            top: 0px;
            width: 100%;
        }

        .page-footer {
            display: table-footer-group;
            position: fixed;
            bottom: 0px;
            width: 100%;
        }

        .page {
            page-break-after: always;
        }

        @page {
            margin-top: 100px;
            margin-bottom: 100px;
            margin-left: 50px;
            margin-right: 50px;
        }

        @media print {
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }

        .page-header {
            display: table-header-group;
            position: fixed;
            top: -10px;
            width: 100%;
        }

        .page-footer {
            display: table-footer-group;
            position: fixed;
            bottom: -50px;
            width: 100%;
        }

        button {
            display: none;
        }

        body {
            margin: 0;
        }
        }
    </style>
</head>

<body>
    <div class="page-header" style="text-align: center">
    I'm The Header
    </div>

  
    @yield('content')

    <div class="page-footer">I'm The Footer</div>
</body>

</html>