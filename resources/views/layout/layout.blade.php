<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection('title')
            @yield('title') — {{ setting('nombre', config('cafe.nombre', 'Raíz & Grano')) }}
        @else
            {{ setting('nombre', config('cafe.nombre', 'Raíz & Grano')) }} — Café de Especialidad
        @endif
    </title>
    <meta name="description" content="{{ setting('slogan', config('cafe.slogan')) }} {{ setting('titulo', config('cafe.titulo')) }}">
    @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite('resources/css/app.css')
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            ink: '#0A0704', dark: '#110D08', surface: '#1A120A', card: '#221608',
                            amber: '#C8783A', gold: '#D4A017', cream: '#F0E6D0', muted: '#7A6550', border: '#2E1F10'
                        },
                        fontFamily: {
                            display: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                            body: ['"Outfit"', 'sans-serif']
                        }
                    }
                }
            };
        </script>
    @endif

    <!-- Font Awesome 6 — iconos profesionales -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Google Fonts: Cormorant Garant (display serif) + Outfit (body sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">
</head>

<body>
    @include('layout.components.navbar')
    
    @yield('content')
    
    @include('layout.components.footer')

    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>