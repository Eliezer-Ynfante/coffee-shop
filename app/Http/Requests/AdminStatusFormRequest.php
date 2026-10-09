<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminStatusFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statuses = match ($this->route()->getName()) {
            'admin.reservations.status' => ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'],
            'admin.orders.status' => ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'],
            'admin.messages.status' => ['unread', 'read', 'attended'],
            'admin.tables.status' => ['disponible', 'ocupada', 'reservada', 'mantenimiento'],
            default => [],
        };

        return ['status' => ['required', Rule::in($statuses)]];
    }
}
