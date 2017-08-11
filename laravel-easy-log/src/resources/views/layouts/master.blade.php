<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel Easy Log') }}</title>
 
        <!-- BS Multiselect-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

        <!-- BS Date Range Picker -->
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
        <link href="{{ asset('vendor/lel/css/app.css') }}" rel="stylesheet">

    </head>
    <body class="site">
        @include('lel::layouts.navigation')

        @if ($flash = session('message'))
        <div class="alert alert-success"> 
            {{$flash}}
        </div>
        @endif
        
        @include('lel::layouts.errors')


        @yield('content')

        @include('lel::layouts.footer') 
    </body>
</html>
