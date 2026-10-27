<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- app icon --}}
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon">
<<<<<<< HEAD
        <title>{{ config('app.name', 'Campus Complaint Management System') }}</title>
=======
        <title>{{ config('app.name', 'Complaint Management System') }}</title>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
