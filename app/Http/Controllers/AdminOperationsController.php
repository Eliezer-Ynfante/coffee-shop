<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStatusFormRequest;
use App\Models\CafeTable;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminOperationsController extends Controller
{
    public function reservations(Request $request)
    {
        $query = Reservation::latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        $reservations = $query->paginate(15)->withQueryString();
        $statusCounts = [
            'total' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'statusCounts'));
    }

    public function updateReservationStatus(AdminStatusFormRequest $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $validated = $request->validated();
        $reservation->update(['status' => $validated['status']]);

        return back()->with('status', "Reserva #{$reservation->id} de {$reservation->nombre} actualizada a estado: ".ucfirst($validated['status']).'.');
    }

    public function destroyReservation(int $id)
    {
        Reservation::findOrFail($id)->delete();

        return back()->with('status', 'Reserva eliminada.');
    }

    public function orders(Request $request)
    {
        $query = Order::with('items.product')->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        $orders = $query->paginate(15)->withQueryString();
        $orderStats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'sales' => Order::where('status', 'completed')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'orderStats'));
    }

    public function updateOrderStatus(AdminStatusFormRequest $request, int $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validated();
        $updates = ['status' => $validated['status']];

        if ($validated['status'] === 'preparing' && ! $order->prepared_at) {
            $updates['prepared_at'] = now();
        }
        if ($validated['status'] === 'completed' && ! $order->completed_at) {
            $updates['completed_at'] = now();
        }
        if ($validated['status'] === 'cancelled' && ! $order->cancelled_at) {
            $updates['cancelled_at'] = now();
        }

        $order->update($updates);

        return back()->with('status', "Orden {$order->order_number} actualizada a: ".ucfirst($validated['status']).'.');
    }

    public function tables(Request $request)
    {
        $query = CafeTable::orderBy('zone')->orderBy('code');
        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tables = $query->get();
        $stats = [
            'total' => CafeTable::count(),
            'disponible' => CafeTable::where('status', 'disponible')->count(),
            'ocupada' => CafeTable::where('status', 'ocupada')->count(),
            'reservada' => CafeTable::where('status', 'reservada')->count(),
        ];

        return view('admin.tables.index', compact('tables', 'stats'));
    }

    public function updateTableStatus(AdminStatusFormRequest $request, int $id)
    {
        $table = CafeTable::findOrFail($id);
        $validated = $request->validated();
        $table->update(['status' => $validated['status']]);

        return back()->with('status', "Mesa {$table->code} actualizada a: ".ucfirst($validated['status']).'.');
    }
}
