<?php

namespace App\Http\Requests;

use App\Rules\AllowedUrl;
use Illuminate\Foundation\Http\FormRequest;

class GalleryItemFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:50'],
            'image_url' => ['required', 'url:https', 'max:500', new AllowedUrl(['images.unsplash.com', 'raizygrano.pe', 'www.raizygrano.pe'])],
            'description' => ['nullable', 'string'],
            'badge' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
