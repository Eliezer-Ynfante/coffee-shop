<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
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
            'customer_name'    => 'required|string|max:100',
            'customer_phone'   => 'nullable|required_if:delivery_type,delivery|string|max:20',
            'customer_email'   => 'nullable|email|max:150',
            'delivery_type'    => 'required|in:mesa,recojo,delivery',
            'table_number'     => 'nullable|string|max:20',
            'address'          => 'nullable|required_if:delivery_type,delivery|string|max:255',
            'notes'            => 'nullable|string|max:500',
            'items'            => 'required|array|min:1',
            'items.*.product_id' => ['nullable', 'integer', 'exists:products,id'],
            'items.*.name'     => 'nullable|string|max:150',
            'items.*.quantity' => 'required|integer|min:1|max:50',
        ]);

        foreach ($validated['items'] as $index => $itemInput) {
            if (empty($itemInput['product_id']) && empty($itemInput['name'])) {
                throw ValidationException::withMessages([
                    "items.{$index}.name" => 'Cada item debe indicar un producto existente por ID o por nombre.',
                ]);
            }
        }

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
            $customerId = null;

            if ($user instanceof User) {
                $fullName = trim((string) ($validated['customer_name'] ?: $user->name ?: 'Cliente'));
                $nameParts = array_pad(preg_split('/\s+/', $fullName, 2) ?: [], 2, '');

                /** @var Customer $customer */
                $customer = $user->customer()->firstOrNew([]);
                $userId = $user->getKey();

                $customer->fill([
                    'first_name' => mb_substr($nameParts[0] !== '' ? $nameParts[0] : 'Cliente', 0, 80),
                    'last_name' => mb_substr($nameParts[1] ?? '', 0, 80),
                    'phone' => $validated['customer_phone'] ?? null,
                    'address_line1' => $validated['address'] ?? null,
                    'is_active' => true,
                ]);
                $customer->setAttribute('user_id', $userId);
                $customer->save();
                $customerId = $customer->getKey();
            }

            // Validar productos y calcular importes en el servidor (NUNCA confiar en precios del navegador)
            $orderItemsData = [];
            $subtotal = 0.0;

            foreach ($validated['items'] as $itemInput) {
                $quantity = (int) $itemInput['quantity'];

                if (! empty($itemInput['product_id'])) {
                    $product = Product::whereKey($itemInput['product_id'])
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->first();
                } else {
                    $productName = trim((string) ($itemInput['name'] ?? ''));
                    $product = Product::where('name', $productName)
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->first();
                }

                if (! $product) {
                    $productLabel = ! empty($itemInput['product_id'])
                        ? 'ID ' . $itemInput['product_id']
                        : (string) ($itemInput['name'] ?? 'sin nombre');

                    throw ValidationException::withMessages([
                        'items' => "El producto {$productLabel} no existe o no está disponible en la carta.",
                    ]);
                }

                if (! $product->available_in_store) {
                    throw ValidationException::withMessages([
                        'items' => "El producto {$product->name} no está disponible para pedidos web.",
                    ]);
                }

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "No hay stock suficiente para {$product->name}. Disponibles: {$product->stock}.",
                    ]);
                }

                $unitPrice = (float) $product->price;
                $itemSubtotal = round($unitPrice * $quantity, 2);
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                    'notes' => null,
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
                'customer_id'     => $customerId,
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
