# Autenticació Laravel Breeze vs Manual (Sprint 2)

## Resum comparatiu

| Aspecte | Autenticació Manual (Sprint 2) | Laravel Breeze |
|---------|-------------------------------|----------------|
| **Sessions** | `session_start()` + `$_SESSION` manual | Middleware `auth` + Session driver configurable |
| **Cookies** | `setcookie()` manual per "remember me" | `remember_token` automàtic a Eloquent |
| **Hash passwords** | `password_hash()` manual | `Hash::make()` integrat al model User |
| **Validació** | Codi PHP manual amb `filter_var()` | Form Requests amb regles declaratives |
| **CSRF** | Tokens manuals o inexistents | `@csrf` automàtic a cada formulari |
| **Middleware** | Comprovacions IF a cada pàgina | Middleware `auth`, `guest`, `verified` |
| **Rutes protegides** | Codi repetit a cada fitxer | `->middleware('auth')` a routes/web.php |
| **Vistes** | HTML pur amb PHP barrejat | Blade components reutilitzables |
| **Recuperació password** | No implementat | Integrat amb email notifications |
| **Verificació email** | No implementat | Disponible amb `MustVerifyEmail` |

---

## Flux d'autenticació

### Manual (Sprint 2)
```
1. Usuari envia form → backend/login.php
2. PHP llegeix $_POST, valida manualment
3. Consulta JSON/MySQL amb password_verify()
4. Si OK: $_SESSION['user'] = $data
5. Cada pàgina: if (!isset($_SESSION['user'])) redirect
```

### Laravel Breeze
```
1. Usuari envia form → POST /login
2. AuthenticatedSessionController valida amb FormRequest
3. Auth::attempt() comprova credencials vs Eloquent User
4. Si OK: Session regenerada, remember_token si cal
5. Middleware 'auth' protegeix rutes automàticament
```

---

## Avantatges de Breeze

1. **Seguretat**: CSRF, rate limiting, session fixation protection integrats
2. **Mantenibilitat**: Codi estandarditzat i testejable
3. **Escalabilitat**: Fàcil afegir OAuth, 2FA, API tokens
4. **DX**: Vistes Blade + Tailwind llestes per usar

## Fitxers clau de Breeze

- `routes/auth.php` → Rutes d'autenticació
- `app/Http/Controllers/Auth/*` → Controladors
- `app/Http/Requests/Auth/*` → Validacions
- `resources/views/auth/*` → Vistes login/register
- `app/Http/Middleware/Authenticate.php` → Protecció rutes
