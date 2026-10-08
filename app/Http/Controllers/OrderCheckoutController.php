<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderCheckoutController extends Controller
{
    /**
     * Muestra la vista de checkout con el resumen del pedido.
     */
    public function showCheckout()
    {
        return view('checkout');
    }

    /**
     * Procesa la creación atómica del pedido con validación server-side.
     * Sprint A: venta web de principio a fin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_phone' => 'nullable|required_if:delivery_type,delivery|string|max:20',
            'customer_email' => 'nullable|email|max:150',
            'delivery_type'  => 'required|in:mesa,recojo,delivery',
            'table_number'   => 'nullable|string|max:20',
            'address'        => 'nullable|required_if:delivery_type,delivery|string|max:255',
            'notes'          => 'nullable|string|max:500',
            'items'          => 'required|array|min:1',
            'items.*.name'   => 'required|string|max:150',
            'items.*.quantity' => 'required|integer|min:1|max:50',
        ]);

        if ($validated['delivery_type'] === 'mesa' && empty($validated['table_number'])) {
            throw ValidationException::withMessages([
                'table_number' => 'Por favor indica el número o código de tu mesa.',
            ]);
        }

        if ($validated['delivery_type'] === 'delivery' && empty($validated['address'])) {
            throw ValidationException::withMessages([
                'address' => 'Por favor indica la dirección de entrega.',
            ]);
        }

        $order = DB::transaction(function () use ($validated) {
            $user = Auth::user();
            $customer = null;

            if ($user) {
                $customer = Customer::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $validated['customer_name'] ?: $user->name,
                        'email' => $user->email,
                        'phone' => $validated['customer_phone'],
                        'is_active' => true,
                    ]
                );
            }

            // Validar productos y calcular importes en el servidor (NUNCA confiar en precios del navegador)
            $orderItemsData = [];
            $subtotal = 0.0;

            foreach ($validated['items'] as $itemInput) {
                $productName = trim($itemInput['name']);
                $quantity = (int) $itemInput['quantity'];

                // Buscar producto activo en la base de datos
                $product = Product::where('name', $productName)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if ($product) {
                    $unitPrice = (float) $product->price;
                    $productId = $product->id;
                } else {
                    // Fallback a catálogo en config si no está en BD
                    $menuConfigProducts = collect(config('cafe.productos', []));
                    $configCatProducts = collect(config('cafe.menu_categorias', []))
                        ->pluck('items')
                        ->flatten(1);

                    $found = $menuConfigProducts->firstWhere('nombre', $productName)
                        ?: $configCatProducts->firstWhere('nombre', $productName);

                    if (! $found) {
                        throw ValidationException::withMessages([
                            'items' => "El producto '{$productName}' no se encuentra disponible en la carta.",
                        ]);
                    }

                    // Extraer precio numérico
                    $rawPrice = preg_replace('/[^0-9.]/', '', str_replace(',', '.', $found['precio']));
                    $unitPrice = (float) $rawPrice;

                    // Asegurar existencia mínima de producto en BD para mantener la clave foránea
                    $product = Product::firstOrCreate(
                        ['name' => $productName],
                        [
                            'price' => $unitPrice,
                            'cost_price' => round($unitPrice * 0.4, 2),
                            'stock' => 100,
                            'available_in_store' => true,
                            'is_active' => true,
                        ]
                    );
                    $productId = $product->id;
                }

                $itemSubtotal = round($unitPrice * $quantity, 2);
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal'   => $itemSubtotal,
                    'notes'      => null,
                ];
            }

            $discount = 0.0;
            $tax = 0.0; // Incluido en los precios o según configuración
            $total = round($subtotal - $discount + $tax, 2);

            // Generar número de orden único
            $datePrefix = date('Ymd');
            $randomSuffix = strtoupper(Str::random(4));
            $orderNumber = "WEB-{$datePrefix}-{$randomSuffix}";

            $notesCombined = "Modalidad: " . ucfirst($validated['delivery_type']);
            if (! empty($validated['table_number'])) {
                $notesCombined .= " (Mesa: {$validated['table_number']})";
            }
            if (! empty($validated['address'])) {
                $notesCombined .= " | Dirección: {$validated['address']}";
            }
            if (! empty($validated['notes'])) {
                $notesCombined .= " | Notas: {$validated['notes']}";
            }

            $order = Order::create([
                'order_number'    => $orderNumber,
                'customer_id'     => $customer?->id,
                'channel'         => 'ecommerce',
                'status'          => 'pending',
                'subtotal'        => $subtotal,
                'discount_amount' => $discount,
                'tax_amount'      => $tax,
                'total'           => $total,
                'payment_method'  => null,
                'payment_status'  => 'pending',
                'customer_name'   => $validated['customer_name'],
                'customer_phone'  => $validated['customer_phone'] ?? null,
                'notes'           => $notesCombined,
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            return $order;
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '¡Pedido creado con éxito!',
                'data' => [
                    'id'           => $order->id,
                    'order_number' => $order->order_number,
                    'total'        => 'S/ ' . number_format($order->total, 2),
                    'redirect_url' => route('pedido.confirmacion', ['order_number' => $order->order_number]),
                ],
            ]);
        }

        return redirect()->route('pedido.confirmacion', ['order_number' => $order->order_number]);
    }

    /**
     * Muestra la vista de confirmación y seguimiento de la orden.
     */
    public function confirmation(Request $request, string $order_number)
    {
        $order = Order::with('items.product')->where('order_number', $order_number)->firstOrFail();

        return view('order-success', compact('order'));
    }
}
