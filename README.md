# 🛍️ AlberoPerezTech — Tienda en Línea Sostenible

Proyecto intermódulo de desarrollo web: una tienda en línea de productos tecnológicos con criterios de sostenibilidad (ASG).

## 🎓 Módulos del Proyecto

Este proyecto intermodular integra 7 módulos especializados, cada uno con sus propias tecnologías, tareas y resultados de aprendizaje:

| Módulo | Descripción | Tecnologías Principales | Estado |
|--------|-------------|------------------------|---------|
| **[DWES](docs/modulos/DWES/)** | Desplegament Web Entorn Servidor | Laravel 12, MySQL, Sanctum | ✅ Completo |
| **[DWEC](docs/modulos/DWEC/)** | Desplegament Web Entorn Client | Vue 3, Vite, Pinia, Vue Router | ✅ Completo |
| **[DIW](docs/modulos/DIW/)** | Disseny d'Interfícies Web | CSS3, WCAG 2.1, Responsive Design | ✅ Completo |
| **[DIG](docs/modulos/DIG/)** | Digitalització | Analytics, ML, n8n, Automatización | ✅ Completo |
| **[SOST](docs/modulos/SOST/)** | Sostenibilitat | ASG, Ecodiseño, Accesibilidad | ✅ Completo |
| **[DDAW](docs/modulos/DDAW/)** | Desplegament d'Aplicacions Web | AWS, Nginx, CI/CD, Docker | ✅ Completo |
| **[NUV](docs/modulos/NUV/)** | Núvol | AWS Cloud, Auto Scaling, CloudWatch | ✅ Completo |

📖 **[Ver documentación completa de módulos](docs/modulos/)**

---

## 📦 Stack Tecnológico

| Componente | Tecnología | Versión |
|-----------|-----------|--------|
| **Frontend** | Vue 3 + Vite + Pinia | 3.x / 7.x / 3.x |
| **Backend** | Laravel + Sanctum | 12.x / 4.x |
| **Base de datos** | MySQL | 8.0 |
| **Servidor web** | Nginx | stable-alpine |
| **Contenedores** | Docker + Docker Compose | — |

## 🐳 Desarrollo con Docker

### Requisitos previos

