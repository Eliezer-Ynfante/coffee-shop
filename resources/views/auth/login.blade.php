@extends('layout.layout')

@section('content')

<section
    class="login-section grain"
    aria-label="Iniciar Sesión"
>
    {{-- Fondo base --}}
    <div class="absolute inset-0 bg-ink"></div>

    {{-- Fondo sólido con degradado cálido del tema (sin imagen pesada externa) --}}
    <div class="absolute inset-0" style="background: linear-gradient(135deg, #0A0704 0%, #1A120A 35%, #2E1F10 65%, rgba(200,120,58,0.18) 100%);"></div>

    {{-- Gradientes en capas --}}
    <div class="absolute inset-0 bg-linear-to-t from-ink via-ink/80 to-ink/60"></div>
    <div class="absolute inset-0 bg-radial from-amber/5 via-transparent to-transparent pointer-events-none"></div>

    {{-- Glow decorativo ámbar --}}
    <div class="absolute w-96 h-96 bg-amber/8 rounded-full blur-3xl pointer-events-none -top-20 -left-20" aria-hidden="true"></div>
    <div class="absolute w-96 h-96 bg-amber/6 rounded-full blur-3xl pointer-events-none -bottom-20 -right-20" aria-hidden="true"></div>

    {{-- Tarjeta Principal de Login --}}
    <div class="login-card p-8 sm:p-11 reveal">
        {{-- Cabecera de la tarjeta --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-full border border-amber/50 bg-amber/10 flex items-center justify-center mx-auto mb-4 text-amber text-xl shadow-lg">
                <i class="fa-solid fa-mug-hot" aria-hidden="true"></i>
            </div>
            
            <p class="amber-tag justify-center mb-2">Portal de Acceso</p>
            <h1 class="font-display text-3xl sm:text-4xl font-bold text-cream">
                Iniciar <em class="text-amber not-italic">Sesión</em>
            </h1>
            <p class="text-muted text-xs sm:text-sm mt-2 leading-relaxed max-w-xs mx-auto">
                Ingresa tus credenciales para acceder al panel
            </p>
        </div>

        {{-- Alertas de Estado o Errores --}}
        @if (session('status'))
        <div class="mb-6 p-3.5 rounded-lg bg-amber/15 border border-amber/40 text-cream text-xs flex items-center gap-2.5">
            <i class="fa-solid fa-circle-info text-amber"></i>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        @if (isset($errors) && $errors->any())
        <div class="mb-6 p-3.5 rounded-lg bg-red-950/60 border border-red-500/40 text-red-200 text-xs space-y-1">
            <div class="flex items-center gap-2 font-medium text-red-400 mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>No fue posible iniciar sesión:</span>
            </div>
            @foreach ($errors->all() as $error)
            <p class="pl-5 text-[11px]">• {{ $error }}</p>
            @endforeach
        </div>
        @endif

        {{-- Formulario de Login --}}
        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Campo: Correo Electrónico --}}
            <div>
                <label class="f-label" for="login-email">
                    <i class="fa-regular fa-envelope mr-1.5" aria-hidden="true"></i> Correo Electrónico
                </label>
                <div class="relative">
                    <input
                        id="login-email"
                        type="email"
                        name="email"
                        class="f-input"
                        placeholder="test@example.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                    >
                </div>
            </div>

            {{-- Campo: Contraseña con Toggle Mostrar/Ocultar --}}
            <div>
                <label class="f-label" for="login-password">
                    <i class="fa-solid fa-lock mr-1.5" aria-hidden="true"></i> Contraseña
                </label>
                <div class="relative">
                    <input
                        id="login-password"
                        type="password"
                        name="password"
                        class="f-input pr-10"
                        placeholder="••••••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <button
                        id="toggle-password"
                        type="button"
                        class="password-toggle-btn"
                        aria-label="Mostrar u ocultar contraseña"
                        title="Mostrar u ocultar contraseña"
                    >
                        <i id="toggle-password-icon" class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            {{-- Opciones: Recordar sesión y Ayuda --}}
            <div class="flex items-center justify-between gap-2 pt-1 text-xs">
                <label class="flex items-center gap-2 cursor-pointer select-none text-cream/75 hover:text-cream transition">
                    <input
                        type="checkbox"
                        name="remember"
                        class="custom-checkbox"
                    >
                    <span>Recordar sesión</span>
                </label>

                <a
                    href="{{ route('contacto') }}"
                    class="text-amber hover:text-gold transition font-medium hover:underline"
                    title="Contáctanos si necesitas asistencia"
                >
                    ¿Necesitas ayuda?
                </a>
            </div>

            {{-- Botón de Ingreso --}}
            <button
                type="submit"
                class="btn-amber w-full py-3.5 text-sm font-semibold tracking-wide shadow-xl flex items-center justify-center gap-2"
            >
                <i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i>
                <span>Ingresar al Portal</span>
            </button>
        </form>

        {{-- Nota de Seguridad y Privacidad --}}
        <div class="mt-8 pt-6 border-t border-border/80 text-center">
            <p class="text-[11px] text-muted flex items-center justify-center gap-1.5 leading-relaxed">
                <i class="fa-solid fa-shield-halved text-amber text-xs" aria-hidden="true"></i>
                <span>Acceso seguro</span>
            </p>
        </div>
    </div>
</section>

@endsection
