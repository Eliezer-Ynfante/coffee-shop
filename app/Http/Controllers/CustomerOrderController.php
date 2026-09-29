<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    /**
     * Muestra el portal del cliente con sus pedidos activos e historial de compras.
     */
    public function index()
    {
        $user = Auth::user();

        // Obtener perfil del cliente asociado al usuario
        $customer = DB::table('customers')->where('user_id', $user->id)->first();

        $activeOrders = collect();
        $pastOrders = collect();

        if ($customer) {
            $rawOrders = DB::table('orders')
                ->where('customer_id', $customer->id)
                ->orderByDesc('created_at')
                ->get();

            // Asociar los items a cada orden
            $orderIds = $rawOrders->pluck('id');
            $items = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereIn('order_items.order_id', $orderIds)
                ->select(
                    'order_items.*',
                    'products.name as product_name',
                    'products.slug as product_slug'
                )
                ->get()
                ->groupBy('order_id');

            $allOrders = $rawOrders->map(function ($order) use ($items) {
                $order->items = $items->get($order->id, collect());
                return $order;
            });

            // Separar en activos (en curso) y completados/cancelados
            $activeOrders = $allOrders->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready']);
            $pastOrders = $allOrders->whereIn('status', ['completed', 'cancelled', 'refunded']);
        }

        return view('customer.orders', compact('user', 'customer', 'activeOrders', 'pastOrders'));
    }
}
