<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Roboto', sans-serif; }
            h1, h2, h3, h4, h5, h6 { font-family: 'Montserrat', sans-serif; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background-color: #1A1D24;">
            <div>
                <a href="/">
                    <img src="{{ asset('img/LOGO AlberoPerezTech.png') }}" alt="Logo" class="w-20 h-20 fill-current text-gray-500" style="width: 100px; height: auto;" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg" style="background-color: #242833; border: 1px solid #3A4150;">
                {{ $slot }}
            </div>
            
            <div class="mt-4 text-center">
                 <a href="/" style="color: #9BA3B0; text-decoration: none; font-size: 0.9em;">&larr; Tornar a la botiga</a>
            </div>
        </div>
    </body>
</html>
