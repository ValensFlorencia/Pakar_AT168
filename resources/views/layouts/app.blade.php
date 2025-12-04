<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Custom Global CSS -->
    <style>
        /* Global font untuk semua elemen */
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        /* Override untuk input */
        input {
            font-family: inherit !important;
            font-size: 15px !important;
            background: #fffdf7 !important;
            border: 2px solid #ffe4b3 !important;
            border-radius: 12px !important;
            padding: 15px !important;
        }

        /* Override untuk textarea (yang sebelumnya bermasalah) */
        textarea {
            font-family: inherit !important;
            font-size: 15px !important;
            line-height: 1.5 !important;
            background: #fffdf7 !important;
            border: 2px solid #ffe4b3 !important;
            border-radius: 12px !important;
            padding: 15px !important;
            resize: vertical;
        }

        textarea:focus,
        input:focus {
            outline: none !important;
            border-color: #ffd93d !important;
            background: white !important;
            box-shadow: 0 0 0 3px rgba(255,217,61,0.15) !important;
        }
    </style>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
