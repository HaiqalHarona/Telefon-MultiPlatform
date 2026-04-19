<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Telefon' }}</title>

    <!-- Load Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    
    <!-- Main Content Slot -->
    <main>
        {{ $slot }}
    </main>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
