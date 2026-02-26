# 🏗️ Arquitectura del Backend (Laravel 12)

## 🎯 Visión General
El backend es una **API RESTful** robusta desarrollada con Laravel 12. El objetivo es proporcionar datos al frontend Vue de manera segura y eficiente, gestionar la autenticación de usuarios y la lógica de negocio compleja.

## 🛠️ Stack Tecnológico

| Tecnología | Versión | Justificación |
|-----------|--------|--------------|
| **Laravel** | 12.x | Framework PHP maduro con ecosistema rico. |
| **PHP** | 8.4 | Alto rendimiento y tipado fuerte. |
| **MySQL** | 8.0 | Base de datos relacional fiable. |
| **Sanctum** | 4.x | Autenticación SPA simple y segura (cookies). |
| **Socialite** | 5.x | Integración OAuth (Google Login). |
| **Scribe** | * | Generación automática de documentación API. |
| **Nginx** | 1.25 | Servidor web de alto rendimiento (proxy inverso). |

## 📂 Estructura de Directorios

```text
laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/ # Controladores API (ProductController...)
│   │   ├── Middleware/  # Filtros de peticiones (Auth, Cors...)
│   │   └── Requests/    # Validación de formularios (FormRequest)
│   ├── Models/          # Modelos Eloquent (ORM)
│   └── Providers/       # Configuración de servicios
├── config/              # Configuración global (app, auth, database...)
├── database/
│   ├── migrations/      # Esquema de la BD (versionado)
│   ├── seeders/         # Datos iniciales y de prueba
│   └── factories/       # Generadores de datos falsos
├── routes/
│   ├── api.php          # Rutas de la API REST
│   └── web.php          # Rutas web (OAuth, Health Check)
├── tests/               # Tests automatizados (Unit/Feature)
├── docker/              # Configuración Docker (Nginx, PHP)
├── .env.example         # Plantilla de variables de entorno
└── composer.json        # Dependencias PHP
```

## 🧩 Patrones de Diseño

### 1. MVC (Model-View-Controller)
Aunque es una API (sin Vistas Blade), seguimos el patrón:
- **Model**: `app/Models/Product.php` (Lógica de datos).
- **Controller**: `app/Http/Controllers/ProductController.php` (Gestión de peticiones).
- **Resource**: `app/Http/Resources/ProductResource.php` (Transformación JSON de salida).

### 2. Service Layer (Opcional)
Para lógica compleja (ej: procesamiento de pedidos), utilizamos Servicios (`app/Services/OrderService.php`) para mantener los controladores delgados ("Thin Controllers").

### 3. Repository Pattern (Simplificado)
Utilizamos Eloquent directamente en los controladores para operaciones CRUD simples, pero Scope Queries (`scopeActive()`) para reutilizar filtros complejos.

## 🔒 Seguridad Backend

- **Autenticación**: Laravel Sanctum con cookies `httpOnly` y `SameSite=Lax`.
- **Autorización**: Policies (`app/Policies/ProductPolicy.php`) para verificar permisos antes de acciones (ej: `update`).
- **Validación**: FormRequests (`StoreProductRequest`) garantizan que los datos de entrada son correctos.
- **CSRF**: Protección automática en rutas web; Sanctum gestiona CSRF para SPA.
