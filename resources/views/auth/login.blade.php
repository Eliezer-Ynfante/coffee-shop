@extends('layout.layout')

@section('content')
<section class="min-h-[60vh] flex items-center justify-center pt-24 px-6">
    <div class="bg-card border border-border p-8 rounded-lg w-full max-w-md text-center">
        <h1 class="font-display text-3xl font-bold text-amber mb-6">Iniciar Sesión</h1>
        <form class="space-y-4">
            <input type="email" placeholder="Correo electrónico" class="w-full bg-transparent border-b border-border text-cream py-2 outline-none focus:border-amber transition">
            <input type="password" placeholder="Contraseña" class="w-full bg-transparent border-b border-border text-cream py-2 outline-none focus:border-amber transition mb-4">
            <button type="button" class="btn-amber w-full mt-4">Ingresar</button>
        </form>
    </div>
</section>
@endsection