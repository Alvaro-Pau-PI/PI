# 🛍️ AlberoPerezTech Backend — Laravel 12

API REST del backend de la tienda en línea AlberoPerezTech, desarrollada con **Laravel 12**, **MySQL** y **Sanctum** para autenticación.

## 📦 Stack Tecnológico

| Tecnología | Versión | Función |
|-----------|--------|--------|
| Laravel | 12.x | Framework PHP |
| PHP | 8.4 | Lenguaje backend |
| MySQL | 8.0 | Base de datos relacional |
| Sanctum | 4.x | Autenticación SPA (cookies) |
| Socialite | 5.x | OAuth (Google Login) |
| Scribe | * | Documentación API automática |
| Maatwebsite Excel | 3.x | Importación/exportación CSV/Excel |

## 🐳 Desarrollo con Docker

### Requisitos previos

- [Docker](https://docs.docker.com/get-docker/) y [Docker Compose](https://docs.docker.com/compose/install/) instalados.

### Instrucciones

```bash
# 1. Clonar el repositorio (si aún no lo has hecho)
git clone <url-del-repo>
cd laravel

# 2. Crear el archivo de configuración
cp .env.example .env

# 3. Arrancar los contenedores
docker compose up --build

# 4. Generar la clave de la aplicación
docker compose exec laravel-app php artisan key:generate

# 5. Ejecutar migraciones y seeders
docker compose exec laravel-app php artisan migrate --seed

# 6. Acceder a la aplicación
# API Laravel:  http://localhost:8000
# phpMyAdmin:   http://localhost:8081
```

### Comandos útiles

```bash
# Ejecutar tests
docker compose exec laravel-app php artisan test

# Crear una nueva migración
docker compose exec laravel-app php artisan make:migration create_example_table

# Limpiar cachés
docker compose exec laravel-app php artisan cache:clear
docker compose exec laravel-app php artisan config:clear

# Acceder al contenedor PHP
docker compose exec laravel-app bash

# Detener todos los servicios
docker compose down

# Detener y borrar volúmenes (ATENCIÓN: borra la BD)
docker compose down -v
```

## 💻 Desarrollo sin Docker (local)

### Requisitos previos

- PHP >= 8.2 con extensiones: pdo_mysql, mbstring, zip, gd, bcmath
- Composer >= 2
- MySQL >= 8.0
- Node.js >= 20 (para el build de assets Vite)

### Instrucciones

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Crear y configurar .env (ajustar DB_HOST=127.0.0.1)
cp .env.example .env
php artisan key:generate

# 3. Ejecutar migraciones y seeders
php artisan migrate --seed

# 4. Arrancar el servidor
php artisan serve  # http://localhost:8000
```

## ⚙️ Variables de Entorno Principales

| Variable | Descripción | Valor por defecto (Docker) |
|----------|-----------|---------------------------|
| `APP_URL` | URL base de la aplicación | `http://localhost:8000` |
| `FRONTEND_URL` | URL del frontend Vue | `http://localhost:5173` |
| `DB_HOST` | Host de la BD | `db` (nombre del contenedor) |
| `DB_DATABASE` | Nombre de la BD | `pi_db` |
| `DB_USERNAME` | Usuario de la BD | `pi_user` |
| `DB_PASSWORD` | Contraseña de la BD | `pi_password` |
| `SANCTUM_STATEFUL_DOMAINS` | Dominios para cookies Sanctum | `localhost:5173` |

## 📚 Documentación Técnica

El backend dispone de documentación detallada en la carpeta `docs/`:

- [🏗️ Arquitectura y Patrones](docs/arquitectura.md)
- [🐳 Entornos: Desarrollo vs Producción](docs/entorns.md)
- [🔄 CI/CD y Tests](docs/ci_cd.md)

## 📁 Estructura del proyecto

```
laravel/
├── app/
│   ├── Http/Controllers/   # Controladores de la API
│   ├── Models/              # Modelos Eloquent
│   └── ...
├── database/
│   ├── migrations/          # Migraciones de la BD
│   └── seeders/             # Seeders con datos de prueba
├── routes/
│   ├── api.php              # Rutas de la API REST
│   └── web.php              # Rutas web (OAuth, etc.)
├── tests/                   # Tests PHPUnit
├── docker/
│   └── nginx.conf           # Configuración Nginx para Docker
├── Dockerfile               # Imagen Docker (PHP-FPM)
├── docker-compose.yml       # Composición para desarrollo
├── .env.example             # Plantilla de variables de entorno
└── composer.json            # Dependencias PHP
```

## 🔌 Endpoints principales de la API

| Método | Ruta | Descripción |
|--------|------|-----------|
| `GET` | `/api/products` | Listado de productos |
| `GET` | `/api/products/{id}` | Detalle de un producto |
| `POST` | `/api/products/{id}/reviews` | Añadir valoración |
| `POST` | `/register` | Registro de usuario |
| `POST` | `/login` | Iniciar sesión |
| `POST` | `/logout` | Cerrar sesión |

📖 Documentación completa de la API disponible en: `http://localhost:8000/docs`
