# 🐳 Entornos de Desarrollo y Producción (Backend)

Laravel gestiona diferentes configuraciones según el entorno (`APP_ENV`). Este documento explica cómo arrancar y configurar cada escenario.

## 1. Entorno de Desarrollo (Local)

El objetivo es tener una instancia rápida con debugging activado y acceso completo a las herramientas de desarrollo (Tinker, Telescope, etc.).

### ✨ Características
- **APP_ENV**: `local` (errores detallados, stack trace visible).
- **APP_DEBUG**: `true`.
- **Base de Datos**: MySQL en Docker (`db`), expuesta al host (`localhost:3308`).
- **Servicios**: PHP-FPM, Nginx, MySQL, phpMyAdmin.
- **Acceso**: `http://localhost:8000` (API), `http://localhost:8081` (phpMyAdmin).

### 🚀 Cómo arrancar (Docker)
Recomendado para garantizar un entorno idéntico para todos los desarrolladores.

```bash
cd laravel
cp .env.example .env
docker compose up --build
```

**Configuración inicial (solo una vez):**
```bash
# Generar clave de encriptación
docker compose exec laravel-app php artisan key:generate

# Instalar tablas y datos de prueba
docker compose exec laravel-app php artisan migrate --seed
```

### 💻 Cómo arrancar (Local - Sin Docker)
Si prefieres `php artisan serve`, asegúrate de que `DB_HOST=127.0.0.1` en tu `.env`.

```bash
composer install
php artisan serve
```

---

## 2. Entorno de Producción (Cloud - AWS)

El objetivo es máximo rendimiento y seguridad. Errores ocultos al usuario final.

### ✨ Características
- **APP_ENV**: `production` (errores genéricos 500).
- **APP_DEBUG**: `false`.
- **Optimizaciones**: Opcache activo, rutas y configuración cacheadas.
- **Base de Datos**: RDS (MySQL gestionado), no expuesto públicamente.
- **Servicios**: Solo PHP-FPM y Nginx (sin phpMyAdmin por seguridad).

### 🚀 Despliegue
Se realiza automáticamente mediante GitHub Actions (ver `ci_cd.md`).

El `docker-compose.prod.yml` define:
- `restart: always` para alta disponibilidad.
- Red `pi_network_prod` aislada.
- Volúmenes persistentes solo para logs y storage público.

### ⚙️ Variables de Entorno Críticas (Secretos GitHub)
Estas variables se inyectan al contenedor en tiempo de ejecución:

| Variable | Valor típico | Descripción |
|----------|-------------|------------|
| `APP_ENV` | `production` | Activa modo seguro. |
| `APP_DEBUG` | `false` | Oculta errores de código. |
| `DB_HOST` | `db` (o endpoint RDS) | Conexión a MySQL. |
| `DB_PASSWORD` | `*****` | Contraseña segura (Secreto). |
| `FRONTEND_URL` | `https://AlberoPerezTech...` | Para CORS y Sanctum. |

---

## 🔄 Diferencias Clave

| Característica | Desarrollo | Producción |
|---------------|-----------------|-----------|
| **Debug** | ✅ `true` | ❌ `false` |
| **Migraciones** | `migrate:fresh --seed` (destructivo) | `migrate --force` (conservativo) |
| **Caché** | ❌ Desactivado (`cache:clear`) | ✅ Activado (`config:cache`, `route:cache`) |
| **Composer** | `install` (con require-dev) | `install --no-dev --optimize-autoloader` |
| **Logs** | `storage/logs/laravel.log` | `daily` o servicio externo (CloudWatch) |
