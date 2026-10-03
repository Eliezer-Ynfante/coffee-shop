<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_uses_business_settings_saved_in_the_database(): void
    {
        Setting::set('nombre', 'Café Configurable');
        Setting::set('direccion', 'Av. de Prueba 123');

        $this->get(route('contacto'))
            ->assertOk()
            ->assertSee('Café Configurable')
            ->assertSee('Av. de Prueba 123');
    }

    public function test_empty_saved_values_do_not_fall_back_to_config(): void
    {
        Setting::set('nombre', null);

        $this->assertSame('', setting('nombre', 'Valor por defecto'));
    }
}