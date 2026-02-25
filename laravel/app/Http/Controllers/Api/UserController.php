<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controlador de Usuarios (Administración).
 */
class UserController extends Controller
{
    /**
     * Listar todos los usuarios.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(15);
        
        return response()->json($users);
    }

    /**
     * Actualizar el rol de un usuario.
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:admin,user',
        ]);

        // Evitar que un admin se quite el rol a sí mismo (seguridad básica)
        if ($request->user()->id === $user->id && $validated['role'] !== 'admin') {
            return response()->json(['message' => 'No pots llevar-te el rol de administrador a tu mateix.'], 422);
        }

        $user->update(['role' => $validated['role']]);

        return response()->json($user);
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy(Request $request, User $user)
    {
        // Evitar que un admin se elimine a sí mismo
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'No pots eliminar el teu propi compte.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'Usuari eliminat correctament.']);
    }
}
