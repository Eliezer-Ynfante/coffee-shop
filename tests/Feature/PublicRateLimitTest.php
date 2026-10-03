<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicRateLimitTest extends TestCase
{
    public function test_login_is_limited_to_five_attempts_per_minute(): void
    {
        $this->assertRouteIsThrottled('/login', 5, [
            'email' => 'no-es-correo',
            'password' => 'incorrecta',
        ]);
    }

    public function test_contact_is_limited_to_ten_attempts_per_minute(): void
    {
        $this->assertRouteIsThrottled('/contacto', 10, []);
    }

    public function test_reservations_are_limited_to_ten_attempts_per_minute(): void
    {
        $this->assertRouteIsThrottled('/reserva', 10, []);
    }

    private function assertRouteIsThrottled(string $uri, int $limit, array $payload): void
    {
        for ($attempt = 0; $attempt < $limit; $attempt++) {
            $this->postJson($uri, $payload)->assertUnprocessable();
        }

        $this->postJson($uri, $payload)->assertTooManyRequests();
    }
}