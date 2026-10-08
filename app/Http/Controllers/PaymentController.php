<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Procesamiento seguro de pago para una orden.
     * Sprint B: pagos y conciliación.
     */
    public function processPayment(Request $request, string $order_number)
    {
        $validated = $request->validate([
            'payment_method'        => ['required', 'string', Rule::in(['cash', 'card', 'yape', 'plin'])],
            'transaction_reference' => 'required_if:payment_method,yape,plin|string|max:100',
            'cash_code'             => 'required_if:payment_method,cash|string|max:100',
            'card_holder'           => 'required_if:payment_method,card|string|max:100',
            'card_number'           => ['required_if:payment_method,card', 'string', 'regex:/^[0-9 ]{13,23}$/'],
            'card_exp'              => ['required_if:payment_method,card', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
            'card_cvc'              => 'required_if:payment_method,card|digits_between:3,4',
            'payment_voucher'       => 'required_if:payment_method,yape,plin|image|mimes:jpeg,png,jpg,webp|max:5120',
            'notes'                 => 'nullable|string|max:255',
        ]);

        $order = Order::where('order_number', $order_number)->firstOrFail();

        // Regla contra pagos duplicados o conciliación falsa
        if ($order->payment_status === 'paid') {
            throw ValidationException::withMessages([
                'payment_method' => "El pedido #{$order->order_number} ya se encuentra pagado y confirmado.",
            ]);
        }

        // Manejo de referencia por método de pago
        $ref = match ($validated['payment_method']) {
            'yape', 'plin' => $validated['transaction_reference'],
            'cash' => $validated['cash_code'],
            'card' => 'CARD-****-' . substr(preg_replace('/[^0-9]/', '', $validated['card_number']), -4),
        };

        // Procesar archivo adjunto si fue subido
        $voucherPath = null;
        if ($request->hasFile('payment_voucher')) {
            $file = $request->file('payment_voucher');
            $filename = 'voucher_' . $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $voucherPath = $file->storeAs('vouchers', $filename, 'public');
        }

        $payment = DB::transaction(function () use ($order, $validated, $ref, $voucherPath) {
            $payment = null;

            $payment = Payment::create([
                'order_id'              => $order->id,
                'payment_method'        => $validated['payment_method'],
                'transaction_reference' => $ref,
                'amount'                => $order->total,
                'currency'              => 'PEN',
                'status'                => 'completed',
                'provider'              => 'manual',
                'confirmed_by_id'       => Auth::id(),
                'notes'                 => $voucherPath ? "Comprobante: {$voucherPath}" : ($validated['notes'] ?? null),
            ]);

            // Conciliación atómica: actualizar orden a pagada y confirmada
            $order->update([
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'status'         => 'confirmed',
            ]);

            return $payment;
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '¡Pago procesado y conciliado con éxito!',
                'data'    => [
                    'order_number'          => $order->order_number,
                    'payment_id'            => $payment->id,
                    'transaction_reference' => $ref,
                    'amount'                => 'S/ ' . number_format($order->total, 2),
                    'payment_method'        => strtoupper($validated['payment_method']),
                    'status'                => 'completed',
                ],
            ]);
        }

        return redirect()->route('pedido.confirmacion', ['order_number' => $order->order_number])
            ->with('status', '¡Pago realizado con éxito! Tu pedido ha sido confirmado.');
    }

    /**
     * Procesamiento de Reembolso / Anulación de pago conciliado.
     */
    public function refundPayment(Request $request, string $order_number)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $order = Order::where('order_number', $order_number)->firstOrFail();

        if ($order->payment_status !== 'paid') {
            throw ValidationException::withMessages([
                'reason' => 'Solo se pueden reembolsar pedidos que hayan sido pagados previamente.',
            ]);
        }

        DB::transaction(function () use ($order, $validated) {
            $latestPayment = Payment::where('order_id', $order->id)->where('status', 'completed')->latest()->first();

            if ($latestPayment) {
                $latestPayment->update([
                    'status'        => 'refunded',
                    'refund_reason' => $validated['reason'],
                    'refunded_at'   => now(),
                ]);
            }

            $order->update([
                'payment_status'      => 'refunded',
                'status'              => 'refunded',
                'cancelled_at'        => now(),
                'cancellation_reason' => 'Reembolso de pago: ' . $validated['reason'],
            ]);
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'El pago ha sido reembolsado exitosamente.',
            ]);
        }

        return back()->with('status', 'El pago fue reembolsado correctamente.');
    }
}
