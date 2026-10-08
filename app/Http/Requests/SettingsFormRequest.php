<?php

namespace App\Http\Requests;

use App\Rules\AllowedUrl;
use Illuminate\Foundation\Http\FormRequest;

class SettingsFormRequest extends FormRequest
{
    private const IMAGE_HOSTS = ['images.unsplash.com', 'raizygrano.pe', 'www.raizygrano.pe'];

    private const SOCIAL_HOSTS = [
        'whatsapp' => ['wa.me', 'api.whatsapp.com', 'whatsapp.com', 'www.whatsapp.com'],
        'instagram' => ['instagram.com', 'www.instagram.com'],
        'facebook' => ['facebook.com', 'www.facebook.com', 'm.facebook.com', 'fb.com', 'www.fb.com'],
        'tiktok' => ['tiktok.com', 'www.tiktok.com'],
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'required', 'string', 'max:150'],
            'slogan' => ['sometimes', 'nullable', 'string', 'max:255'],
            'titulo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'subtitulo' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'descripcion' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'subtag' => ['sometimes', 'nullable', 'string', 'max:255'],
            'horario' => ['sometimes', 'nullable', 'string', 'max:255'],
            'direccion' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:150'],
            'telefono' => ['sometimes', 'nullable', 'string', 'max:30'],
            'yape_phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'payment_qr' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'hero_img' => ['sometimes', 'nullable', 'string', 'max:500', new AllowedUrl(self::IMAGE_HOSTS, true)],
            'about_img' => ['sometimes', 'nullable', 'string', 'max:500', new AllowedUrl(self::IMAGE_HOSTS, true)],
            'redes' => ['sometimes', 'array:whatsapp,instagram,facebook,tiktok'],
            'redes.whatsapp' => ['nullable', 'string', 'max:255', new AllowedUrl(self::SOCIAL_HOSTS['whatsapp'])],
            'redes.instagram' => ['nullable', 'string', 'max:255', new AllowedUrl(self::SOCIAL_HOSTS['instagram'])],
            'redes.facebook' => ['nullable', 'string', 'max:255', new AllowedUrl(self::SOCIAL_HOSTS['facebook'])],
            'redes.tiktok' => ['nullable', 'string', 'max:255', new AllowedUrl(self::SOCIAL_HOSTS['tiktok'])],
        ];
    }
}
