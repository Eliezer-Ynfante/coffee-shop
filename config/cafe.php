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
    'about_img' => 'https://img.freepik.com/fotos-premium/marco-granos-cafe-espacio-copia-fondo-negro_677155-108.jpg',

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
            'nombre'      => 'Cold Brew',
            'descripcion' => 'Extracción en frío durante 24 horas. Suave, bajo en acidez y muy refrescante.',
            'precio'      => 'S/ 16',
            'imagen'      => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80',
            'badge'       => 'Nuevo',
        ],
    ],

    /* --- Galería fotográfica --- */
    'galeria_categorias' => [
        ['id' => 'todos',    'nombre' => 'Todos'],
        ['id' => 'cafe',     'nombre' => 'Café & Barismo'],
        ['id' => 'ambiente', 'nombre' => 'Espacios & Local'],
        ['id' => 'postres',  'nombre' => 'Repostería & Postres'],
        ['id' => 'procesos', 'nombre' => 'Tueste & Origen'],
    ],

    'galeria' => [
        [
            'titulo'      => 'Arte Latte en Flat White',
            'categoria'   => 'cafe',
            'categoria_nombre' => 'Café & Barismo',
            'descripcion' => 'Diseño cisne vertido a mano alzada con leche texturizada a 65°C.',
            'imagen'      => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=900&q=85',
            'badge'       => 'Arte Latte',
        ],
        [
            'titulo'      => 'Nuestra Barra de Especialidad',
            'categoria'   => 'ambiente',
            'categoria_nombre' => 'Espacios & Local',
            'descripcion' => 'Diseño minimalista con madera cálida e iluminación ámbar para disfrutar el ritual.',
            'imagen'      => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=900&q=85',
            'badge'       => 'Barra',
        ],
        [
            'titulo'      => 'Croissants recién horneados',
            'categoria'   => 'postres',
            'categoria_nombre' => 'Repostería & Postres',
            'descripcion' => 'Hojaldre artesanal francés con 100% mantequilla de campo.',
            'imagen'      => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=900&q=85',
            'badge'       => 'Horneado Diario',
        ],
        [
            'titulo'      => 'Extracción en Chemex',
            'categoria'   => 'cafe',
            'categoria_nombre' => 'Café & Barismo',
            'descripcion' => 'Método de goteo limpio que resalta las notas cítricas y florales del café de origen.',
            'imagen'      => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=900&q=85',
            'badge'       => 'Métodos',
        ],
        [
            'titulo'      => 'Tueste Artesanal de Granos',
            'categoria'   => 'procesos',
            'categoria_nombre' => 'Tueste & Origen',
            'descripcion' => 'Perfil de tueste medio desarrollado en pequeños lotes para preservar los azúcares.',
            'imagen'      => 'https://images.unsplash.com/photo-1518832553480-cd0e625ed3e6?w=900&q=85',
            'badge'       => 'Tostaduría',
        ],
        [
            'titulo'      => 'Rincón de Lectura & Coworking',
            'categoria'   => 'ambiente',
            'categoria_nombre' => 'Espacios & Local',
            'descripcion' => 'Espacio tranquilo con buena conexión Wi-Fi, luz natural y música jazz suave.',
            'imagen'      => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=900&q=85',
            'badge'       => 'Espacios',
        ],
        [
            'titulo'      => 'Cheesecake de Frutos Rojos',
            'categoria'   => 'postres',
            'categoria_nombre' => 'Repostería & Postres',
            'descripcion' => 'Crema suave de queso horneada con compota fresca de berries.',
            'imagen'      => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900&q=85',
            'badge'       => 'Repostería',
        ],
        [
            'titulo'      => 'Selección de Grano Verde en Origen',
            'categoria'   => 'procesos',
            'categoria_nombre' => 'Tueste & Origen',
            'descripcion' => 'Cosecha a mano a más de 1,600 msnm en los valles cafetaleros del norte peruano.',
            'imagen'      => 'https://images.unsplash.com/photo-1498804103079-a6351b050096?w=900&q=85',
            'badge'       => 'Origen',
        ],
        [
            'titulo'      => 'Cold Brew con Naranja',
            'categoria'   => 'cafe',
            'categoria_nombre' => 'Café & Barismo',
            'descripcion' => '24 horas de infusión en frío combinado con piel de naranja caramelizada.',
            'imagen'      => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=900&q=85',
            'badge'       => 'Especial',
        ],
    ],

    /* --- Datos extendidos para Sobre Nosotros --- */
    'nosotros_stats' => [
        ['valor' => '12+',  'etiqueta' => 'Orígenes de Altura',  'subtexto' => 'Cajamarca, Cusco, Junín y Piura'],
        ['valor' => '86+',  'etiqueta' => 'Puntos SCA',          'subtexto' => 'Cafés de especialidad certificados'],
        ['valor' => '100%', 'etiqueta' => 'Comercio Directo',    'subtexto' => 'Trato directo con pequeños caficultores'],
        ['valor' => '+8',   'etiqueta' => 'Años Tostando',       'subtexto' => 'Pasión y maestría en cada batch'],
    ],

    'pilares' => [
        [
            'icono'  => 'fa-solid fa-mountain-sun',
            'titulo' => 'Origen & Trazabilidad',
            'desc'   => 'Trabajamos exclusivamente con micro-lotes de estricta altura (1,400 - 2,100 msnm), conociendo el nombre del productor, la variedad y el terruño.',
        ],
        [
            'icono'  => 'fa-solid fa-fire-burner',
            'titulo' => 'Tueste en Pequeños Lotes',
            'desc'   => 'Tostamos artesanalmente en pequeños batches semanales para resaltar los azúcares naturales, notas frutales y acidez balanceada de cada grano.',
        ],
        [
            'icono'  => 'fa-solid fa-hand-holding-heart',
            'titulo' => 'Comercio Justo y Humano',
            'desc'   => 'Creemos en una cadena de valor transparente, pagando precios justos y sostenibles que impulsan el desarrollo de las familias del campo.',
        ],
        [
            'icono'  => 'fa-solid fa-mug-saucer',
            'titulo' => 'El Ritual del Barismo',
            'desc'   => 'Cada taza es preparada con técnica, temperatura y molienda calibrada al segundo, convirtiendo tu café diario en una experiencia memorable.',
        ],
    ],

    'proceso_cafe' => [
        [
            'fase'   => '01',
            'titulo' => 'Cosecha Manual en Altura',
            'desc'   => 'Recolección selectiva manual de cerezas 100% maduras en las laderas andinas y de selva alta.',
            'icono'  => 'fa-solid fa-seedling',
            'imagen' => 'https://images.unsplash.com/photo-1524350876685-274059332603?w=700&q=80',
        ],
        [
            'fase'   => '02',
            'titulo' => 'Beneficiado & Secado Solar',
            'desc'   => 'Procesos lavados, honeys y naturales secados lentamente en camas elevadas bajo sombra y sol andino.',
            'icono'  => 'fa-solid fa-sun',
            'imagen' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=700&q=80',
        ],
        [
            'fase'   => '03',
            'titulo' => 'Tueste & Catación SCA',
            'desc'   => 'Curvas de tueste a medida y rigurosa evaluación en mesa de catación para garantizar notas limpias.',
            'icono'  => 'fa-solid fa-fire',
            'imagen' => 'https://images.unsplash.com/photo-1518832553480-cd0e625ed3e6?w=700&q=80',
        ],
        [
            'fase'   => '04',
            'titulo' => 'Extracción Calibrada',
            'desc'   => 'Espresso y métodos artesanales (V60, Chemex, Aeropress) ejecutados por baristas certificados.',
            'icono'  => 'fa-solid fa-mug-hot',
            'imagen' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=700&q=80',
        ],
    ],

    'equipo' => [
        [
            'nombre'      => 'Mateo Vargas',
            'rol'         => 'Head Roaster & Q-Grader',
            'especialidad'=> 'Curvas de Tueste & Catación',
            'imagen'      => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&q=80',
            'bio'         => 'Certificado por el Coffee Quality Institute. Obsesionado con balancear dulzor, cuerpo y acidez brillante en cada origen.',
        ],
        [
            'nombre'      => 'Camila Rivas',
            'rol'         => 'Head Barista & Trainer',
            'especialidad'=> 'Métodos Filtrados & Arte Latte',
            'imagen'      => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&q=80',
            'bio'         => 'Con más de 7 años detrás de la barra, lidera la formación técnica de nuestro equipo y el servicio al cliente.',
        ],
        [
            'nombre'      => 'Sebastián Morales',
            'rol'         => 'Chef Pastelero',
            'especialidad'=> 'Bollería Francesa & Maridajes',
            'imagen'      => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80',
            'bio'         => 'Elabora diariamente repostería artesanal con mantequilla pura, diseñada para maridar a la perfección con nuestro espresso.',
        ],
    ],

    /* --- Configuración para Contacto --- */
    'motivos_contacto' => [
        ['id' => 'consulta',     'nombre' => 'Consulta general o información'],
        ['id' => 'eventos',      'nombre' => 'Eventos privados, talleres o cataciones'],
        ['id' => 'corporativo',  'nombre' => 'Café corporativo o pedidos para empresas'],
        ['id' => 'caficultores', 'nombre' => 'Alianzas con productores y caficultores'],
        ['id' => 'sugerencia',   'nombre' => 'Comentarios y sugerencias'],
    ],

    'faqs' => [
        [
            'pregunta'  => '¿Cuentan con opciones de leche vegetal y café descafeinado?',
            'respuesta' => 'Sí, disponemos de leche de avena, almendras y soya texturizadas profesionalmente para arte latte. También contamos con espresso y café filtrado descafeinado por el proceso natural Swiss Water (100% libre de químicos).',
            'icono'     => 'fa-solid fa-seedling',
        ],
        [
            'pregunta'  => '¿Tienen comodidades para trabajar o estudiar (Wi-Fi y enchufes)?',
            'respuesta' => '¡Por supuesto! Contamos con conexión Wi-Fi de alta velocidad (fibra óptica), mesas amplias con tomas de corriente accesibles y un ambiente acústicamente agradable con música jazz y soul suave.',
            'icono'     => 'fa-solid fa-wifi',
        ],
        [
            'pregunta'  => '¿Se pueden realizar eventos privados, cataciones o talleres de café?',
            'respuesta' => 'Organizamos cataciones guiadas por nuestro Q-Grader, talleres de barismo básico y eventos privados para grupos previa reserva. Puedes escribirnos mediante el formulario seleccionando "Eventos privados, talleres o cataciones".',
            'icono'     => 'fa-solid fa-cake-candles',
        ],
        [
            'pregunta'  => '¿Puedo comprar café en grano o pedir que lo muelan en el momento?',
            'respuesta' => 'Todos nuestros cafés de origen están disponibles en bolsas herméticas de 250g y 1kg con válvula desgasificadora. Podemos molértelo al instante calibrado según tu cafetera (espresso, V60, prensa francesa, moka italiana, etc.).',
            'icono'     => 'fa-solid fa-bag-shopping',
        ],
        [
            'pregunta'  => '¿Son una cafetería Pet Friendly?',
            'respuesta' => '¡Totalmente! Las mascotas educadas son bienvenidas tanto en nuestra terraza como en la zona designada del interior. Siempre disponemos de platitos con agua fresca para ellas.',
            'icono'     => 'fa-solid fa-paw',
        ],
    ],

    /* --- Configuración para Reservas y Maqueta 3D --- */
    'zonas_reserva' => [
        [
            'id'          => 'barra',
            'nombre'      => 'Barra de Especialidad',
            'badge'       => 'Experiencia Sensorial',
            'descripcion' => 'Frente a los baristas, ideal para catar filtrados y presenciar el ritual del café.',
            'icono'       => 'fa-solid fa-mug-hot',
            'capacidad'   => '1 a 4 personas',
            'imagen'      => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=700&q=80',
        ],
        [
            'id'          => 'salon',
            'nombre'      => 'Salón Principal',
            'badge'       => 'Cálido & Confortable',
            'descripcion' => 'Sillones cómodos, iluminación tenue y música jazz suave para una estancia relajante.',
            'icono'       => 'fa-solid fa-couch',
            'capacidad'   => '2 a 6 personas',
            'imagen'      => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=700&q=80',
        ],
        [
            'id'          => 'terraza',
            'nombre'      => 'Terraza Jardín',
            'badge'       => 'Pet Friendly',
            'descripcion' => 'Espacio al aire libre rodeado de plantas naturales. Bienvenida para tus mascotas.',
            'icono'       => 'fa-solid fa-seedling',
            'capacidad'   => '2 a 8 personas',
            'imagen'      => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=700&q=80',
        ],
        [
            'id'          => 'coworking',
            'nombre'      => 'Rincón Coworking',
            'badge'       => 'Productividad',
            'descripcion' => 'Mesas amplias, tomas de corriente accesibles y Wi-Fi de fibra óptica.',
            'icono'       => 'fa-solid fa-laptop',
            'capacidad'   => '1 a 4 personas',
            'imagen'      => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=700&q=80',
        ],
    ],

    'mesas_3d' => [
        // Zona Barra
        ['id' => 'B1', 'zona' => 'barra', 'nombre' => 'Taburete Barra B1', 'capacidad' => 1, 'estado' => 'disponible', 'x' => 8,  'y' => 20, 'icono' => 'fa-solid fa-chair'],
        ['id' => 'B2', 'zona' => 'barra', 'nombre' => 'Taburete Barra B2', 'capacidad' => 1, 'estado' => 'disponible', 'x' => 18, 'y' => 20, 'icono' => 'fa-solid fa-chair'],
        ['id' => 'B3', 'zona' => 'barra', 'nombre' => 'Taburete Barra B3', 'capacidad' => 1, 'estado' => 'disponible', 'x' => 28, 'y' => 20, 'icono' => 'fa-solid fa-chair'],
        ['id' => 'B4', 'zona' => 'barra', 'nombre' => 'Taburete Barra B4', 'capacidad' => 1, 'estado' => 'ocupada',    'x' => 38, 'y' => 20, 'icono' => 'fa-solid fa-chair'],
        // Zona Salón
        ['id' => 'S1', 'zona' => 'salon', 'nombre' => 'Mesa Central S1',   'capacidad' => 4, 'estado' => 'disponible', 'x' => 8,  'y' => 46, 'icono' => 'fa-solid fa-utensils'],
        ['id' => 'S2', 'zona' => 'salon', 'nombre' => 'Mesa Central S2',   'capacidad' => 4, 'estado' => 'disponible', 'x' => 22, 'y' => 46, 'icono' => 'fa-solid fa-utensils'],
        ['id' => 'S3', 'zona' => 'salon', 'nombre' => 'Mesa Ventanal S3',  'capacidad' => 2, 'estado' => 'disponible', 'x' => 8,  'y' => 68, 'icono' => 'fa-solid fa-wine-glass'],
        ['id' => 'S5', 'zona' => 'salon', 'nombre' => 'Mesa Bistro S5',    'capacidad' => 2, 'estado' => 'disponible', 'x' => 22, 'y' => 68, 'icono' => 'fa-solid fa-mug-hot'],
        ['id' => 'S4', 'zona' => 'salon', 'nombre' => 'Sofá Chesterfield S4', 'capacidad' => 6, 'estado' => 'disponible', 'x' => 36, 'y' => 52, 'icono' => 'fa-solid fa-couch'],
        // Zona Terraza
        ['id' => 'T1', 'zona' => 'terraza', 'nombre' => 'Mesa Jardín T1',   'capacidad' => 4, 'estado' => 'disponible', 'x' => 60, 'y' => 16, 'icono' => 'fa-solid fa-sun'],
        ['id' => 'T2', 'zona' => 'terraza', 'nombre' => 'Mesa Jardín T2',   'capacidad' => 4, 'estado' => 'disponible', 'x' => 78, 'y' => 16, 'icono' => 'fa-solid fa-tree'],
        ['id' => 'T3', 'zona' => 'terraza', 'nombre' => 'Mesa Lounge T3',   'capacidad' => 4, 'estado' => 'disponible', 'x' => 60, 'y' => 38, 'icono' => 'fa-solid fa-umbrella'],
        ['id' => 'T4', 'zona' => 'terraza', 'nombre' => 'Mesa Lounge T4',   'capacidad' => 4, 'estado' => 'disponible', 'x' => 78, 'y' => 38, 'icono' => 'fa-solid fa-chair'],
        ['id' => 'T5', 'zona' => 'terraza', 'nombre' => 'Mesa Terraza T5',  'capacidad' => 6, 'estado' => 'ocupada',    'x' => 68, 'y' => 56, 'icono' => 'fa-solid fa-mug-hot'],


        // Zona Coworking
        ['id' => 'C1', 'zona' => 'coworking', 'nombre' => 'Estación Focus C1', 'capacidad' => 1, 'estado' => 'disponible', 'x' => 60, 'y' => 74, 'icono' => 'fa-solid fa-laptop'],
        ['id' => 'C2', 'zona' => 'coworking', 'nombre' => 'Estación Focus C2', 'capacidad' => 1, 'estado' => 'disponible', 'x' => 70, 'y' => 74, 'icono' => 'fa-solid fa-laptop'],
        ['id' => 'C3', 'zona' => 'coworking', 'nombre' => 'Estación Focus C3', 'capacidad' => 1, 'estado' => 'disponible', 'x' => 80, 'y' => 74, 'icono' => 'fa-solid fa-laptop'],
        ['id' => 'C4', 'zona' => 'coworking', 'nombre' => 'Estación Focus C4', 'capacidad' => 1, 'estado' => 'ocupada',    'x' => 90, 'y' => 74, 'icono' => 'fa-solid fa-plug'],
    ],




    'turnos_horarios' => [
        '08:00 AM', '09:30 AM', '11:00 AM', '12:30 PM', '02:00 PM', '03:30 PM', '05:00 PM', '06:30 PM', '07:30 PM'
    ],

    'ocasiones_reserva' => [
        ['id' => 'casual',      'nombre' => 'Reunión casual / Café entre amigos'],
        ['id' => 'trabajo',     'nombre' => 'Trabajo / Reunión de negocios'],
        ['id' => 'cumpleanos',  'nombre' => 'Celebración o cumpleaños'],
        ['id' => 'cita',        'nombre' => 'Cita especial'],
        ['id' => 'degustacion', 'nombre' => 'Degustación sensorial de métodos de café'],
    ],

    'politicas_reserva' => [
        [
            'icono'  => 'fa-regular fa-clock',
            'titulo' => 'Tolerancia de 15 Minutos',
            'desc'   => 'Guardamos tu mesa hasta 15 minutos después de la hora seleccionada para asegurar tu tranquilidad.',
        ],
        [
            'icono'  => 'fa-solid fa-hand-holding-dollar',
            'titulo' => 'Sin Pago Previo',
            'desc'   => 'La reserva es 100% gratuita. Puedes modificar o cancelar avisándonos previamente.',
        ],
        [
            'icono'  => 'fa-solid fa-users',
            'titulo' => 'Mesas para +8 Personas',
            'desc'   => 'Para grupos grandes o eventos privados, escríbenos directamente por WhatsApp para coordinar tu experiencia.',
        ],
    ],
];
