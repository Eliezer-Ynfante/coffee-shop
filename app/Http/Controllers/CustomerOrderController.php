<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    /**
     * Muestra el portal del cliente con sus pedidos activos e historial de compras.
     */
    public function index()
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $nameParts = array_pad(preg_split('/\s+/', trim($user->name), 2) ?: [], 2, '');
        $customer = $user->customer()->firstOrCreate([], [
            'first_name' => mb_substr($nameParts[0] !== '' ? $nameParts[0] : 'Cliente', 0, 80),
            'last_name' => mb_substr($nameParts[1], 0, 80),
        ]);

        $allOrders = $customer->orders()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->get();
        $activeOrders = $allOrders->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready']);
        $pastOrders = $allOrders->whereIn('status', ['completed', 'cancelled', 'refunded']);

        return view('customer.orders', compact('user', 'customer', 'activeOrders', 'pastOrders'));
    }
}
