<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $now = now();

        $adminId = DB::table('users')->where('email', 'admin@raizygrano.test')->value('id');
        if (! $adminId) {
            $adminId = DB::table('users')->insertGetId([
                'name' => 'Administrador',
                'email' => 'admin@raizygrano.test',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $customerId = DB::table('users')->where('email', 'cliente@raizygrano.test')->value('id');
        if (! $customerId) {
            $customerId = DB::table('users')->insertGetId([
                'name' => 'Cliente Demo',
                'email' => 'cliente@raizygrano.test',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $categories = [
            ['name' => 'Café', 'slug' => 'cafe', 'description' => 'Bebidas calientes y frías', 'sort_order' => 1],
            ['name' => 'Repostería', 'slug' => 'reposteria', 'description' => 'Pastelería artesanal', 'sort_order' => 2],
            ['name' => 'Brunch', 'slug' => 'brunch', 'description' => 'Platos ligeros para el día', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            $exists = DB::table('categories')->where('slug', $category['slug'])->exists();
            if (! $exists) {
                DB::table('categories')->insert(array_merge($category, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        $cafeCategoryId = DB::table('categories')->where('slug', 'cafe')->value('id');
        $reposteriaCategoryId = DB::table('categories')->where('slug', 'reposteria')->value('id');
        $brunchCategoryId = DB::table('categories')->where('slug', 'brunch')->value('id');

        $products = [
            ['category_id' => $cafeCategoryId, 'name' => 'Espresso Americano', 'slug' => 'espresso-americano', 'sku' => 'ESP-001', 'price' => 12.00, 'cost_price' => 4.00, 'stock' => 50, 'available_in_pos' => true, 'available_in_store' => true, 'is_active' => true, 'preparation_time' => 4],
            ['category_id' => $cafeCategoryId, 'name' => 'Cappuccino', 'slug' => 'cappuccino', 'sku' => 'CAP-002', 'price' => 15.00, 'cost_price' => 5.00, 'stock' => 40, 'available_in_pos' => true, 'available_in_store' => true, 'is_active' => true, 'preparation_time' => 5],
            ['category_id' => $reposteriaCategoryId, 'name' => 'Cheesecake', 'slug' => 'cheesecake', 'sku' => 'CHE-101', 'price' => 18.00, 'cost_price' => 6.00, 'stock' => 20, 'available_in_pos' => true, 'available_in_store' => true, 'is_active' => true, 'preparation_time' => 6],
            ['category_id' => $brunchCategoryId, 'name' => 'Toast de Aguacate', 'slug' => 'toast-de-aguacate', 'sku' => 'TOA-201', 'price' => 22.00, 'cost_price' => 8.00, 'stock' => 12, 'available_in_pos' => true, 'available_in_store' => true, 'is_active' => true, 'preparation_time' => 8],
        ];

        foreach ($products as $product) {
            if (! DB::table('products')->where('slug', $product['slug'])->exists()) {
                DB::table('products')->insert(array_merge($product, [
                    'description' => 'Producto de demostración',
                    'image_path' => null,
                    'min_stock_alert' => 5,
                    'is_featured' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        $customerRow = DB::table('customers')->where('user_id', $customerId)->first();
        if (! $customerRow) {
            DB::table('customers')->insert([
                'user_id' => $customerId,
                'first_name' => 'Cliente',
                'last_name' => 'Demo',
                'phone' => '999888777',
                'city' => 'Lima',
                'district' => 'Miraflores',
                'loyalty_points' => 120,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $tables = [
            ['code' => 'B1', 'zone' => 'barra', 'zone_name' => 'Barra', 'name' => 'Barra 1', 'capacity' => 2, 'status' => 'disponible', 'coord_x' => 12, 'coord_y' => 20, 'icon' => 'fa-solid fa-chair', 'is_active' => true],
            ['code' => 'S1', 'zone' => 'salon', 'zone_name' => 'Salón Principal', 'name' => 'Mesa S1', 'capacity' => 2, 'status' => 'disponible', 'coord_x' => 25, 'coord_y' => 38, 'icon' => 'fa-solid fa-chair', 'is_active' => true],
            ['code' => 'S2', 'zone' => 'salon', 'zone_name' => 'Salón Principal', 'name' => 'Mesa S2', 'capacity' => 4, 'status' => 'disponible', 'coord_x' => 40, 'coord_y' => 42, 'icon' => 'fa-solid fa-chair', 'is_active' => true],
            ['code' => 'T1', 'zone' => 'terraza', 'zone_name' => 'Terraza', 'name' => 'Mesa T1', 'capacity' => 2, 'status' => 'disponible', 'coord_x' => 70, 'coord_y' => 18, 'icon' => 'fa-solid fa-chair', 'is_active' => true],
        ];

        foreach ($tables as $table) {
            if (! DB::table('cafe_tables')->where('code', $table['code'])->exists()) {
                DB::table('cafe_tables')->insert(array_merge($table, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        if (! DB::table('settings')->where('key', 'site_name')->exists()) {
            DB::table('settings')->insert([
                ['key' => 'site_name', 'value' => 'Raíz & Grano', 'group' => 'general', 'type' => 'text', 'created_at' => $now, 'updated_at' => $now],
                ['key' => 'phone', 'value' => '+51 987 654 321', 'group' => 'general', 'type' => 'text', 'created_at' => $now, 'updated_at' => $now],
                ['key' => 'address', 'value' => 'Av. Principal 123, Lima', 'group' => 'general', 'type' => 'text', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        if (! DB::table('gallery_items')->exists()) {
            DB::table('gallery_items')->insert([
                ['title' => 'Barra artesanal', 'category' => 'cafe', 'category_name' => 'Café', 'description' => 'Detalle del proceso', 'image_url' => '/images/demo/barra.jpg', 'badge' => 'Nuevo', 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['title' => 'Ambiente cálido', 'category' => 'ambiente', 'category_name' => 'Ambiente', 'description' => 'Espacio de reunión y calma', 'image_url' => '/images/demo/ambiente.jpg', 'badge' => 'Popular', 'sort_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['title' => 'Pastelería', 'category' => 'postres', 'category_name' => 'Postres', 'description' => 'Repostería hecha a diario', 'image_url' => '/images/demo/postres.jpg', 'badge' => 'Chef', 'sort_order' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }

    public function down(): void
    {
        DB::table('gallery_items')->delete();
        DB::table('settings')->whereIn('key', ['site_name', 'phone', 'address'])->delete();
        DB::table('cafe_tables')->whereIn('code', ['B1', 'S1', 'S2', 'T1'])->delete();
        DB::table('customers')->where('user_id', function ($query) {
            $query->select('id')->from('users')->where('email', 'cliente@raizygrano.test');
        })->delete();
        DB::table('products')->whereIn('slug', ['espresso-americano', 'cappuccino', 'cheesecake', 'toast-de-aguacate'])->delete();
        DB::table('categories')->whereIn('slug', ['cafe', 'reposteria', 'brunch'])->delete();
        DB::table('users')->whereIn('email', ['admin@raizygrano.test', 'cliente@raizygrano.test'])->delete();
    }
};
