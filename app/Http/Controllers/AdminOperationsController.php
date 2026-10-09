<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStatusFormRequest;
use App\Models\CafeTable;
use App\Models\Customer;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Reservation;
use App\Notifications\ReservationStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminOperationsController extends Controller
{
    public function reservations(Request $request)
    {
        $query = Reservation::with('table')->latest();
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
            'no_show' => Reservation::where('status', 'no_show')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'statusCounts'));
    }

    public function updateReservationStatus(AdminStatusFormRequest $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $validated = $request->validated();

        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['completed', 'cancelled', 'no_show'],
            'completed' => [],
            'cancelled' => [],
            'no_show' => [],
        ];

        if (! in_array($validated['status'], $allowedTransitions[$reservation->status] ?? [], true)) {
            return back()->withErrors(['status' => 'La transición de estado de la reserva no es válida.']);
        }

        $reservation->update(['status' => $validated['status']]);

        if ($reservation->email) {
            Notification::route('mail', $reservation->email)->notify(new ReservationStatusNotification($reservation->load('table')));
        }

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

        $allowedTransitions = [
            'pending' => ['confirmed'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['completed', 'cancelled'],
            'completed' => ['completed'],
            'cancelled' => ['cancelled'],
            'refunded' => ['refunded'],
        ];

        if (! isset($allowedTransitions[$order->status]) || ! in_array($validated['status'], $allowedTransitions[$order->status], true)) {
            return back()->withErrors([
                'status' => "La transición de estado {$order->status} → {$validated['status']} no es válida.",
            ])->withInput();
        }

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

    public function posIndex()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where('available_in_pos', true)
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        $shiftName = session('admin_pos_shift', 'mañana');
        $openingCash = (float) session('admin_pos_opening_cash', 0);
        $closingCash = session()->has('admin_pos_closing_cash') ? (float) session('admin_pos_closing_cash') : null;

        $todayOrders = Order::query()
            ->whereDate('created_at', today())
            ->with('items.product')
            ->get();

        $cashSales = (float) $todayOrders->where('payment_method', 'cash')->sum('total');
        $cardSales = (float) $todayOrders->where('payment_method', 'card')->sum('total');
        $yapeSales = (float) $todayOrders->whereIn('payment_method', ['yape', 'plin'])->sum('total');
        $totalSales = (float) $todayOrders->sum('total');

        $tableSales = $todayOrders
            ->filter(fn ($order) => $order->status !== 'cancelled')
            ->groupBy(function ($order) {
                $notes = strtolower((string) ($order->notes ?? ''));
                if (preg_match('/mesa\s*[:#]?\s*(\d+)/i', $notes, $matches)) {
                    return 'Mesa ' . trim($matches[1]);
                }

                if (preg_match('/mesa\s*[:#]?\s*([a-z0-9]+)/i', $order->customer_name ?? '', $matches)) {
                    return 'Mesa ' . trim($matches[1]);
                }

                return 'Sin mesa';
            })
            ->map(function ($orders, $tableName) {
                return [
                    'table' => $tableName,
                    'count' => $orders->count(),
                    'total' => (float) $orders->sum('total'),
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->all();

        $cashDifference = $closingCash !== null ? round($closingCash - ($openingCash + $cashSales), 2) : null;

        return view('admin.pos.index', compact(
            'products',
            'shiftName',
            'openingCash',
            'closingCash',
            'cashDifference',
            'cashSales',
            'cardSales',
            'yapeSales',
            'totalSales',
            'tableSales',
        ));
    }

    public function setShift(Request $request)
    {
        $validated = $request->validate([
            'shift_name' => 'required|string|in:mañana,tarde,noche',
            'opening_cash' => 'required|numeric|min:0',
        ]);

        session()->put('admin_pos_shift', $validated['shift_name']);
        session()->put('admin_pos_opening_cash', (float) $validated['opening_cash']);
        session()->forget('admin_pos_closing_cash');

        return redirect()->route('admin.pos.index')->with('status', 'Turno '. $validated['shift_name'] .' abierto correctamente.');
    }

    public function closeCash(Request $request)
    {
        $validated = $request->validate([
            'closing_cash' => 'required|numeric|min:0',
        ]);

        session()->put('admin_pos_closing_cash', (float) $validated['closing_cash']);

        return redirect()->route('admin.pos.index')->with('status', 'Cierre de caja registrado correctamente.');
    }

    public function storePosOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'payment_method' => 'required|string|in:cash,card,yape,plin',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1|max:20',
        ]);

        $items = $validated['items'];
        if (! array_is_list($items)) {
            $items = collect($items)->map(function ($item, $key) {
                return is_array($item) ? $item : ['product_id' => $key, 'quantity' => 1];
            })->values()->all();
        }

        $order = DB::transaction(function () use ($validated, $items) {
            $subtotal = 0.0;
            $orderItems = [];

            foreach ($items as $item) {
                $product = Product::whereKey($item['product_id'])->firstOrFail();

                if (! $product->is_active || ! $product->available_in_pos) {
                    abort(422, "El producto {$product->name} no está disponible para POS.");
                }

                $quantity = (int) $item['quantity'];
                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "No hay stock suficiente para {$product->name}. Disponibles: {$product->stock}.",
                    ]);
                }

                $lineTotal = round((float) $product->price * $quantity, 2);
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => (float) $product->price,
                    'subtotal' => $lineTotal,
                ];
            }

            $order = Order::create([
                'order_number' => 'POS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'channel' => 'pos',
                'status' => 'confirmed',
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total' => $subtotal,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'customer_name' => $validated['customer_name'] ?? 'Cliente POS',
                'customer_phone' => null,
                'notes' => $validated['notes'] ?? 'Venta console POS',
                'attendant_id' => Auth::id(),
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            foreach ($orderItems as $item) {
                $product = Product::whereKey($item['product_id'])->lockForUpdate()->firstOrFail();
                $before = (int) $product->stock;
                $after = $before - (int) $item['quantity'];
                $product->stock = max(0, $after);
                $product->save();

                InventoryLog::create([
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'type' => 'sale',
                    'quantity' => -(int) $item['quantity'],
                    'stock_before' => $before,
                    'stock_after' => $product->stock,
                    'channel' => 'pos',
                    'notes' => 'Venta POS ' . $order->order_number,
                    'created_at' => now(),
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['payment_reference'] ?? 'POS-' . $order->id,
                'amount' => $order->total,
                'currency' => 'PEN',
                'status' => 'completed',
                'provider' => 'pos_manual',
                'confirmed_by_id' => Auth::id(),
                'notes' => $validated['notes'] ?? 'Pago registrado en caja POS',
            ]);

            return $order;
        });

        return redirect()->route('admin.pos.index')->with('status', "Venta {$order->order_number} registrada correctamente.");
    }

    public function baristaIndex()
    {
        $orders = Order::with('items.product')
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->latest('created_at')
            ->get();

        return view('admin.barista.index', compact('orders'));
    }

    public function inventoryIndex(Request $request)
    {
        $query = Product::with('category')->orderBy('stock');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(15)->withQueryString();
        $recentLogs = InventoryLog::with('product')->latest('created_at')->limit(8)->get();
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock_alert')->count();

        return view('admin.inventory.index', compact('products', 'recentLogs', 'lowStockCount'));
    }

    public function adjustInventory(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:restock,adjustment,waste,return',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
        $before = (int) $product->stock;
        $delta = (int) $validated['quantity'];

        if (in_array($validated['type'], ['waste'], true)) {
            $delta = -abs($delta);
        } elseif (in_array($validated['type'], ['restock', 'adjustment', 'return'], true)) {
            $delta = abs($delta);
        }

        $updatedStock = max(0, $before + $delta);
        $product->stock = $updatedStock;
        $product->save();

        InventoryLog::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'quantity' => $delta,
            'stock_before' => $before,
            'stock_after' => $updatedStock,
            'channel' => 'manual',
            'notes' => $validated['notes'] ?? null,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.inventory.index')->with('status', "Inventario actualizado para {$product->name}.");
    }

    public function customersIndex(Request $request)
    {
        $query = Customer::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
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
