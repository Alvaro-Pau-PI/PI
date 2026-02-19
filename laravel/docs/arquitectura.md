# 🏗️ Arquitectura del Backend (Laravel 12)

## 🎯 Visió General
El backend és una **API RESTful** robusta desenvolupada amb Laravel 12. L'objectiu és proporcionar dades al frontend Vue de manera segura i eficient, gestionar l'autenticació d'usuaris i la lògica de negoci complexa.

## 🛠️ Stack Tecnològic

| Tecnologia | Versió | Justificació |
|-----------|--------|--------------|
| **Laravel** | 12.x | Framework PHP madur amb ecosistema ric. |
| **PHP** | 8.4 | Alt rendiment i tipat fort. |
| **MySQL** | 8.0 | Base de dades relacional fiable. |
| **Sanctum** | 4.x | Autenticació SPA simple i segura (cookies). |
| **Socialite** | 5.x | Integració OAuth (Google Login). |
| **Scribe** | * | Generació automàtica de documentació API. |
| **Nginx** | 1.25 | Servidor web d'alt rendiment (proxy invers). |

## 📂 Estructura de Directoris

```text
laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/ # Controladors API (ProductController...)
│   │   ├── Middleware/  # Filtres de peticions (Auth, Cors...)
│   │   └── Requests/    # Validació de formularis (FormRequest)
│   ├── Models/          # Models Eloquent (ORM)
│   └── Providers/       # Configuració de serveis
├── config/              # Configuració global (app, auth, database...)
├── database/
│   ├── migrations/      # Esquema de la BD (versionat)
│   ├── seeders/         # Dades inicials i de prova
│   └── factories/       # Generadors de dades falses
├── routes/
│   ├── api.php          # Rutes de l'API REST
│   └── web.php          # Rutes web (OAuth, Health Check)
├── tests/               # Tests automatitzats (Unit/Feature)
├── docker/              # Configuració Docker (Nginx, PHP)
├── .env.example         # Plantilla de variables d'entorn
└── composer.json        # Dependències PHP
```

## 🧩 Patrons de Disseny

### 1. MVC (Model-View-Controller)
Tot i que és una API (sense Vistes Blade), seguim el patró:
- **Model**: `app/Models/Product.php` (Lògica de dades).
- **Controller**: `app/Http/Controllers/ProductController.php` (Gestió de peticions).
- **Resource**: `app/Http/Resources/ProductResource.php` (Transformació JSON de sortida).

### 2. Service Layer (Opcional)
Per a lògica complexa (ex: processament de comandes), utilitzem Serveis (`app/Services/OrderService.php`) per mantenir els controladors prims ("Thin Controllers").

### 3. Repository Pattern (Simplificat)
Utilitzem Eloquent directament als controladors per a operacions CRUD simples, però Scope Queries (`scopeActive()`) per a reutilitzar filtres complexos.

## 🔒 Seguretat Backend

- **Autenticació**: Laravel Sanctum amb cookies `httpOnly` i `SameSite=Lax`.
- **Autorització**: Policies (`app/Policies/ProductPolicy.php`) per verificar permisos abans d'accions (ex: `update`).
- **Validació**: FormRequests (`StoreProductRequest`) garanteixen que les dades d'entrada són correctes.
- **CSRF**: Protecció automàtica en rutes web; Sanctum gestiona CSRF per a SPA.
