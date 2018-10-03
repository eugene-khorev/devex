<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <script src="{{ asset('js/app.js') }}" defer></script>
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <autocomplete-form :lang="{{ json_encode(trans('app.form')) }}"></autocomplete-form>
        </div>
    </body>
</html>
