<?php

return [
    /* --- Identidad de la cafetería --- */
    'nombre'    => 'Raíz & Grano',
    'slogan'    => 'Tómate un descanso',
    'titulo'    => 'y bebe café',
    'subtitulo' => 'En nuestra acogedora cafetería, en buena compañía, con socios de negocios o con personas que piensan igual que tú.',
    'descripcion' => '¡Bienvenido a nuestra acogedora cafetería! Es un lugar excelente donde puedes pasar el tiempo tomando una taza de café, con buena música y un ambiente increíble. Aquí puedes relajarte con amigos, trabajar o simplemente disfrutar del maravilloso aroma del café. Estaremos encantados de verte cada día.',
    'subtag'    => 'Café de especialidad · Piura, Perú',

    /* --- Logo (dejar vacío para usar texto+icono) --- */
    'logo' => '',

    /* --- Imágenes principales --- */
    'hero_img'  => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400&q=85',
    'about_img' => 'https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=800&q=80',

    /* --- Horario y contacto --- */
    'horario'   => 'Lun – Vie: 7 am – 8 pm · Sáb – Dom: 8 am – 6 pm',
    'direccion' => 'Av. La Mar 620, Piura, Perú',
    'email'     => 'hola@raizygrano.pe',
    'telefono'  => '+51 999 999 999',

    /* --- Redes sociales (dejar vacío para ocultar el ícono) --- */
    'redes' => [
        'instagram' => 'https://instagram.com',
        'facebook'  => 'https://facebook.com',
        'tiktok'    => 'https://tiktok.com',
        'whatsapp'  => 'https://wa.me/51999999999',
    ],

    /* --- Características / ventajas — usar clases de Font Awesome 6 --- */
    'features' => [
        ['icono' => 'fa-solid fa-mug-hot',      'titulo' => 'Los mejores granos',   'texto' => 'Elige tu sabor de café favorito'],
        ['icono' => 'fa-solid fa-wifi',         'titulo' => 'Wi-Fi gratuito',        'texto' => 'Conversa con amigos o resuelve asuntos de trabajo'],
        ['icono' => 'fa-solid fa-cookie-bite',  'titulo' => 'Deliciosos postres',    'texto' => 'Nunca te quedarás con hambre'],
        ['icono' => 'fa-solid fa-bag-shopping', 'titulo' => 'Café para llevar',      'texto' => 'Toma café aquí o llévate uno'],
    ],

    /* --- Menú por categorías — lista con precios punteados --- */
    'menu_categorias' => [
        [
            'nombre' => 'Café caliente',
            'imagen' => 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=420&q=80',
            'items'  => [
                ['nombre' => 'Cappuccino',  'descripcion' => 'Espresso con leche y abundante espuma',          'precio' => 'S/ 10'],
                ['nombre' => 'Americano',   'descripcion' => 'Espresso suave con agua caliente',               'precio' => 'S/ 8'],
                ['nombre' => 'Flat White',  'descripcion' => 'Doble ristretto con leche aterciopelada',        'precio' => 'S/ 12'],
                ['nombre' => 'Latte',       'descripcion' => 'Espresso cremoso con leche vaporizada',          'precio' => 'S/ 11'],
            ],
        ],
        [
            'nombre' => 'Repostería y postres',
            'imagen' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=420&q=80',
            'items'  => [
                ['nombre' => 'Croissant',   'descripcion' => 'Clásico croissant con textura mantecosa',        'precio' => 'S/ 9'],
                ['nombre' => 'Cheesecake',  'descripcion' => 'Tarta de queso cremosa con base de galleta',    'precio' => 'S/ 14'],
                ['nombre' => 'Brownie',     'descripcion' => 'Brownie de chocolate amargo y nueces',           'precio' => 'S/ 10'],
                ['nombre' => 'Muffin',      'descripcion' => 'Muffin esponjoso con chips de espresso',        'precio' => 'S/ 8'],
            ],
        ],
    ],

    /* --- Productos destacados (cards con foto) --- */
    'productos' => [
        [
            'nombre'      => 'Espresso Reserva',
            'descripcion' => 'Blend exclusivo de altura, cuerpo intenso con notas a chocolate oscuro.',
            'precio'      => 'S/ 9',
            'imagen'      => 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=600&q=80',
            'badge'       => 'Firma',
        ],
        [
            'nombre'      => 'Latte de Lúcuma',
            'descripcion' => 'Espresso suave con leche vaporizada y jarabe artesanal de lúcuma peruana.',
            'precio'      => 'S/ 14',
            'imagen'      => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=600&q=80',
            'badge'       => 'Favorito',
        ],
        [
            'nombre'      => 'Cold Brew 24h',
            'descripcion' => 'Extracción en frío durante 24 horas. Suave, bajo en acidez y muy refrescante.',
            'precio'      => 'S/ 16',
            'imagen'      => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80',
            'badge'       => 'Nuevo',
        ],
    ],
];
