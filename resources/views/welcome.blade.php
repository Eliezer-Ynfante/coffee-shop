<?php
// ============================================================
//  CONFIGURACIÓN PRINCIPAL — Edita aquí sin tocar el resto
// ============================================================

/* --- Identidad de la cafetería --- */
$cafe_nombre    = "Raíz & Grano";
$cafe_slogan    = "Tómate un descanso";
$cafe_titulo    = "y bebe café";
$cafe_subtitulo = "En nuestra acogedora cafetería, en buena compañía, con socios de negocios o con personas que piensan igual que tú.";
$cafe_descripcion = "¡Bienvenido a nuestra acogedora cafetería! Es un lugar excelente donde puedes pasar el tiempo tomando una taza de café, con buena música y un ambiente increíble. Aquí puedes relajarte con amigos, trabajar o simplemente disfrutar del maravilloso aroma del café. Estaremos encantados de verte cada día.";
$cafe_subtag    = "Café de especialidad · Piura, Perú";

/* --- Logo (dejar vacío para usar texto+icono) --- */
$img_logo = "";

/* --- Imágenes principales --- */
$img_hero  = "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400&q=85";
$img_about = "https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=800&q=80";

/* --- Horario y contacto --- */
$cafe_horario   = "Lun – Vie: 7 am – 8 pm · Sáb – Dom: 8 am – 6 pm";
$cafe_direccion = "Av. La Mar 620, Miraflores, Lima";
$cafe_email     = "hola@raizygrano.pe";
$cafe_telefono  = "+51 999 999 999";

/* --- Redes sociales (dejar vacío para ocultar el ícono) --- */
$redes = [
    "instagram" => "https://instagram.com",
    "facebook"  => "https://facebook.com",
    "tiktok"    => "https://tiktok.com",
    "whatsapp"  => "https://wa.me/51999999999",
];

/* --- Características / ventajas — usar clases de Font Awesome 6 --- */
$features = [
    ["icono" => "fa-solid fa-mug-hot",      "titulo" => "Los mejores granos",   "texto" => "Elige tu sabor de café favorito"],
    ["icono" => "fa-solid fa-wifi",         "titulo" => "Wi-Fi gratuito",        "texto" => "Conversa con amigos o resuelve asuntos de trabajo"],
    ["icono" => "fa-solid fa-cookie-bite",  "titulo" => "Deliciosos postres",    "texto" => "Nunca te quedarás con hambre"],
    ["icono" => "fa-solid fa-bag-shopping", "titulo" => "Café para llevar",      "texto" => "Toma café aquí o llévate uno"],
];

/* --- Menú por categorías — lista con precios punteados --- */
$menu_categorias = [
    [
        "nombre" => "Café caliente",
        "imagen" => "https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=420&q=80",
        "items"  => [
            ["nombre" => "Cappuccino",  "descripcion" => "Espresso con leche y abundante espuma",          "precio" => "S/ 10"],
            ["nombre" => "Americano",   "descripcion" => "Espresso suave con agua caliente",               "precio" => "S/ 8"],
            ["nombre" => "Flat White",  "descripcion" => "Doble ristretto con leche aterciopelada",        "precio" => "S/ 12"],
            ["nombre" => "Latte",       "descripcion" => "Espresso cremoso con leche vaporizada",          "precio" => "S/ 11"],
        ],
    ],
    [
        "nombre" => "Repostería y postres",
        "imagen" => "https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=420&q=80",
        "items"  => [
            ["nombre" => "Croissant",   "descripcion" => "Clásico croissant con textura mantecosa",        "precio" => "S/ 9"],
            ["nombre" => "Cheesecake",  "descripcion" => "Tarta de queso cremosa con base de galleta",    "precio" => "S/ 14"],
            ["nombre" => "Brownie",     "descripcion" => "Brownie de chocolate amargo y nueces",           "precio" => "S/ 10"],
            ["nombre" => "Muffin",      "descripcion" => "Muffin esponjoso con chips de espresso",        "precio" => "S/ 8"],
        ],
    ],
];

