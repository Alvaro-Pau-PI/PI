<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de mensajes de contacto.
 */
class ContactController extends Controller
{
    /**
     * [ADMIN] Listar todos los mensajes de contacto.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json($contacts);
    }

    /**
     * Guardar un nuevo mensaje de contacto (público).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        return response()->json([
            'message' => 'Mensaje enviado correctamente.',
            'contact' => $contact
        ], 201);
    }

    /**
     * [ADMIN] Eliminar un mensaje de contacto.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['message' => 'Mensaje eliminado correctamente.']);
    }
}
