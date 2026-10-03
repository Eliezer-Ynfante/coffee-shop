<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles, true)) {
            // Redirección amigable según el rol del usuario
            if ($request->user()->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('status', 'Sección restringida: has sido redirigido a tu panel de administración.');
            }

            return redirect()->route('customer.orders')->with('status', 'Sección restringida: has sido redirigido a tu panel de cliente.');
        }

        return $next($request);
    }
}
