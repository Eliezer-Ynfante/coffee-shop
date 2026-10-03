<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategorySlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_slug_is_unique_and_can_be_kept_during_update(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('admin.categories.store'), ['name' => 'Café de Prueba'])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::where('slug', 'cafe-de-prueba')->firstOrFail();

        $this->put(route('admin.categories.update', $category->id), [
            'name' => 'Café de Prueba',
            'description' => 'Actualización de la misma categoría',
        ])->assertRedirect(route('admin.categories.index'));

        $this->postJson(route('admin.categories.store'), ['name' => 'Café de Prueba'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('slug');

        $this->assertDatabaseCount('categories', 1);
    }
}