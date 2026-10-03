<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_reservations' => Reservation::count(),
            'unread_messages' => ContactMessage::where('status', 'unread')->count(),
            'active_orders' => Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])->count(),
            'pending_reservas' => Reservation::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with('items.product')->latest()->take(5)->get();
        $upcomingReservations = Reservation::whereDate('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->orderBy('hora')
            ->take(5)
            ->get();
        $recentMessages = ContactMessage::latest()->take(4)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'upcomingReservations', 'recentMessages'));
    }
}
