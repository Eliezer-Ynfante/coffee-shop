<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_carta_displays_active_store_products_from_the_database(): void
    {
        $category = Category::create([
            'name' => 'Café de prueba',
            'slug' => 'cafe-de-prueba',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Producto desde base de datos',
            'slug' => 'producto-desde-base-de-datos',
            'price' => 12,
            'stock' => 5,
            'available_in_store' => true,
            'is_active' => true,
        ]);

        $this->get(route('carta'))
            ->assertOk()
            ->assertSee('Producto desde base de datos');
    }
}