- [Docker](https://docs.docker.com/get-docker/) y [Docker Compose](https://docs.docker.com/compose/install/) instalados.

### Opción A: Arrancar TODO junto (docker-compose global)

Esta opción arranca frontend, backend, BD, phpMyAdmin y n8n en un solo comando.

```bash
# Desde la raíz del proyecto
docker compose up --build

# Acceso:
# Frontend:    http://localhost:5173
# API Laravel: http://localhost:8000
# phpMyAdmin:  http://localhost:8081
```

### Opción B: Arrancar cada aplicación de forma INDEPENDIENTE

Cada aplicación tiene su propio `docker-compose.yml` dentro de su carpeta, permitiendo arrancarla sin depender de la otra.

#### Frontend Vue (independiente)

```bash
cd frontend
cp .env.example .env        # Crear configuración
docker compose up --build    # Arrancar
# → http://localhost:5173
```

> Consulta [frontend/README.md](frontend/README.md) para más detalles.

#### Backend Laravel (independiente)

```bash
cd laravel
cp .env.example .env                                   # Crear configuración
docker compose up --build                              # Arrancar (Laravel + MySQL + Nginx + phpMyAdmin)
docker compose exec laravel-app php artisan key:generate   # Generar clave
docker compose exec laravel-app php artisan migrate --seed # Migraciones + datos de prueba
# → API:        http://localhost:8000
# → phpMyAdmin: http://localhost:8081
```

> Consulta [laravel/README.md](laravel/README.md) para más detalles.

### ⚠️ Importante

Antes de arrancar una opción, asegúrate de detener la otra para evitar conflictos de puertos:

```bash
docker compose down
```

## 🚀 Despliegue en Producción (CI/CD)

El proyecto está configurado para desplegarse automáticamente en una instancia AWS EC2 utilizando **GitHub Actions**.

### 1. Configuración del Servidor (EC2)

1. Conéctate a tu instancia: `ssh -i clau.pem ubuntu@IP`
2. Clona el repositorio: `git clone <URL_REPO> PI && cd PI`
3. Crea el archivo `.env` de producción con las credenciales reales: `cp .env.example .env && nano .env`
4. Ejecuta el script de configuración automática (Nginx + SSL):

```bash
sudo ./deploy/nginx/setup_prod.sh
```

Este script configurará Nginx como proxy inverso y generará certificados SSL con Let's Encrypt para `proyecto03.ddaw.es` y `api.proyecto03.ddaw.es`.

### Nota importante sobre el despliegue

Este proyecto utiliza un monorepo con pipelines y despliegues independientes para frontend y backend. Esto significa que cada aplicación se despliega de forma autónoma, lo que permite una mayor flexibilidad y escalabilidad.

### 2. Secretos de GitHub Actions

Para que el CI/CD funcione, hay que configurar los siguientes "Repository Secrets" en GitHub:

| Secreto | Descripción | Ejemplo |
|--------|-----------|---------|
| `EC2_HOST` | IP Elástica o DNS de la EC2 | `3.123.45.67` |
| `EC2_USER` | Usuario SSH | `ubuntu` |
| `EC2_SSH_KEY` | Contenido del archivo .pem | `-----BEGIN RSA PRIVATE KEY-----...` |
| `VITE_API_URL` | URL pública del backend | `http://18.206.113.196:8000` (o `https://api.proyecto03.ddaw.es` si el DNS ya está delegado) |
| `DB_PASSWORD` | Contraseña de la BD MySQL | `contrasenya_segura` |
| `VITE_N8N_WEBHOOK_URL` | Webhook N8N (Opcional) | `https://n8n...` |

### 3. Funcionamiento del Despliegue

- **Frontend**: Al hacer push a `main` (carpeta `frontend/`), se conecta por SSH, hace pull y reconstruye el contenedor `pi_prod_frontend`.
- **Backend**: Al hacer push a `main` (carpeta `laravel/`), ejecuta tests PHPUnit. Si pasan, conecta por SSH, hace pull, reconstruye `pi_prod_laravel_app` y ejecuta migraciones.

Aunque el código vive en un único repositorio, el despliegue está desacoplado por aplicación: cada workflow se dispara solo por cambios en su carpeta (`frontend/` o `laravel/`), permitiendo desplegar una sin afectar a la otra.

## 📁 Estructura del Proyecto

```
PI/
├── frontend/              # 🖥️  Aplicación Vue 3 (SPA)
│   ├── src/               #     Código fuente Vue
│   ├── Dockerfile         #     Imagen Docker (multi-stage)
│   ├── docker-compose.yml #     Docker independiente
│   └── README.md          #     Documentación frontend
│
├── laravel/               # ⚙️  API REST Laravel
│   ├── app/               #     Código fuente Laravel
│   ├── database/          #     Migraciones y seeders
│   ├── docker/            #     Configuración Nginx
│   ├── Dockerfile         #     Imagen Docker (PHP-FPM)
│   ├── docker-compose.yml #     Docker independiente
│   └── README.md          #     Documentación backend
│
├── docker-compose.yml     # 🐳  Docker Compose global (todo junto)
├── docs/                  # 📖  Documentación del proyecto
└── README.md              # 📄  Este archivo
```

---

## Documentación y recursos

### Documentos Markdown

**Documentación Global**
- [📸 Capturas y Evidencias Visuales del Proyecto](docs/evidencias_proyecto.md)
- [🌐 Visión Global del Sistema](docs/global_system.md)
- [👥 Guía de Contribución](docs/guia_contribucio.md)
- [📋 Manual de Despliegue](docs/manual_desplegament.md)
- [🌐 DNS (Route 53) del proyecto](docs/dns_route53.md)
- [📐 Arquitectura AWS Escalable](docs/arquitectura_aws.md)

**Documentación Específica**
- [Frontend: Arquitectura](frontend/docs/arquitectura.md) | [Entornos](frontend/docs/entorns.md) | [CI/CD](frontend/docs/ci_cd.md)
- [Backend: Arquitectura](laravel/docs/arquitectura.md) | [Entornos](laravel/docs/entorns.md) | [CI/CD](laravel/docs/ci_cd.md)

**Gestión**
- [Asignación de roles y responsabilidades](docs/rols.md)
- [Plan de riesgos y prevención](docs/RISKS.md)
- [Riesgos individuales](docs/riscos_individuals.md)


### Cronograma

- [Cronograma inicial Gantt (Sprint 1)](docs/gantt-SA1.pdf)

## Sprints

### Sprint 1: Entorno, escaparate y contacto
- [Informe Sprint 1](docs/sprint1.md) *(resume actividades realizadas, resultados y entregas de la primera iteración)*

### Sprint 2: Migración a PHP + JSON Server
- [Informe Sprint 2](docs/sprint2.md) *(versión backend legacy con PHP nativo)*
- [Documento de Despliegue C4 (PDF)](docs/Entrega%20Projecte%20Intermodular%20Part%20I%20Desplegament%20d'aplicacions%20WEB.pdf)

### Sprint 3: Migración a Laravel v2
- [Informe Sprint 3](docs/sprint3.md) *(backend moderno con Laravel, Breeze y MySQL)*

### Sprint 4: Cliente SPA con Vue y control de roles
- [Informe Sprint 4](docs/sprint4.md) *(interfaz moderna SPA, autenticación API y roles)*

### Sprint 5 y 6: Integraciones externas, Swagger, Docker, Despliegue final, calidad y entrega del producto
- [Informe Sprint 5 y 6](docs/sprint5_6.md) *(OAuth2, OpenAPI, NUV, SOST, DIW, etc.)*

---

## ♻️ Sostenibilidad y Criterios ASG

**🌱 Compromiso con el planeta** - Este proyecto incorpora criterios de sostenibilidad basados en los pilares ASG (Ambiental, Social, Gobernanza).

### Mejoras Implementadas

#### 🌍 Pilar Ambiental
- ✅ **Optimización de imágenes**: Formato WebP con lazy loading
- ✅ **Reducción de peso web**: >40% de reducción en transferencia de datos
- ✅ **Economía circular**: Catálogo de productos reacondicionados
- ✅ **Etiquetas ecológicas**: AlberoPerez Score, Huella de carbono, Proveedores locales
- ✅ **Eficiencia**: Code splitting, minificación, tree-shaking

#### 👥 Pilar Social
- ✅ **Accesibilidad WCAG AA**: Lighthouse Accessibility Score ≥ 95
- ✅ **Navegación por teclado**: Experiencia completa sin ratón
- ✅ **Contraste de colores**: Ratios validados para legibilidad
- ✅ **Información clara**: Transparencia en etiquetas y políticas
- ✅ **Inclusión**: Experiencia UX sin barreras

#### ⚖️ Pilar Gobernanza
- ✅ **Código documentado**: PHPDoc y JSDoc completo
- ✅ **Trazabilidad**: Criterios de AlberoPerezTech (o eco) verificables
- ✅ **Políticas públicas**: Documentación accesible en MkDocs
- ✅ **Calidad de código**: Principios SOLID, DRY, KISS
- ✅ **Métricas públicas**: Estadísticas de sostenibilidad en tiempo real

### Documentación Completa

📖 Consulta nuestra [Política de Sostenibilidad](docs/sostenibilidad.md) completa para más detalles.

🌐 También disponible en la web: [/sostenibilidad](/sostenibilidad)

### Métricas de Sostenibilidad

| Métrica | Valor Actual | Objetivo |
|---------|--------------|----------|
| Performance Score | 90+ | 95+ |
| Accessibility Score | 95+ | 100 |
| Reducción peso web | 40%+ | 60%+ |
| % Catálogo ecológico | 20%+ | 50%+ |
