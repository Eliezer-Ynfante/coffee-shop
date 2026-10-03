<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLogin()
    {
        $user = Auth::user();
        if ($user instanceof User) {
            return $this->redirectByRole($user);
        }

        return view('auth.login');
    }

    /**
     * Procesa la autenticación del usuario.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user instanceof User) {
                return $this->redirectByRole($user, '¡Bienvenido de nuevo, '.$user->name.'!');
            }

            Auth::logout();
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Redirige al usuario según su rol.
     */
    protected function redirectByRole(User $user, ?string $message = null)
    {
        $targetRoute = $user->isAdmin() ? 'admin.dashboard' : 'customer.orders';
        $redirect = redirect()->intended(route($targetRoute));

        return $message ? $redirect->with('status', $message) : $redirect;
    }

    /**
     * Cierra la sesión activa.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('status', 'Has cerrado sesión correctamente.');
    }
}
