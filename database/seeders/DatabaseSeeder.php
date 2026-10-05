<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->call(CafeDataExportSeeder::class);

            return;
        }

        // ── 1. Usuarios: Administrador y Cliente ─────────────────────────
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.test'],
            [
                'name'     => 'Admin Demo',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        $customerUser = User::updateOrCreate(
            ['email' => 'cliente@example.test'],
            [
                'name'     => 'Cliente Demo',
                'password' => Hash::make('password'),
                'role'     => 'customer',
            ]
        );

        // Actualizamos también Test User a customer
        User::updateOrCreate(
            ['email' => 'test-user@example.test'],
            [
                'name'     => 'Test User',
                'password' => Hash::make('password'),
                'role'     => 'customer',
            ]
        );

        // ── 2. Perfil del Cliente ───────────────────────────────────────
        $customer = DB::table('customers')->where('user_id', $customerUser->id)->first();
        if (! $customer) {
            $customerId = DB::table('customers')->insertGetId([
                'user_id'        => $customerUser->id,
                'first_name'     => 'Cliente',
                'last_name'      => 'Demo',
                'phone'          => '+1 202-555-0100',
                'birth_date'     => '2000-01-01',
                'address_line1'  => '123 Example Street',
                'city'           => 'Example City',
                'district'       => 'Demo District',
                'loyalty_points' => 140,
                'is_active'      => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        } else {
            $customerId = $customer->id;
        }

        // ── 3. Categorías de la Cafetería ───────────────────────────────
        $catCalienteId = DB::table('categories')->where('slug', 'cafe-caliente')->value('id');
        if (! $catCalienteId) {
            $catCalienteId = DB::table('categories')->insertGetId([
                'name'        => 'Café de Especialidad Caliente',
                'slug'        => 'cafe-caliente',
                'description' => 'Espressos, filtrados y métodos artesanales preparados por baristas certificados.',
                'sort_order'  => 1,
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $catFrioId = DB::table('categories')->where('slug', 'bebidas-frias')->value('id');
        if (! $catFrioId) {
            $catFrioId = DB::table('categories')->insertGetId([
                'name'        => 'Bebidas Frías & Cold Brew',
                'slug'        => 'bebidas-frias',
                'description' => 'Extracciones en frío de 24 horas y combinaciones refrescantes.',
                'sort_order'  => 2,
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $catPostresId = DB::table('categories')->where('slug', 'reposteria-artesanal')->value('id');
        if (! $catPostresId) {
            $catPostresId = DB::table('categories')->insertGetId([
                'name'        => 'Repostería y Panadería Artesanal',
                'slug'        => 'reposteria-artesanal',
                'description' => 'Hojaldres con mantequilla pura, tartas y repostería horneada a diario.',
                'sort_order'  => 3,
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // ── 4. Productos de Muestra ─────────────────────────────────────
        $prodFlatWhiteId = DB::table('products')->where('slug', 'flat-white')->value('id');
        if (! $prodFlatWhiteId) {
            $prodFlatWhiteId = DB::table('products')->insertGetId([
                'category_id'        => $catCalienteId,
                'name'               => 'Flat White Doble Ristretto',
                'slug'               => 'flat-white',
                'sku'                => 'CAF-FLAT-01',
                'description'        => 'Doble ristretto de café de altura con leche texturizada aterciopelada a 65°C.',
                'price'              => 12.00,
                'cost_price'         => 4.50,
                'stock'              => 50,
                'min_stock_alert'    => 5,
                'available_in_pos'   => 1,
                'available_in_store' => 1,
                'is_active'          => 1,
                'preparation_time'   => 4,
                'is_featured'        => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        $prodColdBrewId = DB::table('products')->where('slug', 'cold-brew-24h')->value('id');
        if (! $prodColdBrewId) {
            $prodColdBrewId = DB::table('products')->insertGetId([
                'category_id'        => $catFrioId,
                'name'               => 'Cold Brew Clásico 24 Horas',
                'slug'               => 'cold-brew-24h',
                'sku'                => 'CAF-COLD-01',
                'description'        => 'Infusión en frío durante 24h. Dulzor natural, bajo en acidez y muy refrescante.',
                'price'              => 16.00,
                'cost_price'         => 5.00,
                'stock'              => 35,
                'min_stock_alert'    => 5,
                'available_in_pos'   => 1,
                'available_in_store' => 1,
                'is_active'          => 1,
                'preparation_time'   => 2,
                'is_featured'        => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        $prodCroissantId = DB::table('products')->where('slug', 'croissant-mantequilla')->value('id');
        if (! $prodCroissantId) {
            $prodCroissantId = DB::table('products')->insertGetId([
                'category_id'        => $catPostresId,
                'name'               => 'Croissant Clásico de Mantequilla',
                'slug'               => 'croissant-mantequilla',
                'sku'                => 'PAN-CROI-01',
                'description'        => 'Clásico croissant francés laminado con 100% mantequilla de campo.',
                'price'              => 9.00,
                'cost_price'         => 3.20,
                'stock'              => 30,
                'min_stock_alert'    => 5,
                'available_in_pos'   => 1,
                'available_in_store' => 1,
                'is_active'          => 1,
                'preparation_time'   => 2,
                'is_featured'        => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        // ── 5. Pedidos de Prueba para Cliente Demo ───────────────────────
        // Pedido A: En preparación (activo)
        $order1 = DB::table('orders')->where('order_number', 'RG-2026-0012')->first();
        if (! $order1) {
            $order1Id = DB::table('orders')->insertGetId([
                'order_number'    => 'RG-2026-0012',
                'customer_id'     => $customerId,
                'attendant_id'    => $adminUser->id,
                'channel'         => 'ecommerce',
                'status'          => 'preparing',
                'subtotal'        => 25.00,
                'discount_amount' => 0.00,
                'tax_amount'      => 0.00,
                'total'           => 25.00,
                'payment_method'  => 'yape',
                'payment_status'  => 'paid',
                'customer_name'   => 'Cliente Demo',
                'customer_phone'  => '+1 202-555-0100',
                'notes'           => 'Empacar para llevar, sin azúcar.',
                'created_at'      => now()->subMinutes(12),
                'updated_at'      => now(),
            ]);

            DB::table('order_items')->insert([
                [
                    'order_id'   => $order1Id,
                    'product_id' => $prodColdBrewId,
                    'quantity'   => 1,
                    'unit_price' => 16.00,
                    'subtotal'   => 16.00,
                    'notes'      => 'Con hielo adicional',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'order_id'   => $order1Id,
                    'product_id' => $prodCroissantId,
                    'quantity'   => 1,
                    'unit_price' => 9.00,
                    'subtotal'   => 9.00,
                    'notes'      => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Pedido B: Listo para entrega (activo)
        $order2 = DB::table('orders')->where('order_number', 'RG-2026-0008')->first();
        if (! $order2) {
            $order2Id = DB::table('orders')->insertGetId([
                'order_number'    => 'RG-2026-0008',
                'customer_id'     => $customerId,
                'attendant_id'    => $adminUser->id,
                'channel'         => 'pos',
                'status'          => 'ready',
                'subtotal'        => 12.00,
                'discount_amount' => 0.00,
                'tax_amount'      => 0.00,
                'total'           => 12.00,
                'payment_method'  => 'card',
                'payment_status'  => 'paid',
                'customer_name'   => 'Cliente Demo',
                'customer_phone'  => '+1 202-555-0100',
                'notes'           => 'Consumo en barra',
                'created_at'      => now()->subMinutes(35),
                'updated_at'      => now(),
            ]);

            DB::table('order_items')->insert([
                [
                    'order_id'   => $order2Id,
                    'product_id' => $prodFlatWhiteId,
                    'quantity'   => 1,
                    'unit_price' => 12.00,
                    'subtotal'   => 12.00,
                    'notes'      => 'Leche de avena',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Pedido C: Completado (historial pasado)
        $order3 = DB::table('orders')->where('order_number', 'RG-2026-0001')->first();
        if (! $order3) {
            $order3Id = DB::table('orders')->insertGetId([
                'order_number'    => 'RG-2026-0001',
                'customer_id'     => $customerId,
                'attendant_id'    => $adminUser->id,
                'channel'         => 'pos',
                'status'          => 'completed',
                'subtotal'        => 21.00,
                'discount_amount' => 0.00,
                'tax_amount'      => 0.00,
                'total'           => 21.00,
                'payment_method'  => 'plin',
                'payment_status'  => 'paid',
                'customer_name'   => 'Cliente Demo',
                'customer_phone'  => '+1 202-555-0100',
                'notes'           => 'Mesa central S1',
                'completed_at'    => now()->subDays(2),
                'created_at'      => now()->subDays(2),
                'updated_at'      => now()->subDays(2),
            ]);

            DB::table('order_items')->insert([
                [
                    'order_id'   => $order3Id,
                    'product_id' => $prodFlatWhiteId,
                    'quantity'   => 1,
                    'unit_price' => 12.00,
                    'subtotal'   => 12.00,
                    'notes'      => null,
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDays(2),
                ],
                [
                    'order_id'   => $order3Id,
                    'product_id' => $prodCroissantId,
                    'quantity'   => 1,
                    'unit_price' => 9.00,
                    'subtotal'   => 9.00,
                    'notes'      => null,
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDays(2),
                ],
            ]);
        }

        // ── 6. Reservas de Muestra ───────────────────────────────────────
        if (DB::table('reservations')->count() === 0) {
            DB::table('reservations')->insert([
                [
                    'nombre'      => 'Persona Demo 1',
                    'email'       => 'persona1@example.test',
                    'telefono'    => '+1 202-555-0101',
                    'fecha'       => now()->format('Y-m-d'),
                    'hora'        => '16:30',
                    'personas'    => 2,
                    'mesa_id'     => 'S1',
                    'zona'        => 'Salón Principal',
                    'ocasion'     => 'Reunión de trabajo',
                    'comentarios' => 'Mesa cerca a toma de corriente si es posible.',
                    'status'      => 'confirmed',
                    'created_at'  => now()->subHours(3),
                    'updated_at'  => now()->subHours(3),
                ],
                [
                    'nombre'      => 'Persona Demo 2',
                    'email'       => 'persona2@example.test',
                    'telefono'    => '+1 202-555-0102',
                    'fecha'       => now()->addDay()->format('Y-m-d'),
                    'hora'        => '19:00',
                    'personas'    => 4,
                    'mesa_id'     => 'T2',
                    'zona'        => 'Terraza Cafetalera',
                    'ocasion'     => 'Cumpleaños / Celebración',
                    'comentarios' => 'Traeremos una tarta pequeña.',
                    'status'      => 'pending',
                    'created_at'  => now()->subHours(1),
                    'updated_at'  => now()->subHours(1),
                ],
                [
                    'nombre'      => 'Persona Demo 3',
                    'email'       => 'persona3@example.test',
                    'telefono'    => '+1 202-555-0103',
                    'fecha'       => now()->format('Y-m-d'),
                    'hora'        => '11:00',
                    'personas'    => 1,
                    'mesa_id'     => 'CW1',
                    'zona'        => 'Rincón Coworking',
                    'ocasion'     => 'Estudio / Trabajo remoto',
                    'comentarios' => 'Espacio tranquilo con Wi-Fi.',
                    'status'      => 'confirmed',
                    'created_at'  => now()->subDays(1),
                    'updated_at'  => now()->subDays(1),
                ],
            ]);
        }

        // ── 7. Mensajes de Contacto de Muestra ───────────────────────────
        if (DB::table('contact_messages')->count() === 0) {
            DB::table('contact_messages')->insert([
                [
                    'nombre'     => 'Persona Demo 4',
                    'email'      => 'persona4@example.test',
                    'telefono'   => '+1 202-555-0104',
                    'motivo'     => 'Eventos Corporativos',
                    'mensaje'    => 'Buenas tardes, quisiéramos cotizar el alquiler del salón principal para un evento privado de 25 personas el próximo mes.',
                    'status'     => 'unread',
                    'created_at' => now()->subHours(5),
                    'updated_at' => now()->subHours(5),
                ],
                [
                    'nombre'     => 'Persona Demo 5',
                    'email'      => 'persona5@example.test',
                    'telefono'   => '+1 202-555-0105',
                    'motivo'     => 'Consulta sobre Granos de Café',
                    'mensaje'    => 'Hola, ¿venden bolsas de 1kg en grano entero del Geisha de Jaén? ¿Tienen envíos a Lima?',
                    'status'     => 'read',
                    'created_at' => now()->subDays(1),
                    'updated_at' => now()->subHours(12),
                ],
            ]);
        }

        // ── 8. Exportación y Sincronización de Catálogo, Ajustes y Mesas ──
        $this->call(CafeDataExportSeeder::class);
    }
}
