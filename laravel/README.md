# 🛍️ EcoTech Backend — Laravel 12

API REST del backend de la botiga en línia EcoTech, desenvolupada amb **Laravel 12**, **MySQL** i **Sanctum** per a autenticació.

## 📦 Stack Tecnològic

| Tecnologia | Versió | Funció |
|-----------|--------|--------|
| Laravel | 12.x | Framework PHP |
| PHP | 8.4 | Llenguatge backend |
| MySQL | 8.0 | Base de dades relacional |
| Sanctum | 4.x | Autenticació SPA (cookies) |
| Socialite | 5.x | OAuth (Google Login) |
| Scribe | * | Documentació API automàtica |
| Maatwebsite Excel | 3.x | Importació/exportació CSV/Excel |

## 🐳 Desenvolupament amb Docker

### Requisits previs

- [Docker](https://docs.docker.com/get-docker/) i [Docker Compose](https://docs.docker.com/compose/install/) instal·lats.

### Instruccions

```bash
# 1. Clonar el repositori (si encara no ho has fet)
git clone <url-del-repo>
cd laravel

# 2. Crear el fitxer de configuració
cp .env.example .env

# 3. Arrancar els contenidors
docker compose up --build

# 4. Generar la clau de l'aplicació
docker compose exec laravel-app php artisan key:generate

# 5. Executar migracions i seeders
docker compose exec laravel-app php artisan migrate --seed

# 6. Accedir a l'aplicació
# API Laravel:  http://localhost:8000
# phpMyAdmin:   http://localhost:8081
```

### Comandes útils

```bash
# Executar tests
docker compose exec laravel-app php artisan test

# Crear una nova migració
docker compose exec laravel-app php artisan make:migration create_example_table

# Netejar cachés
docker compose exec laravel-app php artisan cache:clear
docker compose exec laravel-app php artisan config:clear

# Accedir al contenidor PHP
docker compose exec laravel-app bash

# Aturar tots els serveis
docker compose down

# Aturar i esborrar volums (ATENCIÓ: esborra la BD)
docker compose down -v
```

## 💻 Desenvolupament sense Docker (local)

### Requisits previs

- PHP >= 8.2 amb extensions: pdo_mysql, mbstring, zip, gd, bcmath
- Composer >= 2
- MySQL >= 8.0
- Node.js >= 20 (per al build d'assets Vite)

### Instruccions

```bash
# 1. Instal·lar dependències PHP
composer install

# 2. Crear i configurar .env (ajustar DB_HOST=127.0.0.1)
cp .env.example .env
php artisan key:generate

# 3. Executar migracions i seeders
php artisan migrate --seed

# 4. Arrancar el servidor
php artisan serve  # http://localhost:8000
```

## ⚙️ Variables d'Entorn Principals

| Variable | Descripció | Valor per defecte (Docker) |
|----------|-----------|---------------------------|
| `APP_URL` | URL base de l'aplicació | `http://localhost:8000` |
| `FRONTEND_URL` | URL del frontend Vue | `http://localhost:5173` |
| `DB_HOST` | Host de la BD | `db` (nom del contenidor) |
| `DB_DATABASE` | Nom de la BD | `pi_db` |
| `DB_USERNAME` | Usuari de la BD | `pi_user` |
| `DB_PASSWORD` | Contrasenya de la BD | `pi_password` |
| `SANCTUM_STATEFUL_DOMAINS` | Dominis per a cookies Sanctum | `localhost:5173` |

## 📚 Documentació Tècnica

El backend disposa de documentació detallada a la carpeta `docs/`:

- [🏗️ Arquitectura i Patrons](docs/arquitectura.md)
- [🐳 Entorns: Desenvolupament vs Producció](docs/entorns.md)
- [🔄 CI/CD i Tests](docs/ci_cd.md)

## 📁 Estructura del projecte

```
laravel/
├── app/
│   ├── Http/Controllers/   # Controladors de l'API
│   ├── Models/              # Models Eloquent
│   └── ...
├── database/
│   ├── migrations/          # Migracions de la BD
│   └── seeders/             # Seeders amb dades de prova
├── routes/
│   ├── api.php              # Rutes de l'API REST
│   └── web.php              # Rutes web (OAuth, etc.)
├── tests/                   # Tests PHPUnit
├── docker/
│   └── nginx.conf           # Configuració Nginx per a Docker
├── Dockerfile               # Imatge Docker (PHP-FPM)
├── docker-compose.yml       # Composició per a desenvolupament
├── .env.example             # Plantilla de variables d'entorn
└── composer.json            # Dependències PHP
```

## 🔌 Endpoints principals de l'API

| Mètode | Ruta | Descripció |
|--------|------|-----------|
| `GET` | `/api/products` | Llistat de productes |
| `GET` | `/api/products/{id}` | Detall d'un producte |
| `POST` | `/api/products/{id}/reviews` | Afegir valoració |
| `POST` | `/register` | Registre d'usuari |
| `POST` | `/login` | Iniciar sessió |
| `POST` | `/logout` | Tancar sessió |

📖 Documentació completa de l'API disponible en: `http://localhost:8000/docs`
