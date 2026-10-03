<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedUrl implements ValidationRule
{
    public function __construct(
        private array $allowedHosts,
        private bool $allowLocalImagePaths = false,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->allowLocalImagePaths && $this->isLocalImagePath($value)) {
            return;
        }

        $parts = is_string($value) ? parse_url($value) : false;
        $host = is_array($parts) ? strtolower($parts['host'] ?? '') : '';
        $isAllowed = is_array($parts)
            && strtolower($parts['scheme'] ?? '') === 'https'
            && filter_var($value, FILTER_VALIDATE_URL)
            && in_array($host, $this->allowedHosts, true);

        if (! $isAllowed) {
            $fail('El campo :attribute debe usar HTTPS y un dominio permitido.');
        }
    }

    private function isLocalImagePath(mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        $path = parse_url($value, PHP_URL_PATH);
        $segments = is_string($path) ? explode('/', trim($path, '/')) : [];

        return (str_starts_with($value, '/storage/') || str_starts_with($value, '/images/'))
            && ! in_array('..', $segments, true)
            && ! str_contains($value, '\\');
    }
}
