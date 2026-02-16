<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirige al usuario a la página de autenticación de Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtiene la información del usuario de Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            // Obtener usuario de Google (stateless false para validar state/CSRF)
            $socialUser = Socialite::driver('google')->user();
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?error=invalid_state');
        } catch (\Exception $e) {
            return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?error=auth_failed');
        }

        // Buscar usuario por email
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Crear nuevo usuario si no existe
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(16)), // Contraseña aleatoria y segura
                'email_verified_at' => now(), // Email ya verificado por Google
            ]);
        }

        // Iniciar sesión (Login)
        Auth::login($user);

        // Regenerar sesión para evitar fijación
        request()->session()->regenerate();

        // Redirigir al frontend
        // Asumimos que Sanctum comparte la sesión por cookies (stateful)
        return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/');
    }
}
