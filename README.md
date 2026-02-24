# 🛍️ EcoTech — Botiga en Línia Sostenible

Projecte intermòdul de desenvolupament web: una botiga en línia de productes tecnològics amb criteris de sostenibilitat (ASG).

## 📦 Stack Tecnològic

| Component | Tecnologia | Versió |
|-----------|-----------|--------|
| **Frontend** | Vue 3 + Vite + Pinia | 3.x / 7.x / 3.x |
| **Backend** | Laravel + Sanctum | 12.x / 4.x |
| **Base de dades** | MySQL | 8.0 |
| **Servidor web** | Nginx | stable-alpine |
| **Contenidors** | Docker + Docker Compose | — |

## 🐳 Desenvolupament amb Docker

### Requisits previs

- [Docker](https://docs.docker.com/get-docker/) i [Docker Compose](https://docs.docker.com/compose/install/) instal·lats.

### Opció A: Arrancar TOT junt (docker-compose global)

Aquesta opció arranca frontend, backend, BD, phpMyAdmin i n8n en un sol comandament.

```bash
# Des de l'arrel del projecte
docker compose up --build

# Accés:
# Frontend:    http://localhost:5173
# API Laravel: http://localhost:8000
# phpMyAdmin:  http://localhost:8081
```

### Opció B: Arrancar cada aplicació de forma INDEPENDENT

Cada aplicació té el seu propi `docker-compose.yml` dins de la seua carpeta, permetent arrancar-la sense dependre de l'altra.

#### Frontend Vue (independent)

```bash
cd frontend
cp .env.example .env        # Crear configuració
docker compose up --build    # Arrancar
# → http://localhost:5173
```

> Consulta [frontend/README.md](frontend/README.md) per a més detalls.

#### Backend Laravel (independent)

```bash
cd laravel
cp .env.example .env                                   # Crear configuració
docker compose up --build                              # Arrancar (Laravel + MySQL + Nginx + phpMyAdmin)
docker compose exec laravel-app php artisan key:generate   # Generar clau
docker compose exec laravel-app php artisan migrate --seed # Migracions + dades de prova
# → API:        http://localhost:8000
# → phpMyAdmin: http://localhost:8081
```

> Consulta [laravel/README.md](laravel/README.md) per a més detalls.

### ⚠️ Important

Abans d'arrancar una opció, assegura't d'aturar l'altra per a evitar conflictes de ports:

```bash
docker compose down
```

## 🚀 Desplegament en Producció (CI/CD)

El projecte està configurat per a desplegar-se automàticament en una instància AWS EC2 utilitzant **GitHub Actions**.

### 1. Configuració del Servidor (EC2)

1. Connecta't a la teua instància: `ssh -i clau.pem ubuntu@IP`
2. Clona el repositori: `git clone <URL_REPO> PI && cd PI`
3. Crea el fitxer `.env` de producció amb les credencials reals: `cp .env.example .env && nano .env`
4. Executa l'script de configuració automàtica (Nginx + SSL):

```bash
sudo ./deploy/nginx/setup_prod.sh
```

Aquest script configurarà Nginx com a proxy invers i generarà certificats SSL amb Let's Encrypt per a `AlberoPerezTech.ddaw.es` y `api.AlberoPerezTech.ddaw.es`.

### 2. Secrets de GitHub Actions

Per a que el CI/CD funcione, cal configurar els següents "Repository Secrets" en GitHub:

| Secret | Descripció | Exemple |
|--------|-----------|---------|
| `EC2_HOST` | IP Elàstica o DNS de la EC2 | `3.123.45.67` |
| `EC2_USER` | Usuari SSH | `ubuntu` |
| `EC2_SSH_KEY` | Contingut del fitxer .pem | `-----BEGIN RSA PRIVATE KEY-----...` |
| `VITE_API_URL` | URL pública del backend | `https://api.AlberoPerezTech.ddaw.es` |
| `DB_PASSWORD` | Contrasenya de la BD MySQL | `contrasenya_segura` |
| `VITE_N8N_WEBHOOK_URL` | Webhook N8N (Opcional) | `https://n8n...` |

### 3. Funcionament del Desplegament

- **Frontend**: En fer push a `main` (carpeta `frontend/`), es connecta per SSH, fa pull i reconstrueix el contenidor `pi_prod_frontend`.
- **Backend**: En fer push a `main` (carpeta `laravel/`), executa tests PHPUnit. Si passen, connecta per SSH, fa pull, reconstrueix `pi_prod_laravel_app` i executa migracions.

## 📁 Estructura del Projecte

```
PI/
├── frontend/              # 🖥️  Aplicació Vue 3 (SPA)
│   ├── src/               #     Codi font Vue
│   ├── Dockerfile         #     Imatge Docker (multi-stage)
│   ├── docker-compose.yml #     Docker independent
│   └── README.md          #     Documentació frontend
│
├── laravel/               # ⚙️  API REST Laravel
│   ├── app/               #     Codi font Laravel
│   ├── database/          #     Migracions i seeders
│   ├── docker/            #     Configuració Nginx
│   ├── Dockerfile         #     Imatge Docker (PHP-FPM)
│   ├── docker-compose.yml #     Docker independent
│   └── README.md          #     Documentació backend
│
├── docker-compose.yml     # 🐳  Docker Compose global (tot junt)
├── docs/                  # 📖  Documentació del projecte
└── README.md              # 📄  Aquest fitxer
```

---

## Documentació i recursos

### Documents Markdown

**Documentació Global**
- [🌐 Visió Global del Sistema](docs/global_system.md)
- [👥 Guia de Contribució](docs/guia_contribucio.md)
- [📋 Manual de Desplegament](docs/manual_desplegament.md)
- [📐 Arquitectura AWS Escalable](docs/arquitectura_aws.md)

**Documentació Específica**
- [Frontend: Arquitectura](frontend/docs/arquitectura.md) | [Entorns](frontend/docs/entorns.md) | [CI/CD](frontend/docs/ci_cd.md)
- [Backend: Arquitectura](laravel/docs/arquitectura.md) | [Entorns](laravel/docs/entorns.md) | [CI/CD](laravel/docs/ci_cd.md)

**Gestió**
- [Assignació de rols i responsabilitats](docs/rols.md)
- [Pla de riscos i prevenció](docs/RISKS.md)
- [Riscos individuals](docs/riscos_individuals.md)


### Cronograma

- [Cronograma inicial Gantt (Sprint 1)](docs/gantt-SA1.pdf)

## Sprints

### Sprint 1: Entorn, aparador i contacte
- [Informe Sprint 1](docs/sprint1.md) *(resume activitats realitzades, resultats i lliuraments de la primera iteració)*

### Sprint 2: Migració a PHP + JSON Server
- [Informe Sprint 2](docs/sprint2.md) *(versió backend legacy amb PHP natiu)*

### Sprint 3: Migració a Laravel v2
- [Informe Sprint 3](docs/sprint3.md) *(backend modern amb Laravel, Breeze i MySQL)*

---

## ♻️ Sostenibilitat i Criteris ASG

**🌱 Compromís amb el planeta** - Aquest projecte incorpora criteris de sostenibilitat basats en els pilars ASG (Ambiental, Social, Gobernança).

### Millores Implementades

#### 🌍 Pilar Ambiental
- ✅ **Optimització d'imatges**: Format WebP amb lazy loading
- ✅ **Reducció de pes web**: >40% de reducció en transferència de dades
- ✅ **Economia circular**: Catàleg de productes reacondicionats
- ✅ **Etiquetes eco**: Eco Score, Huella de carboni, Proveïdors locals
- ✅ **Eficiència**: Code splitting, minificació, tree-shaking

#### 👥 Pilar Social
- ✅ **Accessibilitat WCAG AA**: Lighthouse Accessibility Score ≥ 95
- ✅ **Navegació per teclat**: Experiència completa sense ratolí
- ✅ **Contrast de colors**: Ratios validats per a llegibilitat
- ✅ **Informació clara**: Transparència en etiquetes i polítiques
- ✅ **Inclusió**: Experiència UX sense barreres

#### ⚖️ Pilar Gobernança
- ✅ **Codi documentat**: PHPDoc i JSDoc complet
- ✅ **Traçabilitat**: Criteris eco verificables
- ✅ **Polítiques públiques**: Documentació accessible en MkDocs
- ✅ **Qualitat de codi**: Principis SOLID, DRY, KISS
- ✅ **Mètriques públiques**: Estadístiques de sostenibilitat en temps real

### Documentació Completa

📖 Consulta la nostra [Política de Sostenibilitat](docs/sostenibilidad.md) completa per a més detalls.

🌐 També disponible a la web: [/sostenibilidad](/sostenibilidad)

### Métricas de Sostenibilitat

| Métrica | Valor Actual | Objectiu |
|---------|--------------|----------|
| Performance Score | 90+ | 95+ |
| Accessibility Score | 95+ | 100 |
| Reducció pes web | 40%+ | 60%+ |
| % Catàleg eco | 20%+ | 50%+ |
