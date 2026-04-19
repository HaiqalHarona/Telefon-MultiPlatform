<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth - SanCo' }}</title>

    <!-- Load Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased text-gray-100 bg-[#18181b]">
    
    {{ $slot }}

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Global Notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                window.notyf.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                window.notyf.error("{{ session('error') }}");
            @endif
        });
    </script>
</body>
</html>
