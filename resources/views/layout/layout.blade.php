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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
</head>

<body>
    @include('layout.components.navbar')
    
    @yield('content')
    
    @include('layout.components.footer')

</body>

</html>