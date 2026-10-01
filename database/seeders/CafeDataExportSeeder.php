<?php

namespace Database\Seeders;

use App\Models\CafeTable;
use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CafeDataExportSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. EXPORTAR CONFIGURACIONES A TABLA `settings` ─────────────
        $settingsData = [
            'nombre'      => ['value' => config('cafe.nombre', 'Raíz & Grano'), 'group' => 'general', 'type' => 'text'],
            'slogan'      => ['value' => config('cafe.slogan', 'Tómate un descanso'), 'group' => 'general', 'type' => 'text'],
            'titulo'      => ['value' => config('cafe.titulo', 'y bebe café'), 'group' => 'general', 'type' => 'text'],
            'subtitulo'   => ['value' => config('cafe.subtitulo', 'En nuestra acogedora cafetería...'), 'group' => 'general', 'type' => 'textarea'],
            'descripcion' => ['value' => config('cafe.descripcion', '¡Bienvenido a nuestra acogedora cafetería!...'), 'group' => 'general', 'type' => 'textarea'],
            'subtag'      => ['value' => config('cafe.subtag', 'Café de especialidad · Piura, Perú'), 'group' => 'general', 'type' => 'text'],
            'hero_img'    => ['value' => config('cafe.hero_img'), 'group' => 'multimedia', 'type' => 'image'],
            'about_img'   => ['value' => config('cafe.about_img'), 'group' => 'multimedia', 'type' => 'image'],
            'horario'     => ['value' => config('cafe.horario', 'Lun – Vie: 7 am – 8 pm · Sáb – Dom: 8 am – 6 pm'), 'group' => 'contacto', 'type' => 'text'],
            'direccion'   => ['value' => config('cafe.direccion', 'Av. La Mar 620, Piura, Perú'), 'group' => 'contacto', 'type' => 'text'],
            'email'       => ['value' => config('cafe.email', 'hola@raizygrano.pe'), 'group' => 'contacto', 'type' => 'text'],
            'telefono'    => ['value' => config('cafe.telefono', '+51 999 999 999'), 'group' => 'contacto', 'type' => 'text'],
            'redes'       => ['value' => json_encode(config('cafe.redes', []), JSON_UNESCAPED_UNICODE), 'group' => 'contacto', 'type' => 'json'],
        ];

        foreach ($settingsData as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'group' => $data['group'],
                    'type'  => $data['type'],
                ]
            );
        }

        // ── 2. EXPORTAR GALERÍA A TABLA `gallery_items` ───────────────
        $gallery = config('cafe.galeria', []);
        foreach ($gallery as $index => $item) {
            GalleryItem::updateOrCreate(
                ['title' => $item['titulo']],
                [
                    'category'      => $item['categoria'],
                    'category_name' => $item['categoria_nombre'] ?? ucfirst($item['categoria']),
                    'description'   => $item['descripcion'] ?? '',
                    'image_url'     => $item['imagen'],
                    'badge'         => $item['badge'] ?? null,
                    'sort_order'    => $index + 1,
                    'is_active'     => true,
                ]
            );
        }

        // ── 3. EXPORTAR MESAS A TABLA `cafe_tables` ────────────────────
        $mesas = config('cafe.mesas_3d', []);
        $zoneNames = [
            'barra'     => 'Barra de Especialidad',
            'salon'     => 'Salón Principal',
            'terraza'   => 'Terraza Cafetalera',
            'coworking' => 'Rincón Coworking',
        ];

        foreach ($mesas as $mesa) {
            CafeTable::updateOrCreate(
                ['code' => $mesa['id']],
                [
                    'zone'      => $mesa['zona'],
                    'zone_name' => $zoneNames[$mesa['zona']] ?? ucfirst($mesa['zona']),
                    'name'      => $mesa['nombre'],
                    'capacity'  => $mesa['capacidad'],
                    'status'    => $mesa['estado'] ?? 'disponible',
                    'coord_x'   => $mesa['x'] ?? 0,
                    'coord_y'   => $mesa['y'] ?? 0,
                    'icon'      => $mesa['icono'] ?? 'fa-solid fa-chair',
                    'is_active' => true,
                ]
            );
        }

        // ── 4. EXPORTAR CATEGORÍAS Y PRODUCTOS A LA BD ─────────────────
        // Categoría 1: Café Caliente
        $catCaliente = Category::updateOrCreate(
            ['slug' => 'cafe-caliente'],
            [
                'name'        => 'Café de Especialidad Caliente',
                'description' => 'Espressos, filtrados y métodos artesanales preparados por baristas certificados.',
                'image_path'  => 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=420&q=80&fm=webp',
                'sort_order'  => 1,
                'is_active'   => true,
            ]
        );

        // Categoría 2: Bebidas Frías
        $catFrias = Category::updateOrCreate(
            ['slug' => 'bebidas-frias'],
            [
                'name'        => 'Bebidas Frías & Cold Brew',
                'description' => 'Extracciones en frío de 24 horas y combinaciones refrescantes.',
                'image_path'  => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80&fm=webp',
                'sort_order'  => 2,
                'is_active'   => true,
            ]
        );

        // Categoría 3: Repostería y Postres
        $catPostres = Category::updateOrCreate(
            ['slug' => 'reposteria-artesanal'],
            [
                'name'        => 'Repostería y Panadería Artesanal',
                'description' => 'Hojaldres con mantequilla pura, tartas y repostería horneada a diario.',
                'image_path'  => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=420&q=80&fm=webp',
                'sort_order'  => 3,
                'is_active'   => true,
            ]
        );

        // Productos completos a sincronizar
        $productsToSeed = [
            // Café Caliente
            [
                'category_id'        => $catCaliente->id,
                'name'               => 'Flat White Doble Ristretto',
                'slug'               => 'flat-white',
                'sku'                => 'CAF-FLAT-01',
                'description'        => 'Doble ristretto de café de altura con leche texturizada aterciopelada a 65°C.',
                'price'              => 12.00,
                'cost_price'         => 4.50,
                'stock'              => 50,
                'image_path'         => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=600&q=80&fm=webp',
                'is_featured'        => true,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catCaliente->id,
                'name'               => 'Cappuccino Italiano',
                'slug'               => 'cappuccino',
                'sku'                => 'CAF-CAPP-01',
                'description'        => 'Espresso balanceado con partes iguales de leche vaporizada y abundante espuma densa.',
                'price'              => 10.00,
                'cost_price'         => 3.80,
                'stock'              => 45,
                'image_path'         => 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=600&q=80&fm=webp',
                'is_featured'        => false,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catCaliente->id,
                'name'               => 'Americano Clásico',
                'slug'               => 'americano',
                'sku'                => 'CAF-AMER-01',
                'description'        => 'Doble shot de espresso suave diluido con agua caliente a 92°C preservando la crema.',
                'price'              => 8.00,
                'cost_price'         => 2.50,
                'stock'              => 60,
                'image_path'         => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&q=80&fm=webp',
                'is_featured'        => false,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catCaliente->id,
                'name'               => 'Latte Suave Vainilla',
                'slug'               => 'latte',
                'sku'                => 'CAF-LATT-01',
                'description'        => 'Espresso cremoso con abundante leche vaporizada y sutil toque aromático.',
                'price'              => 11.00,
                'cost_price'         => 4.00,
                'stock'              => 40,
                'image_path'         => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=600&q=80&fm=webp',
                'is_featured'        => false,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catCaliente->id,
                'name'               => 'Espresso Reserva de Altura',
                'slug'               => 'espresso-reserva',
                'sku'                => 'CAF-ESPR-01',
                'description'        => 'Blend exclusivo de altura de Jaén, cuerpo sedoso y notas profundas a chocolate oscuro.',
                'price'              => 9.00,
                'cost_price'         => 3.00,
                'stock'              => 50,
                'image_path'         => 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=600&q=80&fm=webp',
                'is_featured'        => true,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catCaliente->id,
                'name'               => 'Latte de Lúcuma Peruana',
                'slug'               => 'latte-lucuma',
                'sku'                => 'CAF-LUCU-01',
                'description'        => 'Espresso suave con leche vaporizada y jarabe artesanal de lúcuma cosechada en valles costeños.',
                'price'              => 14.00,
                'cost_price'         => 5.20,
                'stock'              => 30,
                'image_path'         => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=600&q=80&fm=webp',
                'is_featured'        => true,
                'is_active'          => true,
            ],

            // Bebidas Frías
            [
                'category_id'        => $catFrias->id,
                'name'               => 'Cold Brew Clásico 24 Horas',
                'slug'               => 'cold-brew-24h',
                'sku'                => 'CAF-COLD-01',
                'description'        => 'Infusión en frío durante 24h. Dulzor natural, bajo en acidez y sumamente refrescante.',
                'price'              => 16.00,
                'cost_price'         => 5.00,
                'stock'              => 35,
                'image_path'         => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80&fm=webp',
                'is_featured'        => true,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catFrias->id,
                'name'               => 'Cold Brew Naranja & Tónica',
                'slug'               => 'cold-brew-tonic',
                'sku'                => 'CAF-COLD-02',
                'description'        => 'Cold brew concentrado con agua tónica premium, hielo cristalino y twist de naranja.',
                'price'              => 17.00,
                'cost_price'         => 5.50,
                'stock'              => 25,
                'image_path'         => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=600&q=80&fm=webp',
                'is_featured'        => false,
                'is_active'          => true,
            ],

            // Repostería
            [
                'category_id'        => $catPostres->id,
                'name'               => 'Croissant Clásico de Mantequilla',
                'slug'               => 'croissant-mantequilla',
                'sku'                => 'PAN-CROI-01',
                'description'        => 'Clásico croissant francés laminado a mano con 100% pura mantequilla de campo.',
                'price'              => 9.00,
                'cost_price'         => 3.20,
                'stock'              => 30,
                'image_path'         => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=600&q=80&fm=webp',
                'is_featured'        => false,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catPostres->id,
                'name'               => 'Cheesecake de Frutos Rojos',
                'slug'               => 'cheesecake-frutos-rojos',
                'sku'                => 'POS-CHES-01',
                'description'        => 'Tarta de queso crema suave horneada sobre base crocante y compota natural de berries.',
                'price'              => 14.00,
                'cost_price'         => 5.00,
                'stock'              => 20,
                'image_path'         => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=600&q=80&fm=webp',
                'is_featured'        => false,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catPostres->id,
                'name'               => 'Brownie Chocolate Amargo y Nueces',
                'slug'               => 'brownie-chocolate',
                'sku'                => 'POS-BROW-01',
                'description'        => 'Brownie fudge con 70% cacao cusqueño y nueces tostadas crujientes.',
                'price'              => 10.00,
                'cost_price'         => 3.50,
                'stock'              => 25,
                'image_path'         => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=600&q=80&fm=webp',
                'is_featured'        => false,
                'is_active'          => true,
            ],
            [
                'category_id'        => $catPostres->id,
                'name'               => 'Muffin con Chips de Espresso',
                'slug'               => 'muffin-espresso',
                'sku'                => 'POS-MUFF-01',
                'description'        => 'Muffin esponjoso artesanal marmoleado con gotas de chocolate y café.',
                'price'              => 8.00,
                'cost_price'         => 2.80,
                'stock'              => 35,
                'image_path'         => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=600&q=80&fm=webp',
                'is_featured'        => false,
                'is_active'          => true,
            ],
        ];

        foreach ($productsToSeed as $p) {
            Product::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );
        }
    }
}
