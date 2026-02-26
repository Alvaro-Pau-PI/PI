# Autenticación Laravel Breeze vs Manual (Sprint 2)

## Resumen comparativo

| Aspecto | Autenticación Manual (Sprint 2) | Laravel Breeze |
|---------|-------------------------------|----------------|
| **Sesiones** | `session_start()` + `$_SESSION` manual | Middleware `auth` + Session driver configurable |
| **Cookies** | `setcookie()` manual para "remember me" | `remember_token` automático en Eloquent |
| **Hash passwords** | `password_hash()` manual | `Hash::make()` integrado en el modelo User |
| **Validación** | Código PHP manual con `filter_var()` | Form Requests con reglas declarativas |
| **CSRF** | Tokens manuales o inexistentes | `@csrf` automático en cada formulario |
| **Middleware** | Comprobaciones IF en cada página | Middleware `auth`, `guest`, `verified` |
| **Rutas protegidas** | Código repetido en cada archivo | `->middleware('auth')` en routes/web.php |
| **Vistas** | HTML puro con PHP mezclado | Componentes Blade reutilizables |
| **Recuperación password** | No implementado | Integrado con email notifications |
| **Verificación email** | No implementado | Disponible con `MustVerifyEmail` |

---

## Flujo de autenticación

### Manual (Sprint 2)
```
1. Usuario envía form → backend/login.php
2. PHP lee $_POST, valida manualmente
3. Consulta JSON/MySQL con password_verify()
4. Si OK: $_SESSION['user'] = $data
5. Cada página: if (!isset($_SESSION['user'])) redirect
```

### Laravel Breeze
```
1. Usuario envía form → POST /login
2. AuthenticatedSessionController valida con FormRequest
3. Auth::attempt() comprueba credenciales vs Eloquent User
4. Si OK: Sesión regenerada, remember_token si es necesario
5. Middleware 'auth' protege rutas automáticamente
```

---

## Ventajas de Breeze

1. **Seguridad**: CSRF, rate limiting, session fixation protection integrados
2. **Mantenibilidad**: Código estandarizado y testeable
3. **Escalabilidad**: Fácil añadir OAuth, 2FA, API tokens
4. **DX**: Vistas Blade + Tailwind listas para usar

## Archivos clave de Breeze

- `routes/auth.php` → Rutas de autenticación
- `app/Http/Controllers/Auth/*` → Controladores
- `app/Http/Requests/Auth/*` → Validaciones
- `resources/views/auth/*` → Vistas login/register
- `app/Http/Middleware/Authenticate.php` → Protección de rutas
