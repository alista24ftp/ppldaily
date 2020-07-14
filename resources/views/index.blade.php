<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">
        <meta name="format-detection" content="email=no">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>ppldaily</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{mix('css/custom.css')}}" rel="stylesheet">
    </head>
    <body>
        <div id="app"></div>
        <script src="{{mix('js/app.js')}}"></script>
        <script src="{{mix('js/custom.js')}}"></script>
    </body>
</html>
