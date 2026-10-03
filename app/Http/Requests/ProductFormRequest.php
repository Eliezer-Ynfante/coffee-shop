<?php

namespace App\Http\Requests;

use App\Rules\AllowedUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductFormRequest extends FormRequest
{
    private const IMAGE_HOSTS = ['images.unsplash.com', 'raizygrano.pe', 'www.raizygrano.pe'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sku = Rule::unique('products', 'sku');
        if ($this->routeIs('admin.products.update')) {
            $sku->ignore($this->route('id'));
        }

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['nullable', 'string', 'max:50', $sku],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image_path' => ['nullable', 'string', 'max:255', new AllowedUrl(self::IMAGE_HOSTS, true)],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