/* --- Productos destacados (cards con foto) --- */
$productos = [
    [
        "nombre"      => "Espresso Reserva",
        "descripcion" => "Blend exclusivo de altura, cuerpo intenso con notas a chocolate oscuro.",
        "precio"      => "S/ 9",
        "imagen"      => "https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=600&q=80",
        "badge"       => "Firma",
    ],
    [
        "nombre"      => "Latte de Lúcuma",
        "descripcion" => "Espresso suave con leche vaporizada y jarabe artesanal de lúcuma peruana.",
        "precio"      => "S/ 14",
        "imagen"      => "https://images.unsplash.com/photo-1541167760496-1628856ab772?w=600&q=80",
        "badge"       => "Favorito",
    ],
    [
        "nombre"      => "Cold Brew 24h",
        "descripcion" => "Extracción en frío durante 24 horas. Suave, bajo en acidez y muy refrescante.",
        "precio"      => "S/ 16",
        "imagen"      => "https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80",
        "badge"       => "Nuevo",
    ],
];

// ============================================================
//  FIN DE CONFIGURACIÓN
// ============================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($cafe_nombre) ?> — Café de Especialidad</title>
    <meta name="description" content="<?= htmlspecialchars($cafe_slogan) ?> <?= htmlspecialchars($cafe_titulo) ?>">

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome 6 — iconos profesionales (sin emojis) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">

    <!-- Google Fonts: Cormorant Garant (display serif) + Outfit (body sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garant:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink:     '#0A0704',
                        dark:    '#110D08',
                        surface: '#1A120A',
                        card:    '#221608',
                        amber:   '#C8783A',
                        gold:    '#D4A017',
                        cream:   '#F0E6D0',
                        muted:   '#7A6550',
                        border:  '#2E1F10',
                    },
                    fontFamily: {
                        display: ['"Cormorant Garant"', 'Georgia', 'serif'],
                        body:    ['"Outfit"', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --amber:   #C8783A;
            --gold:    #D4A017;
            --cream:   #F0E6D0;
            --ink:     #0A0704;
            --surface: #1A120A;
            --border:  #2E1F10;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--ink);
            color: var(--cream);
        }

        /* ── Scrollbar personalizada ─────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--ink); }
        ::-webkit-scrollbar-thumb { background: var(--amber); border-radius: 3px; }

        /* ── Textura grain ───────────────────────────── */
        .grain::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
            pointer-events: none;
            opacity: 0.35;
            mix-blend-mode: overlay;
        }

        /* ── Navbar ──────────────────────────────────── */
        #navbar { transition: background .4s, box-shadow .4s; }
        #navbar.scrolled {
            background: rgba(10,7,4,.96) !important;
            backdrop-filter: blur(14px);
            box-shadow: 0 1px 0 rgba(200,120,58,.18);
        }

        .nav-link {
            color: rgba(240,230,208,.65);
            font-size: .875rem;
            transition: color .2s;
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -3px; left: 0;
            width: 0; height: 1px;
            background: var(--amber);
            transition: width .3s;
        }
        .nav-link:hover { color: var(--cream); }
        .nav-link:hover::after { width: 100%; }

        /* ── Hero ────────────────────────────────────── */
        .hero-image {
            background-image: url('<?= htmlspecialchars($img_hero) ?>');
            background-size: cover;
            background-position: center 35%;
        }

        /* ── Botones ─────────────────────────────────── */
        .btn-amber {
            display: inline-block;
            background: var(--amber);
            color: #fff;
            padding: .78rem 1.9rem;
            border-radius: 3px;
            font-weight: 500;
            font-size: .875rem;
            letter-spacing: .05em;
            transition: background .25s, transform .2s, box-shadow .25s;
        }
        .btn-amber:hover {
            background: #a8622e;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(200,120,58,.35);
        }
        .btn-ghost {
            display: inline-block;
            border: 1px solid var(--amber);
            color: var(--amber);
            padding: .72rem 1.75rem;
            border-radius: 3px;
            font-weight: 500;
            font-size: .875rem;
            letter-spacing: .05em;
            transition: all .25s;
        }
        .btn-ghost:hover { background: var(--amber); color: #fff; transform: translateY(-2px); }

        /* ── Etiqueta amber con línea ────────────────── */
        .amber-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--amber);
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .2em;
            text-transform: uppercase;
        }
        .amber-tag::before {
            content: '';
            display: block;
            width: 32px; height: 1px;
            background: var(--amber);
        }

        /* ── Feature item ────────────────────────────── */
        .feat-item { border-top: 1px solid rgba(200,120,58,.25); transition: border-color .3s; }
        .feat-item:hover { border-color: var(--amber); }

        .feat-icon {
            width: 50px; height: 50px;
            border-radius: 50%;
            border: 1px solid rgba(200,120,58,.4);
            display: flex; align-items: center; justify-content: center;
            color: var(--amber);
            font-size: 1.15rem;
            margin-bottom: .9rem;
            transition: background .3s, border-color .3s, color .3s;
        }
        .feat-item:hover .feat-icon { background: var(--amber); border-color: var(--amber); color: #fff; }

        /* ── Línea punteada del menú ─────────────────── */
        .dots-line {
            flex: 1;
            border-bottom: 2px dotted rgba(200,120,58,.2);
            margin: 0 8px;
            position: relative;
            top: -4px;
        }

        /* ── Product card ────────────────────────────── */
        .prod-card { transition: transform .4s cubic-bezier(.22,.68,0,1.2), box-shadow .4s; }
        .prod-card:hover { transform: translateY(-10px); box-shadow: 0 28px 60px rgba(0,0,0,.55); }
        .prod-card:hover .prod-img { transform: scale(1.08); }
        .prod-img { transition: transform .55s cubic-bezier(.22,.68,0,1.2); }

        /* ── Badge ───────────────────────────────────── */
        .badge {
            background: var(--amber);
            color: #fff;
            font-size: .6rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 2px 9px;
            border-radius: 2px;
        }

        /* ── Formulario de reserva ───────────────────── */
        .f-input {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(200,120,58,.28);
            color: var(--cream);
            font-family: 'Outfit', sans-serif;
            font-size: .875rem;
            padding: .7rem .2rem;
            outline: none;
            transition: border-color .3s;
        }
        .f-input::placeholder { color: rgba(240,230,208,.3); }
        .f-input:focus { border-color: var(--amber); }
        .f-label {
            display: block;
            font-size: .68rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(240,230,208,.4);
            margin-bottom: 2px;
        }

        /* ── Social icon ─────────────────────────────── */
        .soc {
            width: 37px; height: 37px;
            border-radius: 50%;
            border: 1px solid rgba(200,120,58,.3);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .82rem;
            color: rgba(240,230,208,.55);
            transition: all .25s;
        }
        .soc:hover { background: var(--amber); border-color: var(--amber); color: #fff; transform: translateY(-2px); }

        /* ── Fade-in reveal ──────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .7s ease, transform .7s ease;
        }
        .reveal.show { opacity: 1; transform: none; }
    </style>
</head>
<body>

<!-- ================================================================
     NAVBAR
     ================================================================ -->
<nav id="navbar" class="fixed top-0 left-0 w-full z-50 bg-transparent" aria-label="Navegación principal">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-16">

        <!-- Logo -->
        <a href="#inicio" class="flex items-center gap-2.5 shrink-0" aria-label="Inicio">
            <span class="w-9 h-9 rounded-full border border-amber/60 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-mug-hot text-amber text-sm" aria-hidden="true"></i>
            </span>
            <span class="font-display text-lg font-semibold text-cream tracking-wide">
                <?= htmlspecialchars($cafe_nombre) ?>
            </span>
        </a>

        <!-- Links desktop -->
        <div class="hidden md:flex items-center gap-7">
            <a href="#nosotros" class="nav-link">Sobre Nosotros</a>
            <a href="#carta"    class="nav-link">Menú</a>
            <a href="#reserva"  class="nav-link">Reserva</a>
            <a href="#galeria"  class="nav-link">Galería</a>
            <a href="#contacto" class="nav-link">Contacto</a>
        </div>

        <!-- Acciones desktop -->
        <div class="hidden md:flex items-center gap-4">
            <a href="/login" class="nav-link">
                <i class="fa-regular fa-circle-user mr-1.5" aria-hidden="true"></i>Login
            </a>
            <a href="#reserva" class="btn-amber py-2 px-5 text-xs">Reservar mesa</a>
        </div>

        <!-- Hamburger -->
        <button id="ham-btn" class="md:hidden text-cream p-2" aria-label="Abrir menú" aria-expanded="false">
            <i id="ham-open"  class="fa-solid fa-bars   text-lg" aria-hidden="true"></i>
            <i id="ham-close" class="fa-solid fa-xmark  text-lg hidden" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Menú mobile -->
    <div id="mob-menu" class="hidden md:hidden bg-ink/97 border-t border-border px-6 py-5 space-y-3">
        <a href="#nosotros" class="block nav-link py-1.5">Sobre Nosotros</a>
        <a href="#carta"    class="block nav-link py-1.5">Menú</a>
        <a href="#reserva"  class="block nav-link py-1.5">Reserva</a>
        <a href="#galeria"  class="block nav-link py-1.5">Galería</a>
        <a href="#contacto" class="block nav-link py-1.5">Contacto</a>
        <div class="flex gap-3 pt-2">
            <a href="login.php"  class="btn-ghost flex-1 text-center text-xs">Login</a>
            <a href="admin.php"  class="btn-amber flex-1 text-center text-xs">Admin</a>
        </div>
    </div>
</nav>


<!-- ================================================================
     HERO — Texto a la izquierda, imagen cubriendo parte derecha
     (inspirado en la composición split de la imagen 1)
     ================================================================ -->
<section id="inicio" class="relative min-h-screen flex items-center overflow-hidden grain" aria-label="Portada">

    <!-- Fondo negro base -->
    <div class="absolute inset-0 bg-ink"></div>

    <!-- Imagen ocupa 60% derecho -->
    <div class="absolute right-0 top-0 w-full md:w-[62%] h-full hero-image" style="opacity:.8"></div>

    <!-- Gradiente izquierda→transparente + top/bottom -->
    <div class="absolute inset-0 bg-linear-to-r from-ink via-ink/90 to-transparent"></div>
    <div class="absolute inset-0 bg-linear-to-t from-ink/70 via-transparent to-ink/40"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full pt-28 pb-20">
        <div class="max-w-130">

            <p class="amber-tag mb-6 reveal"><?= htmlspecialchars($cafe_subtag) ?></p>

            <h1 class="font-display leading-[1.05] mb-3 reveal" style="transition-delay:.1s">
                <span class="block text-cream/70 text-3xl md:text-4xl font-normal italic">
                    <?= htmlspecialchars($cafe_slogan) ?>
                </span>
                <span class="block text-cream text-[clamp(3.5rem,8vw,6rem)] font-bold uppercase tracking-tight">
                    <?= strtoupper(htmlspecialchars($cafe_titulo)) ?>
                </span>
            </h1>

            <p class="text-cream/50 text-sm md:text-base leading-relaxed max-w-sm mb-10 reveal" style="transition-delay:.2s">
                <?= htmlspecialchars($cafe_subtitulo) ?>
            </p>

            <div class="flex flex-wrap gap-3 reveal" style="transition-delay:.3s">
                <a href="#carta"    class="btn-amber">
                    <i class="fa-solid fa-book-open-reader mr-2" aria-hidden="true"></i>Ver Menú
                </a>
                <a href="#nosotros" class="btn-ghost">
                    <i class="fa-solid fa-circle-info mr-2" aria-hidden="true"></i>Más info
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll cue -->
    <div class="absolute bottom-7 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-1.5 animate-bounce opacity-35" aria-hidden="true">
        <span class="text-cream text-[9px] tracking-[0.22em] uppercase">Scroll</span>
        <i class="fa-solid fa-chevron-down text-amber text-xs"></i>
    </div>
</section>


<!-- ================================================================
     FEATURES — 4 bloques con íconos FA (ref. imagen 1, fila de iconos)
     ================================================================ -->
<section class="bg-surface border-y border-border py-14 px-6" aria-label="Ventajas">
    <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
        <?php foreach ($features as $i => $f): ?>
        <div class="feat-item pt-5 reveal" style="transition-delay:<?= $i * .08 ?>s">
            <div class="feat-icon">
                <i class="<?= htmlspecialchars($f['icono']) ?>" aria-hidden="true"></i>
            </div>
            <h3 class="text-cream text-xs font-semibold uppercase tracking-widets mb-1.5">
                <?= htmlspecialchars($f['titulo']) ?>
            </h3>
            <p class="text-muted text-sm leading-relaxed">
                <?= htmlspecialchars($f['texto']) ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
</section>


<!-- ================================================================
     SOBRE NOSOTROS + FORMULARIO DE RESERVA
     (ref. imagen 1: sección de 2 columnas — texto/foto + form)
     ================================================================ -->
<section id="nosotros" class="bg-dark py-24 px-6 relative grain overflow-hidden" aria-label="Sobre nosotros">

    <!-- Glow decorativo -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-14 items-start relative z-10">

        <!-- Columna: texto + stats + imagen -->
        <div class="reveal">
            <p class="amber-tag mb-4">Sobre nosotros</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream leading-tight mb-5">
                El mejor momento<br>para una <em class="text-amber not-italic">taza de café</em>
            </h2>
            <p class="text-cream/52 text-sm md:text-base leading-relaxed mb-8 max-w-md">
                <?= htmlspecialchars($cafe_descripcion) ?>
            </p>

            <!-- Imagen con stats superpuestos -->
            <div class="relative rounded-lg overflow-hidden max-w-sm">
                <img
                    src="<?= htmlspecialchars($img_about) ?>"
                    alt="Interior de <?= htmlspecialchars($cafe_nombre) ?>"
                    class="w-full h-60 object-cover"
                    loading="lazy"
                >
                <div class="absolute inset-0 bg-linear-to-t from-ink/80 to-transparent"></div>
                <div class="absolute bottom-4 left-0 right-0 flex justify-around px-4">
                    <div class="text-center">
                        <div class="font-display text-3xl font-bold text-amber">12+</div>
                        <div class="text-cream/55 text-[10px] uppercase tracking-widest">Orígenes</div>
                    </div>
                    <div class="w-px bg-cream/15 self-stretch"></div>
                    <div class="text-center">
                        <div class="font-display text-3xl font-bold text-amber">5★</div>
                        <div class="text-cream/55 text-[10px] uppercase tracking-widest">Calidad</div>
                    </div>
                    <div class="w-px bg-cream/15 self-stretch"></div>
                    <div class="text-center">
                        <div class="font-display text-3xl font-bold text-amber">8+</div>
                        <div class="text-cream/55 text-[10px] uppercase tracking-widest">Años</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna: formulario reserva -->
        <div id="reserva" class="bg-card border border-border rounded-lg p-8 reveal" style="transition-delay:.14s">
            <h3 class="font-display text-2xl font-semibold text-cream mb-1">
                <i class="fa-regular fa-calendar-check text-amber mr-2 text-xl" aria-hidden="true"></i>
                Reservar una mesa
            </h3>
            <p class="text-muted text-sm mb-7">Asegura tu lugar favorito en nuestra cafetería</p>

            <form action="reserva.php" method="post" class="space-y-5" novalidate>
                <div>
                    <label class="f-label" for="r-nombre">
                        <i class="fa-regular fa-user mr-1" aria-hidden="true"></i> Nombre completo
                    </label>
                    <input id="r-nombre" type="text" name="nombre" class="f-input" placeholder="Tu nombre" required>
                </div>
                <div>
                    <label class="f-label" for="r-tel">
                        <i class="fa-solid fa-phone mr-1" aria-hidden="true"></i> Teléfono
                    </label>
                    <input id="r-tel" type="tel" name="telefono" class="f-input" placeholder="+51 000 000 000" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="f-label" for="r-fecha">
                            <i class="fa-regular fa-calendar mr-1" aria-hidden="true"></i> Fecha
                        </label>
                        <input id="r-fecha" type="date" name="fecha" class="f-input" required>
                    </div>
                    <div>
                        <label class="f-label" for="r-personas">
                            <i class="fa-solid fa-users mr-1" aria-hidden="true"></i> Personas
                        </label>
                        <input id="r-personas" type="number" name="personas" min="1" max="20" class="f-input" placeholder="2" required>
                    </div>
                </div>
                <div>
                    <label class="f-label" for="r-nota">
                        <i class="fa-regular fa-message mr-1" aria-hidden="true"></i> Nota adicional
                    </label>
                    <input id="r-nota" type="text" name="nota" class="f-input" placeholder="Cumpleaños, alergias...">
                </div>
                <button type="submit" class="btn-amber w-full text-center mt-1">
                    <i class="fa-solid fa-paper-plane mr-2" aria-hidden="true"></i>Enviar reserva
                </button>
            </form>
        </div>
    </div>
</section>


<!-- ================================================================
     MENÚ / CARTA — Lista punteada por categorías
     (ref. imagen 1: sección inferior con ítems y precios)
     ================================================================ -->
<section id="carta" class="bg-ink py-24 px-6" aria-label="Carta del menú">
    <div class="max-w-7xl mx-auto">

        <!-- Encabezado -->
        <div class="text-center mb-14 reveal">
            <p class="amber-tag justify-center mb-3">Bebidas y comida</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream uppercase tracking-wide">
                Lo más delicioso<br>de nuestra carta
            </h2>
        </div>

        <!-- Dos columnas de categorías -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16">
            <?php foreach ($menu_categorias as $ci => $cat): ?>
            <div class="reveal" style="transition-delay:<?= $ci * .13 ?>s">

                <!-- Encabezado de categoría con imagen circular -->
                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-amber/20">
                    <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 border-2 border-amber/40">
                        <img
                            src="<?= htmlspecialchars($cat['imagen']) ?>"
                            alt="<?= htmlspecialchars($cat['nombre']) ?>"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>
                    <h3 class="font-display text-2xl font-semibold text-amber uppercase tracking-wide">
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </h3>
                </div>

                <!-- Ítems con línea punteada -->
                <ul class="space-y-4">
                    <?php foreach ($cat['items'] as $item): ?>
                    <li>
                        <div class="flex items-end gap-1">
                            <span class="font-body font-medium text-cream text-sm uppercase tracking-wide shrink-0">
                                <?= htmlspecialchars($item['nombre']) ?>
                            </span>
                            <span class="dots-line"></span>
                            <span class="text-amber font-semibold text-sm shrink-0">
                                <?= htmlspecialchars($item['precio']) ?>
                            </span>
                        </div>
                        <p class="text-muted text-xs mt-0.5 leading-relaxed">
                            <?= htmlspecialchars($item['descripcion']) ?>
                        </p>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-14 reveal">
            <a href="#carta" class="btn-ghost">
                <i class="fa-solid fa-utensils mr-2" aria-hidden="true"></i>Ver carta completa
            </a>
        </div>
    </div>
</section>


<!-- ================================================================
     PRODUCTOS DESTACADOS — Cards con foto
     (ref. imagen 2: featured drinks en cards)
     ================================================================ -->
<section id="galeria" class="bg-surface py-24 px-6" aria-label="Especialidades">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-14 reveal">
            <p class="amber-tag justify-center mb-3">Bebidas destacadas</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">
                Nuestras especialidades
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-7">
            <?php foreach ($productos as $i => $prod): ?>
            <article class="prod-card bg-card border border-border rounded-lg overflow-hidden flex flex-col reveal" style="transition-delay:<?= $i * .11 ?>s">

                <!-- Imagen con zoom en hover -->
                <div class="overflow-hidden relative h-60">
                    <img
                        src="<?= htmlspecialchars($prod['imagen']) ?>"
                        alt="<?= htmlspecialchars($prod['nombre']) ?>"
                        class="prod-img w-full h-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-linear-to-t from-ink/55 to-transparent"></div>

                    <?php if (!empty($prod['badge'])): ?>
                    <span class="badge absolute top-3 right-3"><?= htmlspecialchars($prod['badge']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Contenido -->
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-display text-xl font-semibold text-cream mb-1">
                        <?= htmlspecialchars($prod['nombre']) ?>
                    </h3>
                    <p class="text-muted text-sm leading-relaxed flex-1 mb-4">
                        <?= htmlspecialchars($prod['descripcion']) ?>
                    </p>
                    <div class="flex items-center justify-between border-t border-border pt-4">
                        <span class="font-display text-2xl font-bold text-amber">
                            <?= htmlspecialchars($prod['precio']) ?>
                        </span>
                        <button
                            class="text-xs font-medium text-cream border border-border px-3 py-1.5 rounded hover:bg-amber hover:border-amber hover:text-white transition-all duration-200"
                            aria-label="Pedir <?= htmlspecialchars($prod['nombre']) ?>"
                        >
                            <i class="fa-solid fa-plus mr-1" aria-hidden="true"></i>Pedir
                        </button>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ================================================================
     BANNER PROMOCIONAL — 2 columnas (ref. imagen 2: promo banners)
     ================================================================ -->
<section class="grid grid-cols-1 md:grid-cols-2" aria-label="Promociones">

    <!-- Banner oscuro con imagen de fondo -->
    <div class="relative h-64 flex items-center px-10 overflow-hidden" style="background-image:url('https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=900&q=80'); background-size:cover; background-position:center;">
        <div class="absolute inset-0 bg-ink/70"></div>
        <div class="relative z-10">
            <p class="amber-tag mb-2 reveal">Oferta especial</p>
            <h3 class="font-display text-3xl font-bold text-cream mb-3 reveal" style="transition-delay:.1s">
                2 Cold Brew<br>por el precio de 1
            </h3>
            <a href="#carta" class="btn-amber text-xs py-2.5 reveal" style="transition-delay:.2s">
                <i class="fa-solid fa-tag mr-1.5" aria-hidden="true"></i>Ver promoción
            </a>
        </div>
    </div>

    <!-- Banner amber sólido — Daily deal -->
    <div class="relative h-64 flex items-center px-10 overflow-hidden bg-amber">
        <div class="absolute -right-10 -bottom-10 w-56 h-56 rounded-full bg-white/10 pointer-events-none" aria-hidden="true"></div>
        <div class="absolute right-6 top-6 w-28 h-28 rounded-full bg-white/6 pointer-events-none" aria-hidden="true"></div>
        <div class="relative z-10">
            <p class="text-white/65 text-[10px] font-semibold uppercase tracking-[.18em] mb-1.5 reveal">Deal del día</p>
            <h3 class="font-display text-3xl font-bold text-white mb-1.5 reveal" style="transition-delay:.1s">
                Latte + Croissant
            </h3>
            <p class="text-white/75 text-sm mb-3 reveal" style="transition-delay:.15s">Combo perfecto para empezar el día</p>
            <div class="flex items-baseline gap-2 reveal" style="transition-delay:.2s">
                <span class="font-display text-4xl font-bold text-white">S/ 18</span>
                <span class="text-white/55 line-through text-lg">S/ 23</span>
            </div>
        </div>
    </div>
</section>


<!-- ================================================================
     CTA — Visítanos / Encuéntranos
     ================================================================ -->
<section id="contacto" class="bg-dark grain relative overflow-hidden py-24 px-6" aria-label="Contacto">

    <div class="absolute top-0 right-0 w-80 h-80 bg-amber/4 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 bg-amber/4 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-3xl mx-auto text-center relative z-10">
        <p class="amber-tag justify-center mb-5 reveal">Te esperamos</p>
        <h2 class="font-display text-5xl md:text-6xl font-bold text-cream leading-tight mb-5 reveal" style="transition-delay:.1s">
            ¿Listo para vivir<br>la <em class="text-amber not-italic">experiencia</em>?
        </h2>
        <p class="text-cream/45 text-sm md:text-base mb-10 leading-relaxed reveal" style="transition-delay:.2s">
            Visítanos, reserva tu mesa o pídenos por WhatsApp.<br>El mejor café de tu semana está a un clic.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12 reveal" style="transition-delay:.28s">
            <?php if (!empty($redes['whatsapp'])): ?>
            <a href="<?= htmlspecialchars($redes['whatsapp']) ?>" target="_blank" rel="noopener" class="btn-amber">
                <i class="fa-brands fa-whatsapp mr-2 text-base" aria-hidden="true"></i>Pedir por WhatsApp
            </a>
            <?php endif; ?>
            <a href="#reserva" class="btn-ghost">
                <i class="fa-regular fa-calendar-check mr-2" aria-hidden="true"></i>Reservar mesa
            </a>
        </div>

        <!-- Info en 3 bloques -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-10 border-t border-border reveal" style="transition-delay:.34s">
            <div class="flex flex-col items-center gap-2">
                <i class="fa-solid fa-location-dot text-amber text-lg" aria-hidden="true"></i>
                <p class="text-cream/45 text-xs text-center leading-relaxed"><?= htmlspecialchars($cafe_direccion) ?></p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <i class="fa-regular fa-clock text-amber text-lg" aria-hidden="true"></i>
                <p class="text-cream/45 text-xs text-center leading-relaxed"><?= htmlspecialchars($cafe_horario) ?></p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <i class="fa-regular fa-envelope text-amber text-lg" aria-hidden="true"></i>
                <a href="mailto:<?= htmlspecialchars($cafe_email) ?>" class="text-cream/45 text-xs hover:text-amber transition">
                    <?= htmlspecialchars($cafe_email) ?>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ================================================================
     FOOTER
     ================================================================ -->
<footer class="bg-ink border-t border-border pt-14 pb-8 px-6" aria-label="Pie de página">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 pb-10 border-b border-border">

            <!-- Marca + redes -->
            <div class="sm:col-span-2">
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="w-9 h-9 rounded-full border border-amber/50 flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-amber text-sm" aria-hidden="true"></i>
                    </span>
                    <span class="font-display text-lg font-semibold text-cream">
                        <?= htmlspecialchars($cafe_nombre) ?>
                    </span>
                </div>
                <p class="text-muted text-sm leading-relaxed max-w-xs mb-5">
                    Café de especialidad tostado con amor en Lima, directo del campo peruano a tu taza.
                </p>
                <div class="flex gap-2.5">
                    <?php if (!empty($redes['instagram'])): ?>
                    <a href="<?= htmlspecialchars($redes['instagram']) ?>" target="_blank" rel="noopener" class="soc" aria-label="Instagram">
                        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($redes['facebook'])): ?>
                    <a href="<?= htmlspecialchars($redes['facebook']) ?>" target="_blank" rel="noopener" class="soc" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($redes['tiktok'])): ?>
                    <a href="<?= htmlspecialchars($redes['tiktok']) ?>" target="_blank" rel="noopener" class="soc" aria-label="TikTok">
                        <i class="fa-brands fa-tiktok" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($redes['whatsapp'])): ?>
                    <a href="<?= htmlspecialchars($redes['whatsapp']) ?>" target="_blank" rel="noopener" class="soc" aria-label="WhatsApp">
                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Navegación -->
            <div>
                <h4 class="text-cream text-[10px] font-semibold uppercase tracking-[.16em] mb-4">Navegación</h4>
                <ul class="space-y-2 text-sm text-muted">
                    <li><a href="#nosotros" class="hover:text-amber transition">Sobre Nosotros</a></li>
                    <li><a href="#carta"    class="hover:text-amber transition">Menú</a></li>
                    <li><a href="#galeria"  class="hover:text-amber transition">Galería</a></li>
                    <li><a href="#reserva"  class="hover:text-amber transition">Reservas</a></li>
                    <li><a href="login.php" class="hover:text-amber transition">Login</a></li>
                    <li><a href="admin.php" class="hover:text-amber transition">Administración</a></li>
                </ul>
            </div>

            <!-- Contacto -->
            <div>
                <h4 class="text-cream text-[10px] font-semibold uppercase tracking-[.16em] mb-4">Contacto</h4>
                <ul class="space-y-3 text-sm text-muted">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-location-dot text-amber mt-0.5 shrink-0 text-xs" aria-hidden="true"></i>
                        <?= htmlspecialchars($cafe_direccion) ?>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-amber shrink-0 text-xs" aria-hidden="true"></i>
                        <a href="tel:<?= preg_replace('/\s+/', '', htmlspecialchars($cafe_telefono)) ?>" class="hover:text-amber transition">
                            <?= htmlspecialchars($cafe_telefono) ?>
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-regular fa-envelope text-amber shrink-0 text-xs" aria-hidden="true"></i>
                        <a href="mailto:<?= htmlspecialchars($cafe_email) ?>" class="hover:text-amber transition break-all">
                            <?= htmlspecialchars($cafe_email) ?>
                        </a>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-regular fa-clock text-amber mt-0.5 shrink-0 text-xs" aria-hidden="true"></i>
                        <span class="leading-snug"><?= htmlspecialchars($cafe_horario) ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-muted text-xs">
            <span>&copy; <?= date('Y') ?> <?= htmlspecialchars($cafe_nombre) ?>. Todos los derechos reservados.</span>
            <span class="flex items-center gap-1.5">
                <i class="fa-solid fa-mug-hot text-amber text-[10px]" aria-hidden="true"></i>
                Hecho con café en Lima, Perú
            </span>
        </div>
    </div>
</footer>


<!-- ================================================================
     JAVASCRIPT
     ================================================================ -->
<script>
    /* ── Navbar al hacer scroll ──────────────────────────────── */
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 55);
    }, { passive: true });

    /* ── Menú mobile hamburger ───────────────────────────────── */
    const hamBtn   = document.getElementById('ham-btn');
    const mobMenu  = document.getElementById('mob-menu');
    const hamOpen  = document.getElementById('ham-open');
    const hamClose = document.getElementById('ham-close');

    hamBtn.addEventListener('click', () => {
        const isOpen = !mobMenu.classList.contains('hidden');
        mobMenu.classList.toggle('hidden');
        hamOpen.classList.toggle('hidden',  !isOpen);
        hamClose.classList.toggle('hidden',  isOpen);
        hamBtn.setAttribute('aria-expanded', String(!isOpen));
    });

    /* Cerrar al hacer clic en un link del menú mobile */
    mobMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            mobMenu.classList.add('hidden');
            hamOpen.classList.remove('hidden');
            hamClose.classList.add('hidden');
            hamBtn.setAttribute('aria-expanded', 'false');
        });
    });

    /* ── Reveal al hacer scroll (Intersection Observer) ─────── */
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('show');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>

</body>
</html>