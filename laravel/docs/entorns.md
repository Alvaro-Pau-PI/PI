# 🐳 Entorns de Desenvolupament i Producció (Backend)

Laravel gestiona diferents configuracions segons l'entorn (`APP_ENV`). Aquest document explica com arrancar i configurar cada escenari.

## 1. Entorn de Desenvolupament (Local)

L'objectiu és tenir una instància ràpida amb debugging activat i accés complet a les eines de desenvolupament (Tinker, Telescope, etc.).

### ✨ Característiques
- **APP_ENV**: `local` (errors detallats, stack trace visible).
- **APP_DEBUG**: `true`.
- **Base de Dades**: MySQL en Docker (`db`), exposada al host (`localhost:3308`).
- **Serveis**: PHP-FPM, Nginx, MySQL, phpMyAdmin.
- **Accés**: `http://localhost:8000` (API), `http://localhost:8081` (phpMyAdmin).

### 🚀 Com arrancar (Docker)
Recomanat per garantir un entorn idèntic per a tots els desenvolupadors.

```bash
cd laravel
cp .env.example .env
docker compose up --build
```

**Configuració inicial (un sol cop):**
```bash
# Generar clau d'encriptació
docker compose exec laravel-app php artisan key:generate

# Instal·lar taules i dades de prova
docker compose exec laravel-app php artisan migrate --seed
```

### 💻 Com arrancar (Local - Sense Docker)
Si prefereixes `php artisan serve`, assegura't que `DB_HOST=127.0.0.1` al teu `.env`.

```bash
composer install
php artisan serve
```

---

## 2. Entorn de Producció (Cloud - AWS)

L'objectiu és màxim rendiment i seguretat. Errors ocults a l'usuari final.

### ✨ Característiques
- **APP_ENV**: `production` (errors genèrics 500).
- **APP_DEBUG**: `false`.
- **Optimitzacions**: Opcache actiu, rutes i configuració cacheades.
- **Base de Dades**: RDS (MySQL gestionat), no exposat públicament.
- **Serveis**: Només PHP-FPM i Nginx (sense phpMyAdmin per seguretat).

### 🚀 Desplegament
Es realitza automàticament mitjançant GitHub Actions (veure `ci_cd.md`).

El `docker-compose.prod.yml` defineix:
- `restart: always` per alta disponibilitat.
- Xarxa `pi_network_prod` aïllada.
- Volums persistents només per a logs i storage públic.

### ⚙️ Variables d'Entorn Crítiques (Secrets GitHub)
Aquestes variables s'injecten al contenidor en temps d'execució:

| Variable | Valor típic | Descripció |
|----------|-------------|------------|
| `APP_ENV` | `production` | Activa mode segur. |
| `APP_DEBUG` | `false` | Oculta errors de codi. |
| `DB_HOST` | `db` (o endpoint RDS) | Connexió a MySQL. |
| `DB_PASSWORD` | `*****` | Contrasenya segura (Secret). |
| `FRONTEND_URL` | `https://AlberoPerezTech...` | Per a CORS i Sanctum. |

---

## 🔄 Diferències Clau

| Característica | Desenvolupament | Producció |
|---------------|-----------------|-----------|
| **Debug** | ✅ `true` | ❌ `false` |
| **Migracions** | `migrate:fresh --seed` (destructiu) | `migrate --force` (conservatiu) |
| **Caché** | ❌ Desactivat (`cache:clear`) | ✅ Activat (`config:cache`, `route:cache`) |
| **Composer** | `install` (amb require-dev) | `install --no-dev --optimize-autoloader` |
| **Logs** | `storage/logs/laravel.log` | `daily` o servei extern (CloudWatch) |
