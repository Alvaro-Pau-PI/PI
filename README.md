# 🛍️ AlberoPerezTech — Botiga en Línia Sostenible

Proyecto intermódulo de desarrollo web: una tienda en línea de productos tecnológicos con criterios de sostenibilidad (ASG).

## 📦 Stack Tecnològic

| Component | Tecnologia | Versió |
|-----------|-----------|--------|
| **Frontend** | Vue 3 + Vite + Pinia | 3.x / 7.x / 3.x |
| **Backend** | Laravel + Sanctum | 12.x / 4.x |
| **Base de dades** | MySQL | 8.0 |
| **Servidor web** | Nginx | stable-alpine |
| **Contenidors** | Docker + Docker Compose | — |

## 🐳 Desarrollo con Docker

### Requisitos previos

- [Docker](https://docs.docker.com/get-docker/) i [Docker Compose](https://docs.docker.com/compose/install/) instalados.

### Opción A: Arrancar TODO junto (docker-compose global)

Esta opció arranca frontend, backend, BD, phpMyAdmin i n8n en un sol comandament.

```bash
# Des de el raíz del projecte
docker compose up --build

# Acceso:
# Frontend:    http://localhost:5173
# API Laravel: http://localhost:8000
# phpMyAdmin:  http://localhost:8081
```

### Opción B: Arrancar cada aplicación de forma INDEPENDIENTE

Cada aplicació té el seu propi `docker-compose.yml` dins de la seua carpeta, permetent arrancar-la sense dependre de el altra.

#### Frontend Vue (independent)

```bash
cd frontend
cp .env.example .env        # Crear configuraciónn
docker compose up --build    # Arrancar
# → http://localhost:5173
```

> Consulta [frontend/README.md](frontend/README.md) per a més detalls.

#### Backend Laravel (independent)

```bash
cd laravel
cp .env.example .env                                   # Crear configuraciónn
docker compose up --build                              # Arrancar (Laravel + MySQL + Nginx + phpMyAdmin)
docker compose exec laravel-app php artisan key:generate   # Generar clave
docker compose exec laravel-app php artisan migrate --seed # Migraciones + datos de prueba
# → API:        http://localhost:8000
# → phpMyAdmin: http://localhost:8081
```

> Consulta [laravel/README.md](laravel/README.md) per a més detalls.

### ⚠️ Important

Abans de arrancar una opció, assegura't de aturar el altra per a evitar conflictes de ports:

```bash
docker compose down
```

## 🚀 Despliegue en Producción (CI/CD)

El proyecto está configurado para desplegarse automáticamente en una instància AWS EC2 utilitzant **GitHub Actions**.

### 1. Configuración del Servidor (EC2)

1. Conéctate a tu instancia: `ssh -i clau.pem ubuntu@IP`
2. Clona el repositori: `git clone <URL_REPO> PI && cd PI`
3. Crea el archivo `.env` de producció amb les credencials reals: `cp .env.example .env && nano .env`
4. Executa el script de configuració automàtica (Nginx + SSL):

```bash
sudo ./deploy/nginx/setup_prod.sh
```

Este script configurarà Nginx com a proxy invers i generarà certificats SSL amb Let's Encrypt per a `AlberoPerezTech.ddaw.es` y `api.AlberoPerezTech.ddaw.es`.

### 2. Secrets de GitHub Actions

Para que el CI/CD funcione, hay que configurar los siguientes "Repository Secrets" en GitHub:

| Secret | Descripciónn | Exemple |
|--------|-----------|---------|
| `EC2_HOST` | IP Elástica o DNS de la EC2 | `3.123.45.67` |
| `EC2_USER` | Usuario SSH | `ubuntu` |
| `EC2_SSH_KEY` | Contenido del archivo .pem | `-----BEGIN RSA PRIVATE KEY-----...` |
| `VITE_API_URL` | URL pública del backend | `https://api.AlberoPerezTech.ddaw.es` |
| `DB_PASSWORD` | Contraseña de la BD MySQL | `contrasenya_segura` |
| `VITE_N8N_WEBHOOK_URL` | Webhook N8N (Opcional) | `https://n8n...` |

### 3. Funcionamiento del Despliegue

- **Frontend**: En fer push a `main` (carpeta `frontend/`), es connecta per SSH, fa pull i ralberopereznstrueix el contenidor `pi_prod_frontend`.
- **Backend**: En fer push a `main` (carpeta `laravel/`), executa tests PHPUnit. Si passen, connecta per SSH, fa pull, ralberopereznstrueix `pi_prod_laravel_app` y ejecuta migraciones.

## 📁 Estructura del Proyecto

```
PI/
├── frontend/              # 🖥️  Aplicación Vue 3 (SPA)
│   ├── src/               #     Código fuente Vue
│   ├── Dockerfile         #     Imagen Docker (multi-stage)
│   ├── docker-compose.yml #     Docker independent
│   └── README.md          #     Documentación frontend
│
├── laravel/               # ⚙️  API REST Laravel
│   ├── app/               #     Código fuente Laravel
│   ├── database/          #     Migraciones y seeders
│   ├── docker/            #     Configuración Nginx
│   ├── Dockerfile         #     Imagen Docker (PHP-FPM)
│   ├── docker-compose.yml #     Docker independent
│   └── README.md          #     Documentación backend
│
├── docker-compose.yml     # 🐳  Docker Compose global (todo junto)
├── docs/                  # 📖  Documentación del proyecto
└── README.md              # 📄  Este fitxer
```

---

## Documentación y recursos

### Documentos Markdown

**Documentación Global**
- [🌐 Visión Global del Sistema](docs/global_system.md)
- [👥 Guía de Contribución](docs/guia_contribucio.md)
- [📋 Manual de Despliegue](docs/manual_desplegament.md)
- [📐 Arquitectura AWS Escalable](docs/arquitectura_aws.md)

**Documentación Específica**
- [Frontend: Arquitectura](frontend/docs/arquitectura.md) | [Entornos](frontend/docs/entorns.md) | [CI/CD](frontend/docs/ci_cd.md)
- [Backend: Arquitectura](laravel/docs/arquitectura.md) | [Entornos](laravel/docs/entorns.md) | [CI/CD](laravel/docs/ci_cd.md)

**Gestiónn**
- [Asignación de roles y responsabilidades](docs/rols.md)
- [Plan de riesgos y prevención](docs/RISKS.md)
- [Riesgos individuales](docs/riscos_individuals.md)


### Cronograma

- [Cronograma inicial Gantt (Sprint 1)](docs/gantt-SA1.pdf)

## Sprints

### Sprint 1: Entorno, escaparate y contacto
- [Informe Sprint 1](docs/sprint1.md) *(resume actividades realizadas, resultados y entregas de la primera iteración)*

### Sprint 2: Migració a PHP + JSON Server
- [Informe Sprint 2](docs/sprint2.md) *(versión backend legacy con PHP nativo)*

### Sprint 3: Migració a Laravel v2
- [Informe Sprint 3](docs/sprint3.md) *(backend moderno con Laravel, Breeze y MySQL)*

### Sprint 4: Cliente SPA con Vue y control de roles
- [Informe Sprint 4](docs/sprint4.md) *(interfaz moderna SPA, autenticación API y roles)*

### Sprint 5 i 6: Integraciones externas, Swagger, Docker, Despliegue final, calidad y entrega del producto
- [Informe Sprint 5 i 6](docs/sprint5_6.md) *(OAuth2, OpenAPI, NUV, SOST, DIW, etc.)*

---

## ♻️ Sostenibilidad y Criterios ASG

**🌱 Compromiso con el planeta** - Este projecte incorpora criteris de sostenibilitat basats en els pilars ASG (Ambiental, Social, Gobernança).

### Mejoras Implementadas

#### 🌍 Pilar Ambiental
- ✅ **Optimització de imatges**: Format WebP amb lazy loading
- ✅ **Reducción de peso web**: >40% de reducción en transferencia de datos
- ✅ **AlberoPereznomía circular**: Catálogo de productos reacondicionados
- ✅ **Etiquetas alberoperez**: AlberoPerez Score, Huella de carbono, Proveedores locales
- ✅ **Eficiencia**: Code splitting, minificació, tree-shaking

#### 👥 Pilar Social
- ✅ **Accessibilitat WCAG AA**: Lighthouse Accessibility Score ≥ 95
- ✅ **Navegación por teclado**: Experiencia completa sin ratón
- ✅ **Contraste de colores**: Ratios validados para legibilidad
- ✅ **Información clara**: Transparència en etiquetes i políticas
- ✅ **Inclusiónn**: Experiencia UX sin barreras

#### ⚖️ Pilar Gobernanza
- ✅ **Código documentado**: PHPDoc i JSDoc complet
- ✅ **Trazabilidad**: Criterios alberoperez verificables
- ✅ **Políticas públicas**: Documentación accesible en MkDocs
- ✅ **Calidad de código**: Principis SOLID, DRY, KISS
- ✅ **Métricas públicas**: Estadísticas de sostenibilidad en tiempo real

### Documentación Completa

📖 Consulta nuestra [Política de Sostenibilitat](docs/sostenibilidad.md) completa para más detalles.

🌐 También disponible en la web: [/sostenibilidad](/sostenibilidad)

### Métricas de Sostenibilidad

| Métrica | Valor Actual | Objectiu |
|---------|--------------|----------|
| Performance Score | 90+ | 95+ |
| Accessibility Score | 95+ | 100 |
| Reducció pes web | 40%+ | 60%+ |
| % Catàleg alberoperez | 20%+ | 50%+ |
