<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRoleFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(UserRoleFormRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validated();

        if ($user->getAuthIdentifier() === $request->user()?->getAuthIdentifier() && $validated['role'] !== 'admin') {
            return back()->withErrors(['error' => 'No puedes remover tu propio rol de administrador.']);
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('status', "Rol de usuario '{$user->name}' actualizado a: ".ucfirst($validated['role']).'.');
    }
}
