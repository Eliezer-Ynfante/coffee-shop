@extends('layout.layout')

@section('content')

{{-- ================================================================
     HERO — Cabecera interior de la página Reservas
     ================================================================ --}}
<section
    class="reserva-hero relative flex items-center overflow-hidden grain"
    aria-label="Cabecera de reservas"
>
    {{-- Fondo negro base --}}
    <div class="absolute inset-0 bg-ink"></div>

    {{-- Imagen de fondo ambiental --}}
    <div
        class="absolute inset-0 reserva-hero-bg"
        style="background-image:url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1400&q=85'); opacity:.42"
    ></div>

    {{-- Gradientes en capas --}}
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/85 to-ink/35"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/50"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-40 pb-28">
        <p class="amber-tag mb-5 reveal">Tu Espacio Reservado</p>

        <h1
            class="font-display text-cream font-bold leading-tight mb-4 reveal max-w-3xl"
            style="font-size:clamp(2.6rem,6.5vw,4.8rem); transition-delay:.1s"
        >
            Reserva tu <em class="text-amber not-italic">Experiencia</em>
        </h1>

        <p
            class="text-cream/55 text-sm md:text-base leading-relaxed max-w-xl reveal"
            style="transition-delay:.2s"
        >
            Explora nuestra maqueta 3D interactiva, elige tu mesa favorita y asegura tu lugar en nuestra cafetería de especialidad.
        </p>
    </div>
</section>


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
            <div id="floorplan-mesh" class="floorplan-mesh view-3d-straight">
                
                {{-- Base del Suelo Principal (Microcemento / Parquet Oscuro) --}}
                <div class="floor-base"></div>

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
                    style="left: {{ $leftPos }}%; top: 20%; width: 38px; height: 38px; border-radius: 50%;"
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
                    style="left: {{ $cw['left'] }}%; top: 74%; width: 46px; height: 44px; z-index: 40; pointer-events: auto;"
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

                    <div id="reserva-selection-badge" class="badge inline-flex items-center text-xs self-start sm:self-auto">
                        <i class="fa-solid fa-check-circle mr-1 text-amber" aria-hidden="true"></i> Mesa Central S1 · Salón Principal
                    </div>
                </div>

                {{-- Formulario --}}
                <form id="reserva-form" class="space-y-6">
                    {{-- Campos Ocultos sincronizados con la Maqueta 3D --}}
                    <input type="hidden" id="reserva-mesa-id" name="mesa_id" value="S1">
                    <input type="hidden" id="reserva-mesa-nombre" name="mesa_nombre" value="Mesa Central S1">
                    <input type="hidden" id="reserva-zona-id" name="zona_id" value="salon">
                    <input type="hidden" id="reserva-zona-nombre" name="zona_nombre" value="Salón Principal">
                    <input type="hidden" id="reserva-hora" name="hora" value="05:00 PM">

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
                            @foreach (config('cafe.turnos_horarios') as $idx => $t)
                            <button
                                type="button"
                                class="time-slot-btn {{ $t === '05:00 PM' ? 'active' : '' }}"
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
                        <span>Confirmar Reserva de Mesa</span>
                    </button>
                </form>
            </div>

            {{-- Tarjeta de Confirmación / Voucher Digital (Inicialmente Oculto) --}}
            <div id="reserva-success-card" class="hidden ticket-voucher p-8 sm:p-10 reveal">
                {{-- Encabezado del Voucher --}}
                <div class="text-center pb-6 border-b border-border/80">
                    <div class="w-14 h-14 rounded-full border border-emerald-500/60 bg-emerald-500/10 flex items-center justify-center mx-auto mb-3 text-emerald-400 text-2xl">
                        <i class="fa-solid fa-check" aria-hidden="true"></i>
                    </div>
                    <span class="badge text-emerald-400 bg-emerald-500/10 border-emerald-500/30 mb-2 inline-block">Reserva Confirmada</span>
                    <h3 class="font-display text-3xl font-bold text-cream">¡Te esperamos en {{ config('cafe.nombre') ?? 'Raíz & Grano' }}!</h3>
                    <p class="text-muted text-xs sm:text-sm mt-1">Hemos registrado tu lugar. Presenta este voucher al llegar.</p>
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
                            <span id="voucher-time" class="font-medium text-amber block text-sm font-semibold">05:00 PM</span>
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

                <div class="space-y-3">
                    @foreach (config('cafe.zonas_reserva') as $z)
                    <div
                        class="zona-card {{ $z['id'] === 'salon' ? 'active' : '' }} p-4 flex items-center gap-4"
                        data-zone="{{ $z['id'] }}"
                    >
                        <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0 relative">
                            <img src="{{ $z['imagen'] }}" alt="{{ $z['nombre'] }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-ink/30"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-0.5">
                                <h4 class="font-display text-lg font-semibold text-cream truncate">{{ $z['nombre'] }}</h4>
                                <span class="badge text-[9px] shrink-0">{{ $z['badge'] }}</span>
                            </div>
                            <p class="text-muted text-xs line-clamp-1 leading-snug">{{ $z['descripcion'] }}</p>
                            <span class="text-amber text-[10px] font-medium block mt-1">
                                <i class="fa-solid fa-user-group mr-1" aria-hidden="true"></i>{{ $z['capacidad'] }}
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
                    @if (!empty(config('cafe.redes')['whatsapp']))
                    <a
                        href="{{ config('cafe.redes')['whatsapp'] }}"
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

