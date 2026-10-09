@extends('layout.layout')

@section('content')

{{-- ================================================================
     HERO — Cabecera interior de la página Reservas
     ================================================================ --}}
<x-hero-section
    heroClass="reserva-hero"
    subtag="Tu Espacio Reservado"
    title="Reserva tu "
    highlight="Experiencia"
    description="Explora nuestra maqueta 3D interactiva, elige tu mesa favorita y asegura tu lugar en nuestra cafetería de especialidad."
/>


{{-- ================================================================
     MAQUETA ARQUITECTÓNICA 3D — Simulación Recta del Local
     ================================================================ --}}
<section class="bg-surface py-20 px-6 border-b border-border relative overflow-hidden grain" aria-label="Plano arquitectónico de la cafetería">
    {{-- Glow ambiental --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto relative z-10">

        {{-- Encabezado de la Maqueta --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 reveal">
            <div>
                <p class="amber-tag mb-2">Simulación de la Cafetería</p>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-cream">
                    Plano Arquitectónico <em class="text-amber not-italic">3D</em>
                </h2>
                <p class="text-muted text-xs sm:text-sm mt-1">
                    Visualiza la distribución real del local y haz clic sobre cualquier mesa para seleccionarla.
                </p>
            </div>

            {{-- Controles de Cámara y Leyenda --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Botones de Cámara Rectos --}}
                <div class="flex items-center gap-1.5 bg-card/80 p-1.5 rounded-full border border-border">
                    <button type="button" class="cam-btn active" data-view="3d-straight" title="Vista 3D Frontal Recta">
                        <i class="fa-solid fa-cube" aria-hidden="true"></i> Vista 3D Recta
                    </button>
                    <button type="button" class="cam-btn" data-view="2d-top" title="Plano 2D Superior">
                        <i class="fa-solid fa-layer-group" aria-hidden="true"></i> Plano Cenital (2D)
                    </button>
                </div>

                {{-- Leyenda de Estados --}}
                <div class="hidden sm:flex items-center gap-3 text-xs text-muted bg-card/60 px-4 py-2 rounded-full border border-border">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Disponible
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber"></span> Tu Selección
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500/50"></span> Ocupada
                    </span>
                </div>
            </div>
        </div>

        {{-- Escenario Arquitectónico 3D Recto --}}
        <div class="floorplan-stage reveal">
            <div id="floorplan-mesh" class="floorplan-mesh view-3d-straight" data-availability-url="{{ route('reserva.availability') }}">
                
                {{-- Base del Suelo Principal (Microcemento / Parquet Oscuro) --}}
                <div class="floor-base"></div>

                @foreach ($mesas3d as $mesa)
                <button
                    type="button"
                    class="table-3d reservation-table occupied"
                    data-table-id="{{ $mesa['id'] }}"
                    data-table-name="{{ $mesa['nombre'] }}"
                    data-zone="{{ $mesa['zona'] }}"
                    data-zone-name="{{ $mesa['zona_nombre'] }}"
                    data-capacity="{{ $mesa['capacidad'] }}"
                    data-state="{{ $mesa['estado'] }}"
                    data-available="false"
                    aria-label="{{ $mesa['nombre'] }}, {{ $mesa['capacidad'] }} personas"
                    aria-disabled="true"
                    style="left: {{ $mesa['x'] }}%; top: {{ $mesa['y'] }}%; width: 58px; height: 58px;"
                >
                    <i class="{{ $mesa['icono'] }} text-amber" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold text-cream">{{ $mesa['id'] }}</span>
                    <span class="text-[8px] text-muted">{{ $mesa['capacidad'] }} {{ $mesa['capacidad'] === 1 ? 'persona' : 'personas' }}</span>
                    <span class="table-tooltip">{{ $mesa['nombre'] }} · {{ $mesa['zona_nombre'] }}</span>
                </button>
                @endforeach

                @if (empty($mesas3d))
                <div class="absolute inset-0 z-20 flex items-center justify-center text-center text-sm text-cream">
                    No hay mesas activas configuradas para reservas.
                </div>
                @endif

                {{-- Alfombra tejida decorativa bajo el Salón Principal --}}
                <div class="dining-carpet" style="left: 4%; top: 40%; width: 49%; height: 48%;"></div>

                {{-- Suelo Deck de Madera para la Terraza Jardín --}}
                <div class="terrace-deck-floor" style="left: 55%; top: 4%; width: 42%; height: 92%;"></div>

                {{-- Muro Divisorio Interior / Exterior con ventanales y luz ámbar --}}
                <div class="absolute" style="left: 53.5%; top: 4%; width: 5px; height: 92%; background: linear-gradient(180deg, rgba(200,120,58,0.4), rgba(200,120,58,0.15)); border-radius: 2px; box-shadow: 0 0 12px rgba(0,0,0,0.9);"></div>

                {{-- ☕ 1. BARRA DE BARISMO, ESPRESSO & VITRINA DE REPOSTERÍA (Mostrador Superior) --}}
                <div class="bar-counter-3d" style="left: 4%; top: 5%; width: 48%; height: 60px;">
                    <div class="flex items-center gap-2">
                        <div class="espresso-machine-mini">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span class="font-mono font-bold">ESPRESSO 2-GROUP</span>
                        </div>
                        <div class="pastry-showcase hidden sm:flex">
                            <i class="fa-solid fa-cookie-bite text-gold"></i>
                            <span>Vitrina Postres</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-muted hidden md:inline"><i class="fa-solid fa-flask text-amber"></i> V60 Bar</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-amber">Barra Principal</span>
                    </div>
                </div>

                {{-- Taburetes de la Barra con Sillas Acolchadas (B1, B2, B3, B4) --}}
                @foreach (['B1' => 7, 'B2' => 19, 'B3' => 31, 'B4' => 43] as $tabId => $leftPos)
                @php
                    $isOcc = $tabId === 'B4';
                    $isSel = false;
                @endphp
                <div
                    class="table-3d {{ $isOcc ? 'occupied' : '' }} {{ $isSel ? 'selected' : '' }}"
                    data-table-id="{{ $tabId }}"
                    data-table-name="Taburete Barra {{ $tabId }}"
                    data-zone="barra"
                    data-zone-name="Barra de Especialidad"
                    data-capacity="1"
                    data-state="{{ $isOcc ? 'ocupada' : 'disponible' }}"
                    @style(["left: {$leftPos}%; top: 20%; width: 38px; height: 38px; border-radius: 50%;"])
                >
                    <i class="fa-solid fa-chair text-[11px] text-amber mb-0.5" aria-hidden="true"></i>
                    <span class="text-[8px] font-bold text-cream font-mono">{{ $tabId }}</span>
                    <div class="table-tooltip">
                        <span class="font-semibold text-cream block">Taburete Barra {{ $tabId }}</span>
                        <span class="text-amber text-[10px] block">Capacidad: 1 persona · Frente al Barista</span>
                        <span class="text-xs {{ $isOcc ? 'text-red-400' : 'text-emerald-400' }} block text-[9px] font-medium uppercase mt-0.5">
                            {{ $isOcc ? 'Ocupada' : 'Disponible para reservar' }}
                        </span>
                    </div>
                </div>
                @endforeach


                {{-- 🛋️ 2. SALÓN PRINCIPAL (Mesa S1, S2, S3, S5 y Sofá Lounge S4) --}}
                <div class="absolute text-[10px] font-bold uppercase tracking-widest text-muted flex items-center gap-1.5" style="left: 6%; top: 38%;">
                    <i class="fa-solid fa-couch text-amber"></i> Salón Principal
                </div>

                {{-- Mesa S1 (4 Personas) con 4 Sillas Reales --}}
                <div class="table-set-wrapper" style="left: 6%; top: 45%; width: 68px; height: 56px;">
                    <div class="chair-dot" style="top: -10px; left: 50%; transform: translateX(-50%);"></div>
                    <div class="chair-dot" style="bottom: -10px; left: 50%; transform: translateX(-50%);"></div>
                    <div class="chair-dot" style="left: -10px; top: 50%; transform: translateY(-50%);"></div>
                    <div class="chair-dot" style="right: -10px; top: 50%; transform: translateY(-50%);"></div>
                    <div
                        class="table-3d selected w-full h-full"
                        data-table-id="S1"
                        data-table-name="Mesa Central S1"
                        data-zone="salon"
                        data-zone-name="Salón Principal"
                        data-capacity="4"
                        data-state="disponible"
                    >
                        <i class="fa-solid fa-utensils text-xs text-amber mb-0.5" aria-hidden="true"></i>
                        <span class="text-[10px] font-bold text-cream font-mono">S1</span>
                        <span class="text-[8px] text-muted">4 personas</span>
                        <div class="table-tooltip">
                            <span class="font-semibold text-cream block">Mesa Central S1</span>
                            <span class="text-amber text-[10px] block">Capacidad: 4 personas · Salón Principal</span>
                            <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                        </div>
                    </div>
                </div>

                {{-- Mesa S2 (4 Personas) con 4 Sillas Reales --}}
                <div class="table-set-wrapper" style="left: 20%; top: 45%; width: 68px; height: 56px;">
                    <div class="chair-dot" style="top: -10px; left: 50%; transform: translateX(-50%);"></div>
                    <div class="chair-dot" style="bottom: -10px; left: 50%; transform: translateX(-50%);"></div>
                    <div class="chair-dot" style="left: -10px; top: 50%; transform: translateY(-50%);"></div>
                    <div class="chair-dot" style="right: -10px; top: 50%; transform: translateY(-50%);"></div>
                    <div
                        class="table-3d w-full h-full"
                        data-table-id="S2"
                        data-table-name="Mesa Central S2"
                        data-zone="salon"
                        data-zone-name="Salón Principal"
                        data-capacity="4"
                        data-state="disponible"
                    >
                        <i class="fa-solid fa-utensils text-xs text-amber mb-0.5" aria-hidden="true"></i>
                        <span class="text-[10px] font-bold text-cream font-mono">S2</span>
                        <span class="text-[8px] text-muted">4 personas</span>
                        <div class="table-tooltip">
                            <span class="font-semibold text-cream block">Mesa Central S2</span>
                            <span class="text-amber text-[10px] block">Capacidad: 4 personas · Salón Principal</span>
                            <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                        </div>
                    </div>
                </div>

                {{-- Mesa S3 (2 Personas - Íntima Bistro) con 2 Sillas --}}
                <div class="table-set-wrapper" style="left: 6%; top: 67%; width: 54px; height: 48px;">
                    <div class="chair-dot" style="left: -9px; top: 50%; transform: translateY(-50%);"></div>
                    <div class="chair-dot" style="right: -9px; top: 50%; transform: translateY(-50%);"></div>
                    <div
                        class="table-3d w-full h-full"
                        data-table-id="S3"
                        data-table-name="Mesa Ventanal S3"
                        data-zone="salon"
                        data-zone-name="Salón Principal"
                        data-capacity="2"
                        data-state="disponible"
                    >
                        <i class="fa-solid fa-wine-glass text-xs text-amber mb-0.5" aria-hidden="true"></i>
                        <span class="text-[10px] font-bold text-cream font-mono">S3</span>
                        <span class="text-[8px] text-muted">2 personas</span>
                        <div class="table-tooltip">
                            <span class="font-semibold text-cream block">Mesa Ventanal S3</span>
                            <span class="text-amber text-[10px] block">Capacidad: 2 personas · Salón Principal</span>
                            <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                        </div>
                    </div>
                </div>

                {{-- Mesa S5 (2 Personas - Café Bistro) con 2 Sillas --}}
                <div class="table-set-wrapper" style="left: 20%; top: 67%; width: 54px; height: 48px;">
                    <div class="chair-dot" style="left: -9px; top: 50%; transform: translateY(-50%);"></div>
                    <div class="chair-dot" style="right: -9px; top: 50%; transform: translateY(-50%);"></div>
                    <div
                        class="table-3d w-full h-full"
                        data-table-id="S5"
                        data-table-name="Mesa Bistro S5"
                        data-zone="salon"
                        data-zone-name="Salón Principal"
                        data-capacity="2"
                        data-state="disponible"
                    >
                        <i class="fa-solid fa-mug-hot text-xs text-amber mb-0.5" aria-hidden="true"></i>
                        <span class="text-[10px] font-bold text-cream font-mono">S5</span>
                        <span class="text-[8px] text-muted">2 personas</span>
                        <div class="table-tooltip">
                            <span class="font-semibold text-cream block">Mesa Bistro S5</span>
                            <span class="text-amber text-[10px] block">Capacidad: 2 personas · Salón Principal</span>
                            <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                        </div>
                    </div>
                </div>

                {{-- Zona Lounge Sofá Chesterfield S4 (6 Personas) con mesa de centro --}}
                <div
                    class="sofa-lounge-3d table-3d"
                    data-table-id="S4"
                    data-table-name="Sofá Chesterfield S4"
                    data-zone="salon"
                    data-zone-name="Salón Principal"
                    data-capacity="6"
                    data-state="disponible"
                    style="left: 35%; top: 48%; width: 104px; height: 92px;"
                >
                    <i class="fa-solid fa-couch text-lg text-amber mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold text-cream font-mono">S4 Lounge</span>
                    <span class="text-[8px] text-muted text-center px-1">Sofá Chesterfield 6p</span>
                    <div class="table-tooltip">
                        <span class="font-semibold text-cream block">Sofá Chesterfield S4 Lounge</span>
                        <span class="text-amber text-[10px] block">Capacidad: 6 personas · Terciopelo & Mesa Centro</span>
                        <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                    </div>
                </div>

                {{-- Biblioteca & Vinilos + Acceso a Baños (WC) Integrados --}}
                <div class="absolute flex items-center gap-2" style="left: 34%; top: 76%;">
                    <div class="retail-shelf px-2.5 py-1">
                        <i class="fa-solid fa-compact-disc text-[10px]"></i>
                        <span class="text-[8px] text-muted">Biblioteca & Vinilos</span>
                    </div>
                    <div class="px-2 py-1 rounded bg-card/80 border border-border text-[8px] text-muted flex items-center gap-1" title="Servicios Higiénicos">
                        <i class="fa-solid fa-restroom text-amber"></i> <span>WC</span>
                    </div>
                </div>


                {{-- 🚪 3. PUERTA PRINCIPAL DE ACCESO (Inferior Izquierda Limpia) --}}
                <div class="absolute" style="left: 6%; bottom: 5%; transform: translateZ(4px);">
                    <div class="px-3 py-1.5 rounded-lg bg-amber/15 border border-amber/40 text-[9px] font-bold uppercase tracking-wider text-amber flex items-center gap-2 shadow">
                        <i class="fa-solid fa-door-open text-xs"></i> Puerta Principal
                    </div>
                </div>


                {{-- 🌿 4. TERRAZA JARDÍN (PET FRIENDLY) — Llenado Completo (T1, T2, T3, T4, T5) --}}
                <div class="absolute text-[10px] font-bold uppercase tracking-widest text-amber flex items-center gap-1.5" style="left: 58%; top: 6%;">
                    <i class="fa-solid fa-seedling"></i> Terraza Jardín (Pet Friendly)
                </div>

                {{-- Mesa Terraza T1 (4 Personas) con Sombrilla y 4 Sillas --}}
                <div class="table-set-wrapper" style="left: 58%; top: 14%; width: 62px; height: 56px;">
                    <div class="chair-dot" style="top: -8px; left: 50%; transform: translateX(-50%); border-radius: 50%;"></div>
                    <div class="chair-dot" style="bottom: -8px; left: 50%; transform: translateX(-50%); border-radius: 50%;"></div>
                    <div class="chair-dot" style="left: -8px; top: 50%; transform: translateY(-50%); border-radius: 50%;"></div>
                    <div class="chair-dot" style="right: -8px; top: 50%; transform: translateY(-50%); border-radius: 50%;"></div>
                    <div class="umbrella-canopy" style="left: 50%; top: 50%; transform: translate(-50%, -50%);"></div>
                    <div
                        class="table-3d w-full h-full"
                        data-table-id="T1"
                        data-table-name="Mesa Jardín T1"
                        data-zone="terraza"
                        data-zone-name="Terraza Jardín"
                        data-capacity="4"
                        data-state="disponible"
                        style="border-radius: 50%;"
                    >
                        <i class="fa-solid fa-sun text-xs text-amber mb-0.5" aria-hidden="true"></i>
                        <span class="text-[10px] font-bold text-cream font-mono">T1</span>
                        <span class="text-[8px] text-muted">4p</span>
                        <div class="table-tooltip">
                            <span class="font-semibold text-cream block">Mesa Jardín T1 (Sombrilla)</span>
                            <span class="text-amber text-[10px] block">Capacidad: 4 personas · Pet Friendly</span>
                            <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                        </div>
                    </div>
                </div>

                {{-- Mesa Terraza T2 (4 Personas) con Sombrilla y 4 Sillas --}}
                <div class="table-set-wrapper" style="left: 78%; top: 14%; width: 62px; height: 56px;">
                    <div class="chair-dot" style="top: -8px; left: 50%; transform: translateX(-50%); border-radius: 50%;"></div>
                    <div class="chair-dot" style="bottom: -8px; left: 50%; transform: translateX(-50%); border-radius: 50%;"></div>
                    <div class="chair-dot" style="left: -8px; top: 50%; transform: translateY(-50%); border-radius: 50%;"></div>
                    <div class="chair-dot" style="right: -8px; top: 50%; transform: translateY(-50%); border-radius: 50%;"></div>
                    <div class="umbrella-canopy" style="left: 50%; top: 50%; transform: translate(-50%, -50%);"></div>
                    <div
                        class="table-3d w-full h-full"
                        data-table-id="T2"
                        data-table-name="Mesa Jardín T2"
                        data-zone="terraza"
                        data-zone-name="Terraza Jardín"
                        data-capacity="4"
                        data-state="disponible"
                        style="border-radius: 50%;"
                    >
                        <i class="fa-solid fa-tree text-xs text-amber mb-0.5" aria-hidden="true"></i>
                        <span class="text-[10px] font-bold text-cream font-mono">T2</span>
                        <span class="text-[8px] text-muted">4p</span>
                        <div class="table-tooltip">
                            <span class="font-semibold text-cream block">Mesa Jardín T2 (Sombrilla)</span>
                            <span class="text-amber text-[10px] block">Capacidad: 4 personas · Pet Friendly</span>
                            <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                        </div>
                    </div>
                </div>

                {{-- Mesa Terraza T3 (4 Personas Lounge Exterior) --}}
                <div
                    class="table-3d"
                    data-table-id="T3"
                    data-table-name="Mesa Lounge T3"
                    data-zone="terraza"
                    data-zone-name="Terraza Jardín"
                    data-capacity="4"
                    data-state="disponible"
                    style="left: 58%; top: 32%; width: 68px; height: 50px;"
                >
                    <i class="fa-solid fa-umbrella text-xs text-amber mb-0.5" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold text-cream font-mono">T3 Lounge</span>
                    <span class="text-[8px] text-muted">4 personas</span>
                    <div class="table-tooltip">
                        <span class="font-semibold text-cream block">Mesa Lounge T3 Exterior</span>
                        <span class="text-amber text-[10px] block">Capacidad: 4 personas</span>
                        <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                    </div>
                </div>

                {{-- Mesa Terraza T4 (4 Personas Lounge Exterior) --}}
                <div
                    class="table-3d"
                    data-table-id="T4"
                    data-table-name="Mesa Lounge T4"
                    data-zone="terraza"
                    data-zone-name="Terraza Jardín"
                    data-capacity="4"
                    data-state="disponible"
                    style="left: 78%; top: 32%; width: 68px; height: 50px;"
                >
                    <i class="fa-solid fa-chair text-xs text-amber mb-0.5" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold text-cream font-mono">T4 Lounge</span>
                    <span class="text-[8px] text-muted">4 personas</span>
                    <div class="table-tooltip">
                        <span class="font-semibold text-cream block">Mesa Lounge T4 Exterior</span>
                        <span class="text-amber text-[10px] block">Capacidad: 4 personas</span>
                        <span class="text-emerald-400 text-[9px] font-medium uppercase mt-0.5 block">Disponible</span>
                    </div>
                </div>

                {{-- Mesa Terraza T5 (6 Personas Lounge Exterior - Ocupada) --}}
                <div
                    class="table-3d occupied"
                    data-table-id="T5"
                    data-table-name="Mesa Terraza T5"
                    data-zone="terraza"
                    data-zone-name="Terraza Jardín"
                    data-capacity="6"
                    data-state="ocupada"
                    style="left: 64%; top: 48%; width: 110px; height: 48px;"
                >
                    <i class="fa-solid fa-mug-hot text-xs text-muted mb-0.5" aria-hidden="true"></i>
                    <span class="text-[10px] font-bold text-muted font-mono">T5 Lounge Grande</span>
                    <span class="text-[8px] text-muted">6 personas</span>
                    <div class="table-tooltip">
                        <span class="font-semibold text-cream block">Mesa Terraza T5 Lounge</span>
                        <span class="text-amber text-[10px] block">Capacidad: 6 personas</span>
                        <span class="text-red-400 text-[9px] font-medium uppercase mt-0.5 block">Ocupada</span>
                    </div>
                </div>

                {{-- Estación de Bebedero Pet Friendly Limpia y Centrada --}}
                <div class="pet-station" style="left: 65%; top: 60%;">
                    <i class="fa-solid fa-paw text-amber"></i>
                    <span>Pet Bar & Bebedero</span>
                </div>


                {{-- 💻 5. RINCÓN COWORKING (Estaciones Focus C1, C2, C3, C4 100% Seleccionables) --}}
                <div class="coworking-bench-3d absolute px-4 py-2" style="left: 56%; top: 68%; width: 42%; height: 95px; pointer-events: none;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-wider text-amber">
                            <i class="fa-solid fa-laptop"></i> Rincón Coworking
                        </div>
                        <div class="flex items-center gap-2 text-[8px] font-mono text-muted">
                            <span><i class="fa-solid fa-plug text-amber"></i> 220V</span>
                            <span><i class="fa-solid fa-wifi text-amber"></i> 500M</span>
                        </div>
                    </div>
                </div>

                {{-- Estaciones Focus C1, C2, C3, C4 como elementos absolute clickables directos --}}
                @foreach ([
                    ['id' => 'C1', 'left' => 58, 'occ' => false],
                    ['id' => 'C2', 'left' => 68, 'occ' => false],
                    ['id' => 'C3', 'left' => 78, 'occ' => false],
                    ['id' => 'C4', 'left' => 88, 'occ' => true]
                ] as $cw)
                <div
                    class="table-3d {{ $cw['occ'] ? 'occupied' : '' }}"
                    data-table-id="{{ $cw['id'] }}"
                    data-table-name="Estación Focus {{ $cw['id'] }}"
                    data-zone="coworking"
                    data-zone-name="Rincón Coworking"
                    data-capacity="1"
                    data-state="{{ $cw['occ'] ? 'ocupada' : 'disponible' }}"
                    @style(["left: {$cw['left']}%; top: 74%; width: 46px; height: 44px; z-index: 40; pointer-events: auto;"])
                >
                    <i class="fa-solid fa-laptop text-[11px] text-amber mb-0.5" aria-hidden="true"></i>
                    <span class="text-[9px] font-bold text-cream font-mono">{{ $cw['id'] }}</span>
                    <div class="table-tooltip">
                        <span class="font-semibold text-cream block">Estación Focus {{ $cw['id'] }}</span>
                        <span class="text-amber text-[10px] block">1 Persona · Enchufe + Lámpara + Wi-Fi</span>
                        <span class="text-xs {{ $cw['occ'] ? 'text-red-400' : 'text-emerald-400' }} block text-[9px] font-medium uppercase mt-0.5">
                            {{ $cw['occ'] ? 'Ocupada' : 'Disponible para reservar' }}
                        </span>
                    </div>
                </div>
                @endforeach

            </div>
        </div>



            </div>
        </div>




    </div>
</section>



{{-- ================================================================
     SECCIÓN DE FORMULARIO DE RESERVA & VOUCHER DIGITAL
     ================================================================ --}}
<section class="bg-dark py-24 px-6 relative grain overflow-hidden" aria-label="Formulario de reserva">
    {{-- Glow ambiental --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start relative z-10">

        {{-- Columna Izquierda: Formulario y Ticket (7 columnas) --}}
        <div class="lg:col-span-7">

            {{-- Tarjeta del Formulario --}}
            <div id="reserva-form-card" class="bg-card border border-border rounded-xl p-8 sm:p-10 shadow-2xl reveal">
                
                {{-- Encabezado con Badge de Selección 3D --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-8 pb-6 border-b border-border">
                    <div>
                        <h3 class="font-display text-2xl sm:text-3xl font-semibold text-cream">
                            Completa tu Reserva
                        </h3>
                        <p class="text-muted text-xs sm:text-sm mt-1">Configura fecha, hora y datos de contacto.</p>
                    </div>

                    {{-- Sprint 0 — P1: badge neutro, sin preselección hardcodeada. El JS lo actualiza. --}}
                    <div id="reserva-selection-badge" class="badge inline-flex items-center text-xs self-start sm:self-auto opacity-60">
                        <i class="fa-solid fa-hand-pointer mr-1 text-amber" aria-hidden="true"></i>
                        <span id="reserva-selection-text">Selecciona una mesa en el mapa</span>
                    </div>

                </div>

                {{-- Formulario --}}
                <form id="reserva-form" action="{{ route('reserva.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <p id="reserva-error" class="hidden rounded-lg border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-300" role="alert" aria-live="polite"></p>
                    {{--
                        Sprint 0 — P1: Campos ocultos sin preselección hardcodeada.
                        El JavaScript del mapa los actualiza cuando el usuario hace clic
                        en una mesa o selecciona una zona. El servidor los valida.
                    --}}
                    <input type="hidden" id="reserva-mesa-id"      name="mesa_id"      value="">
                    <input type="hidden" id="reserva-mesa-nombre"   name="mesa_nombre"  value="">
                    <input type="hidden" id="reserva-tipo"          name="tipo_reserva" value="mesa">
                    <input type="hidden" id="reserva-zona-id"       name="zona_id"      value="">
                    <input type="hidden" id="reserva-zona-nombre"   name="zona_nombre"  value="">
                    <input type="hidden" id="reserva-hora"          name="hora"         value="">


                    {{-- Fila 1: Fecha y Número de Comensales --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Selector de Fecha --}}
                        <div>
                            <label class="f-label" for="reserva-fecha">
                                <i class="fa-regular fa-calendar mr-1.5" aria-hidden="true"></i> Fecha de Visita *
                            </label>
                            <input
                                id="reserva-fecha"
                                type="date"
                                name="fecha"
                                class="f-input"
                                value="{{ date('Y-m-d') }}"
                                min="{{ date('Y-m-d') }}"
                                required
                            >
                        </div>

                        {{-- Contador de Comensales --}}
                        <div>
                            <label class="f-label">
                                <i class="fa-solid fa-users mr-1.5" aria-hidden="true"></i> Número de Personas *
                            </label>
                            <div class="flex items-center justify-between bg-surface border border-border rounded-lg p-2 mt-1">
                                <button
                                    id="guests-dec"
                                    type="button"
                                    class="w-9 h-9 rounded-md bg-card border border-border text-cream hover:border-amber hover:text-amber flex items-center justify-center transition cursor-pointer"
                                    aria-label="Disminuir personas"
                                >
                                    <i class="fa-solid fa-minus text-xs" aria-hidden="true"></i>
                                </button>
                                <span id="guests-count" class="font-display font-semibold text-cream text-lg">2 personas</span>
                                <input type="hidden" id="reserva-personas" name="personas" value="2">
                                <button
                                    id="guests-inc"
                                    type="button"
                                    class="w-9 h-9 rounded-md bg-card border border-border text-cream hover:border-amber hover:text-amber flex items-center justify-center transition cursor-pointer"
                                    aria-label="Aumentar personas"
                                >
                                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Fila 2: Turnos de Horario --}}
                    <div>
                        <label class="f-label mb-2">
                            <i class="fa-regular fa-clock mr-1.5" aria-hidden="true"></i> Turno / Horario Disponible *
                        </label>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2.5">
                            @foreach ($reservationSlots as $t)
                            <button
                                type="button"
                                class="time-slot-btn"
                                data-time="{{ $t }}"
                            >
                                {{ $t }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Fila 3: Datos de Contacto --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                        <div>
                            <label class="f-label" for="reserva-nombre">
                                <i class="fa-regular fa-user mr-1.5" aria-hidden="true"></i> Nombre Completo *
                            </label>
                            <input
                                id="reserva-nombre"
                                type="text"
                                name="nombre"
                                class="f-input"
                                placeholder="Ej. Camila Navarro"
                                required
                            >
                        </div>

                        <div>
                            <label class="f-label" for="reserva-email">
                                <i class="fa-regular fa-envelope mr-1.5" aria-hidden="true"></i> Correo Electrónico *
                            </label>
                            <input
                                id="reserva-email"
                                type="email"
                                name="email"
                                class="f-input"
                                placeholder="tuemail@ejemplo.com"
                                required
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="f-label" for="reserva-telefono">
                                <i class="fa-solid fa-phone mr-1.5" aria-hidden="true"></i> Teléfono / WhatsApp *
                            </label>
                            <input
                                id="reserva-telefono"
                                type="tel"
                                name="telefono"
                                class="f-input"
                                placeholder="+51 999 000 000"
                                required
                            >
                        </div>

                        <div>
                            <label class="f-label" for="reserva-ocasion">
                                <i class="fa-solid fa-champagne-glasses mr-1.5" aria-hidden="true"></i> Ocasión Especial
                            </label>
                            <select id="reserva-ocasion" name="ocasion" class="f-select">
                                <option value="" selected>Reunión casual / Sin motivo especial</option>
                                @foreach (config('cafe.ocasiones_reserva') as $oc)
                                <option value="{{ $oc['id'] }}">{{ $oc['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Peticiones especiales --}}
                    <div>
                        <label class="f-label" for="reserva-notas">
                            <i class="fa-regular fa-comment-dots mr-1.5" aria-hidden="true"></i> Notas o Peticiones Especiales (Opcional)
                        </label>
                        <textarea
                            id="reserva-notas"
                            name="notas"
                            class="f-textarea"
                            placeholder="Alergias, preferencia de asiento, si vienes con mascota, etc."
                            style="min-height:75px"
                        ></textarea>
                    </div>

                    {{-- Botón de Enviar --}}
                    <button
                        type="submit"
                        class="btn-amber w-full py-4 text-sm font-semibold tracking-wide shadow-xl flex items-center justify-center gap-2"
                    >
                        <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                        <span>Solicitar Reserva</span>
                    </button>
                </form>
            </div>

            {{-- Tarjeta de Confirmación / Voucher Digital (Inicialmente Oculto) --}}
            <div id="reserva-success-card" class="hidden ticket-voucher p-8 sm:p-10 reveal">
                {{-- Encabezado del Voucher --}}
                <div class="text-center pb-6 border-b border-border/80">
                    <div class="w-14 h-14 rounded-full border border-amber/60 bg-amber/10 flex items-center justify-center mx-auto mb-3 text-amber text-2xl">
                        <i class="fa-solid fa-hourglass-half" aria-hidden="true"></i>
                    </div>
                    {{--
                        Sprint 0 — P3: El servidor guarda status='pending'.
                        Solo mostramos "Confirmada" cuando el admin cambia el estado.
                        El badge refleja la realidad: solicitud recibida, pendiente de confirmación.
                    --}}
                    <span id="voucher-status-badge" class="badge text-amber bg-amber/10 border-amber/30 mb-2 inline-block">Solicitud recibida · Pendiente</span>
                    <h3 class="font-display text-3xl font-bold text-cream">¡Te esperamos en {{ setting('nombre', config('cafe.nombre', 'Raíz & Grano')) }}!</h3>
                    <p class="text-muted text-xs sm:text-sm mt-1">Hemos registrado tu solicitud. El equipo la confirmará pronto.</p>
                </div>


                {{-- Código y Datos del Ticket --}}
                <div class="py-6 space-y-4">
                    <div class="flex items-center justify-between bg-surface/80 p-3.5 rounded-lg border border-border">
                        <span class="text-xs text-muted uppercase font-medium">Código de Reserva</span>
                        <span id="voucher-code" class="font-mono text-base font-bold text-amber">#RG-8492</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-surface/60 p-3 rounded-lg border border-border/60">
                            <span class="text-muted block mb-0.5">Titular</span>
                            <span id="voucher-name" class="font-medium text-cream block text-sm truncate">Juan Pérez</span>
                        </div>
                        <div class="bg-surface/60 p-3 rounded-lg border border-border/60">
                            <span class="text-muted block mb-0.5">Comensales</span>
                            <span id="voucher-guests" class="font-medium text-cream block text-sm">2 personas</span>
                        </div>
                        <div class="bg-surface/60 p-3 rounded-lg border border-border/60">
                            <span class="text-muted block mb-0.5">Fecha</span>
                            <span id="voucher-date" class="font-medium text-cream block text-sm">28/08/2026</span>
                        </div>
                        <div class="bg-surface/60 p-3 rounded-lg border border-border/60">
                            <span class="text-muted block mb-0.5">Hora</span>
                            <span id="voucher-time" class="font-semibold text-amber block text-sm">05:00 PM</span>
                        </div>
                    </div>

                    <div class="bg-surface/80 p-3.5 rounded-lg border border-border flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-muted block">Espacio Seleccionado en Maqueta 3D</span>
                            <span id="voucher-table" class="font-display text-base font-semibold text-cream">Mesa Central S1</span>
                        </div>
                        <span id="voucher-zone" class="badge">Salón Principal</span>
                    </div>
                </div>

                {{-- Divisor perforado de ticket --}}
                <div class="ticket-divider"></div>

                {{-- Acciones del Ticket --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a
                        id="voucher-whatsapp-btn"
                        href="https://wa.me/51999999999"
                        target="_blank"
                        rel="noopener"
                        class="btn-amber text-xs py-3 flex-1 text-center justify-center"
                    >
                        <i class="fa-brands fa-whatsapp mr-1.5 text-sm" aria-hidden="true"></i> Enviar confirmación a WhatsApp
                    </a>
                    <button
                        type="button"
                        onclick="document.getElementById('reserva-form').reset(); document.getElementById('reserva-form-card').classList.remove('hidden'); document.getElementById('reserva-success-card').classList.add('hidden');"
                        class="btn-ghost text-xs py-3"
                    >
                        <i class="fa-solid fa-rotate-left mr-1.5" aria-hidden="true"></i> Nueva Reserva
                    </button>
                </div>
            </div>

        </div>


        {{-- Columna Derecha: Ambientes de la Cafetería & Políticas (5 columnas) --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- Selector Rápido de Zonas --}}
            <div class="reveal">
                <p class="amber-tag mb-3">Zonas Disponibles</p>
                <h3 class="font-display text-2xl font-bold text-cream mb-4">
                    Explora nuestros espacios
                </h3>

                <div class="reserve-mode-switch mb-4" role="group" aria-label="Tipo de reserva">
                    <button id="reserve-mode-zone" type="button" class="reserve-mode-btn" aria-pressed="false">
                        <i class="fa-solid fa-layer-group" aria-hidden="true"></i> Zona completa
                    </button>
                    <button id="reserve-mode-table" type="button" class="reserve-mode-btn active" aria-pressed="true">
                        <i class="fa-solid fa-chair" aria-hidden="true"></i> Mesa exacta
                    </button>
                </div>

                <div class="space-y-3">
                    @foreach ($reservationZones as $z)
                    <div
                        class="zona-card is-disabled p-4 flex items-center gap-4"
                        data-zone="{{ $z['id'] }}"
                        data-zone-name="{{ $z['nombre'] }}"
                        data-available="false"
                    >
                        <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0 relative">
                            <img src="{{ $z['imagen'] ?? config('cafe.hero_img') }}" alt="{{ $z['nombre'] }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                            <div class="absolute inset-0 bg-ink/30"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-0.5">
                                <h4 class="font-display text-lg font-semibold text-cream truncate">{{ $z['nombre'] }}</h4>
                                <span class="badge text-[9px] shrink-0">{{ $z['badge'] ?? 'Zona de reserva' }}</span>
                            </div>
                            <p class="text-muted text-xs line-clamp-1 leading-snug">{{ $z['descripcion'] ?? 'Reserva una zona completa del local.' }}</p>
                            <span class="text-amber text-[10px] font-medium block mt-1">
                                <i class="fa-solid fa-user-group mr-1" aria-hidden="true"></i>
                                <span data-zone-summary>Hasta {{ $z['capacidad'] }} personas</span>
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Políticas y Garantías --}}
            <div class="bg-card border border-border rounded-xl p-6 shadow-xl reveal" style="transition-delay:.15s">
                <h4 class="font-display text-xl font-semibold text-cream mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-amber text-base" aria-hidden="true"></i>
                    <span>Pautas de Reserva</span>
                </h4>

                <div class="space-y-4">
                    @foreach (config('cafe.politicas_reserva') as $pol)
                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-7 h-7 rounded-full bg-surface border border-amber/30 flex items-center justify-center text-amber shrink-0 mt-0.5">
                            <i class="{{ $pol['icono'] }}" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h5 class="font-semibold text-cream mb-0.5">{{ $pol['titulo'] }}</h5>
                            <p class="text-muted leading-relaxed">{{ $pol['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Banner Grupos Grandes --}}
                <div class="mt-6 pt-5 border-t border-border/80 flex items-center justify-between gap-3">
                    <div class="text-xs">
                        <span class="text-cream font-medium block">¿Grupo mayor a 8 personas?</span>
                        <span class="text-muted text-[11px]">Coordinamos mesas especiales y catering.</span>
                    </div>
                    @if (!empty(setting('redes', config('cafe.redes', []))['whatsapp']))
                    <a
                        href="{{ setting('redes', config('cafe.redes', []))['whatsapp'] }}"
                        target="_blank"
                        rel="noopener"
                        class="btn-amber text-[11px] py-2 px-3 shrink-0"
                    >
                        <i class="fa-brands fa-whatsapp mr-1 text-sm" aria-hidden="true"></i> Escribir
                    </a>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
/**
 * Sprint 0 — P2, P9: gestión del flujo de reserva en el frontend.
 *
 * Responsabilidades:
 *  - Sincronizar la selección de mesa/zona del mapa 3D con los campos ocultos.
 *  - Enviar el formulario vía fetch (AJAX) y construir el voucher con la
 *    respuesta del SERVIDOR, nunca con los valores locales del formulario.
 *  - Limpiar el estado de selección al cambiar entre modo mesa y modo zona.
 *  - Gestionar los botones de turno horario.
 *  - Gestionar el contador de personas con límite dinámico por mesa.
 */
(function () {
    'use strict';

    /* ── Estado de selección ─────────────────────────────────────────── */
    const selection = {
        tipo:       'mesa',  // 'mesa' | 'zona'
        mesaId:     '',
        mesaNombre: '',
        zonaId:     '',
        zonaNombre: '',
        capacidad:  null,
        hora:       '',
    };

    /* ── Helpers de DOM ──────────────────────────────────────────────── */
    const $  = id  => document.getElementById(id);
    const $$ = sel => document.querySelectorAll(sel);
    const floorplan = $('floorplan-mesh');

    // Discard the decorative sample tables; the selectable layer is rendered from active database tables.
    $$('#floorplan-mesh .table-3d:not(.reservation-table), #floorplan-mesh .table-set-wrapper').forEach(el => el.remove());

    /* ── Sincronizar campos ocultos con el estado de selección ────────── */
    function syncHiddenFields() {
        $('reserva-mesa-id').value      = selection.mesaId;
        $('reserva-mesa-nombre').value  = selection.mesaNombre;
        $('reserva-tipo').value         = selection.tipo;
        $('reserva-zona-id').value      = selection.zonaId;
        $('reserva-zona-nombre').value  = selection.zonaNombre;
        $('reserva-hora').value         = selection.hora;
    }

    /* ── Actualizar badge de selección en el formulario ──────────────── */
    function updateSelectionBadge() {
        const badge = $('reserva-selection-badge');
        const text  = $('reserva-selection-text');
        if (! badge || ! text) return;

        if (selection.tipo === 'mesa' && selection.mesaId) {
            badge.classList.remove('opacity-60');
            badge.querySelector('i').className = 'fa-solid fa-check-circle mr-1 text-amber';
            text.textContent = selection.mesaNombre + ' · ' + selection.zonaNombre;
        } else if (selection.tipo === 'zona' && selection.zonaId) {
            badge.classList.remove('opacity-60');
            badge.querySelector('i').className = 'fa-solid fa-layer-group mr-1 text-amber';
            text.textContent = 'Zona: ' + selection.zonaNombre;
        } else {
            badge.classList.add('opacity-60');
            badge.querySelector('i').className = 'fa-solid fa-hand-pointer mr-1 text-amber';
            text.textContent = 'Selecciona una ' + (selection.tipo === 'zona' ? 'zona' : 'mesa') + ' en el mapa';
        }
    }

    /* ── Limpiar selección de mesa/zona (Sprint 0 — P9) ─────────────── */
    function clearTableSelection() {
        $$('.table-3d.selected').forEach(el => el.classList.remove('selected'));
        selection.mesaId     = '';
        selection.mesaNombre = '';
        selection.capacidad  = null;
        syncHiddenFields();
        updateSelectionBadge();
        resetGuestLimit();
    }

    function clearZoneSelection() {
        $$('.zona-card.active').forEach(el => el.classList.remove('active'));
        selection.zonaId     = '';
        selection.zonaNombre = '';
        syncHiddenFields();
        updateSelectionBadge();
    }

    /* ── Contador de personas ─────────────────────────────────────────── */
    let guestsCount = 1;
    let guestsMax   = 20;

    function resetGuestLimit() {
        guestsMax = 20;
        renderGuests();
    }

    function renderGuests() {
        guestsCount = Math.min(guestsCount, guestsMax);
        guestsCount = Math.max(1, guestsCount);
        const span = $('guests-count');
        const inp  = $('reserva-personas');
        if (span) span.textContent = guestsCount + (guestsCount === 1 ? ' persona' : ' personas');
        if (inp)  inp.value = guestsCount;
    }

    $('guests-dec') && $('guests-dec').addEventListener('click', () => {
        guestsCount = Math.max(1, guestsCount - 1);
        renderGuests();
    });

    $('guests-inc') && $('guests-inc').addEventListener('click', () => {
        guestsCount = Math.min(guestsMax, guestsCount + 1);
        renderGuests();
    });

    /* ── Clic en mesa del mapa 3D ─────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        const tableEl = e.target.closest('.table-3d');
        if (! tableEl) return;
        if (tableEl.dataset.available !== 'true') return;

        // Solo permitir clic en mesas cuando el modo es 'mesa exacta'
        if (selection.tipo !== 'mesa') return;

        $$('.table-3d.selected').forEach(el => el.classList.remove('selected'));
        tableEl.classList.add('selected');

        selection.mesaId     = tableEl.dataset.tableId    || '';
        selection.mesaNombre = tableEl.dataset.tableName  || '';
        selection.zonaId     = tableEl.dataset.zone       || '';
        selection.zonaNombre = tableEl.dataset.zoneName   || '';
        selection.capacidad  = parseInt(tableEl.dataset.capacity, 10) || 20;

        // Actualizar límite de personas según capacidad de la mesa
        guestsMax = selection.capacidad;
        renderGuests();

        syncHiddenFields();
        updateSelectionBadge();
    });

    /* ── Cambio de modo: Mesa exacta ↔ Zona completa (Sprint 0 — P9) ── */
    $('reserve-mode-table') && $('reserve-mode-table').addEventListener('click', () => {
        if (selection.tipo === 'mesa') return;
        selection.tipo = 'mesa';
        $('reserve-mode-table').classList.add('active');
        $('reserve-mode-table').setAttribute('aria-pressed', 'true');
        $('reserve-mode-zone').classList.remove('active');
        $('reserve-mode-zone').setAttribute('aria-pressed', 'false');
        // P9: limpiar selección de zona al cambiar de modo
        clearZoneSelection();
        syncHiddenFields();
        updateSelectionBadge();
    });

    $('reserve-mode-zone') && $('reserve-mode-zone').addEventListener('click', () => {
        if (selection.tipo === 'zona') return;
        selection.tipo = 'zona';
        $('reserve-mode-zone').classList.add('active');
        $('reserve-mode-zone').setAttribute('aria-pressed', 'true');
        $('reserve-mode-table').classList.remove('active');
        $('reserve-mode-table').setAttribute('aria-pressed', 'false');
        // P9: limpiar selección de mesa al cambiar de modo
        clearTableSelection();
        syncHiddenFields();
        updateSelectionBadge();
    });

    /* ── Clic en tarjeta de zona ──────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        const zonaCard = e.target.closest('.zona-card');
        if (! zonaCard) return;
        if (zonaCard.dataset.available !== 'true') return;

        $$('.zona-card.active').forEach(el => el.classList.remove('active'));
        zonaCard.classList.add('active');

        selection.zonaId     = zonaCard.dataset.zone     || '';
        selection.zonaNombre = zonaCard.dataset.zoneName || '';

        // Si estamos en modo zona, actualizar los ocultos
        if (selection.tipo === 'zona') {
            syncHiddenFields();
            updateSelectionBadge();
        }
    });

    /* ── Selector de turno horario ────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        const slotBtn = e.target.closest('.time-slot-btn');
        if (! slotBtn) return;

        $$('.time-slot-btn.active').forEach(el => el.classList.remove('active'));
        slotBtn.classList.add('active');
        selection.hora = slotBtn.dataset.time || '';
        $('reserva-hora').value = selection.hora;
        refreshAvailability();
    });

    async function refreshAvailability() {
        const fecha = $('reserva-fecha')?.value;
        if (! fecha || ! selection.hora || ! floorplan) return;

        const url = new URL(floorplan.dataset.availabilityUrl, window.location.origin);
        url.searchParams.set('fecha', fecha);
        url.searchParams.set('hora', selection.hora);

        try {
            const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (! response.ok) throw new Error('No se pudo consultar disponibilidad.');
            const data = await response.json();

            $$('#floorplan-mesh .reservation-table').forEach(table => {
                const available = data.tables[table.dataset.tableId] === true;
                table.dataset.available = String(available);
                table.classList.toggle('occupied', ! available);
                table.setAttribute('aria-disabled', String(! available));
                if (! available && table.classList.contains('selected')) clearTableSelection();
            });

            $$('.zona-card').forEach(zone => {
                const available = data.zones[zone.dataset.zone] === true;
                zone.dataset.available = String(available);
                zone.classList.toggle('is-disabled', ! available);
                if (! available && zone.classList.contains('active')) clearZoneSelection();
            });
        } catch (error) {
            $$('#floorplan-mesh .reservation-table').forEach(table => {
                table.dataset.available = 'false';
                table.classList.add('occupied');
                table.setAttribute('aria-disabled', 'true');
            });
            $$('.zona-card').forEach(zone => {
                zone.dataset.available = 'false';
                zone.classList.add('is-disabled');
            });
        }
    }

    $('reserva-fecha')?.addEventListener('change', refreshAvailability);

    /* ── Envío del formulario vía fetch (Sprint 0 — P2) ──────────────── */
    const form = $('reserva-form');
    form && form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const errorEl  = $('reserva-error');
        const submitBtn = form.querySelector('[type="submit"]');
        const btnSpan   = submitBtn && submitBtn.querySelector('span');

        // Validación mínima del lado cliente antes de enviar
        if (selection.tipo === 'mesa' && ! selection.mesaId) {
            errorEl.textContent = 'Por favor selecciona una mesa en el mapa antes de continuar.';
            errorEl.classList.remove('hidden');
            return;
        }
        if (selection.tipo === 'zona' && ! selection.zonaId) {
            errorEl.textContent = 'Por favor selecciona una zona antes de continuar.';
            errorEl.classList.remove('hidden');
            return;
        }
        if (! selection.hora) {
            errorEl.textContent = 'Por favor elige un turno horario.';
            errorEl.classList.remove('hidden');
            return;
        }

        errorEl.classList.add('hidden');
        if (submitBtn) submitBtn.disabled = true;
        if (btnSpan)   btnSpan.textContent = 'Enviando…';

        try {
            const resp = await fetch(form.action, {
                method:  'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body:    new FormData(form),
            });

            const json = await resp.json();

            if (! resp.ok || ! json.success) {
                // Mostrar errores de validación del servidor
                const errors = json.errors
                    ? Object.values(json.errors).flat().join(' ')
                    : (json.message || 'Ocurrió un error al procesar tu solicitud. Por favor intenta de nuevo.');
                errorEl.textContent = errors;
                errorEl.classList.remove('hidden');
                return;
            }

            // ── Sprint 0 — P2: llenar el voucher SOLO con datos del servidor ──
            const d = json.data;

            $('voucher-code').textContent   = '#' + d.code;
            $('voucher-name').textContent   = d.nombre;
            $('voucher-guests').textContent = d.personas + (d.personas === 1 ? ' persona' : ' personas');
            $('voucher-date').textContent   = d.fecha;
            $('voucher-time').textContent   = d.hora;
            $('voucher-table').textContent  = d.mesa_nombre || d.zona;
            $('voucher-zone').textContent   = d.zona;

            // El status siempre llega como 'pending'; el badge ya está en "Solicitud recibida".
            // (Si en el futuro el servidor devuelve 'confirmed', actualizar el badge aquí.)

            // Mostrar tarjeta de éxito y ocultar el formulario
            $('reserva-form-card').classList.add('hidden');
            $('reserva-success-card').classList.remove('hidden');

        } catch (err) {
            errorEl.textContent = 'Error de conexión. Por favor verifica tu internet e intenta de nuevo.';
            errorEl.classList.remove('hidden');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
            if (btnSpan)   btnSpan.textContent = 'Solicitar Reserva';
        }
    });

    /* ── Botón "Nueva Reserva" ────────────────────────────────────────── */
    document.addEventListener('click', function (e) {
        if (! e.target.closest('[data-action="nueva-reserva"]') &&
            ! e.target.closest('#reserva-success-card button[type="button"]')) return;

        // Resetear estado de selección
        selection.mesaId     = '';
        selection.mesaNombre = '';
        selection.zonaId     = '';
        selection.zonaNombre = '';
        selection.hora       = '';
        guestsCount = 1;
        guestsMax   = 20;
        renderGuests();
        $$('.table-3d.selected').forEach(el => el.classList.remove('selected'));
        $$('.time-slot-btn.active').forEach(el => el.classList.remove('active'));
        syncHiddenFields();
        updateSelectionBadge();

        form.reset();
        $('reserva-form-card').classList.remove('hidden');
        $('reserva-success-card').classList.add('hidden');
        $('reserva-error').classList.add('hidden');
    });

    /* ── Inicializar con el primer turno horario como activo ─────────── */
    const firstSlot = document.querySelector('.time-slot-btn');
    if (firstSlot) {
        firstSlot.classList.add('active');
        selection.hora = firstSlot.dataset.time || '';
        $('reserva-hora').value = selection.hora;
    }

    refreshAvailability();

    syncHiddenFields();
    updateSelectionBadge();

})();
</script>
@endpush
