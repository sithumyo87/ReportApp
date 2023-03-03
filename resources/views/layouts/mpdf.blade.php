<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report App</title>
    <style>
        @font-face {
            font-family: 'Nunito';
            src: url({{ storage_path("fonts/nunito/Nunito.ttf") }});
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
        }

        @font-face {
            font-family: 'Pyidaungsu';
            src: url({{ storage_path("fonts/pyidaungsu/Pyidaungsu.ttf") }});
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
        }

        body {
            font-family: 'Pyidaungsu', 'Nunito' !important;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        table{
           border-collapse: collapse;
           width: 100% !important;
           font-size: 14px;
           page-break-inside: auto !important;
           overflow: wrap;
        }

        tbody, thead, tr{
            page-break-inside: auto !important;
           break-inside: auto !important;
           overflow: wrap;
        }

        .header{
            display: block;
            width: 100%;
            color: #483D8B;
            font-weight: bold;
            font-size: 13px;
        }
        .right-div{
            float:right;
            width: 50%;
            text-align: right;
        }
        .left-div{
            float:left;
            width: 50%;
        }

        .footer{
            font-size: 12px;
            color: #483D8B;
        }
        .footer-link{
            text-decoration: none;
        }

        .flex-item1{
            width: 35%;
            float: right;
        }
        .flex-item2{
            flex-grow: 1;
            width: 65%;
        }

        table{
            border-collapse: collapse;
            width:100% !important;
            overflow: wrap;
        }

        .table-bordered{
            margin-top: 10px;
        }

        .table-bordered thead tr th, .table-bordered tbody tr td {
            border: 1px solid #333333;
            padding: 5px 10px;
            font-size: 13px;
            vertical-align: middle;
            margin: 0px !important;
        }

        .table-bordered thead tr th {
            text-align: center;
        }

        .text-right{
            text-align: right !important;
        }

        .note_and_sign{
            display: flex;
            width: 100%;
        }

        .note_and_sign .note{
            width: 70%;
            float: left;
            margin-top: 10px;
        }

        .note_and_sign .sign{
            width: 20%;
            float: right;
            font-size: 13px;
        }

        .note-title{
            font-size: 13px !important;
        }

        .note-body{
            font-size: 12px;
            color: #333333;
        }

        .bank-info-title{
            font-size: 13px;
            font-weight: 800;
        }

        .bank-info-body{
            font-size: 12px;
        }

        .bank-info-body tr td:first-child{
            font-weight: 800;
        }

        .deliver_receiver{
            font-size: 13px;
            display: flex;
            width: 100%;
            padding-top: 20px;
        }

        .deliver{
            width: 50%;
            float: left;
            text-align: center;
        }

        .receiver{
            width: 50%;
            float: right;
            text-align: center;
        }

        #authorizer-img{
            width: 100px;
            height: 100px;
        }

    </style>
</head>

<body>
    <htmlpageheader name="page-header">
        <div class="header">
            <div class="left-div">
                <img src="{{ public_path('img/nexthop-logo.png') }}" width="50px">
                <img src="{{ public_path('img/itp.png') }}" width="140px" class="p-l-10">
            </div>
            <div class="right-div text-right">
                <div>Next Hop IT Service Provider</div>
                <div>Route To Future</div>
                <div>For Your Business</div>
            </div>
        </div>
    </htmlpageheader>

    <htmlpagefooter name="page-footer">
        <div class="footer">
            <div class="pagenum" style="float: right"></div>
            <div>Building 371, Room 302, Yar Zar Dirit Housing, Botataung Township, Yangon, Myanmar</div>
            <div>Tel: 09-5012101, 09-73129351, 09-5033257, Email : info@thenexthop.net , Website : 
                <a href="https://www.thenexthop.net/" class="footer-link">www.thenexthop.net</a>
            </div>
        </div>
    </htmlpagefooter>
    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        @yield('content')
    </main>
</body>

</html>