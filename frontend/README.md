# 🛍️ AlberoPerezTech Frontend — Vue 3 + Vite

Frontend SPA de la tienda en línea AlberoPerezTech, desarrollado con **Vue 3**, **Vite**, **Pinia** y **Vue Router**.

## 📦 Stack Tecnológico

| Tecnología | Versión | Función |
|-----------|--------|--------|
| Vue.js | 3.x | Framework SPA |
| Vite | 7.x | Bundler y dev server |
| Pinia | 3.x | Gestión de estado |
| Vue Router | 5.x | Navegación SPA |
| Axios | 1.x | Cliente HTTP |
| VeeValidate + Yup | 4.x / 1.x | Validación de formularios |
| SweetAlert2 | 11.x | Alertas y modales |

## 🐳 Desarrollo con Docker

### Requisitos previos

- [Docker](https://docs.docker.com/get-docker/) y [Docker Compose](https://docs.docker.com/compose/install/) instalados.

### Instrucciones

```bash
# 1. Clonar el repositorio (si aún no lo has hecho)
git clone <url-del-repo>
cd frontend

# 2. Crear el archivo de variables de entorno
cp .env.example .env

# 3. (Opcional) Editar .env para ajustar la URL del backend
nano .env

# 4. Arrancar el contenedor
docker compose up --build

# 5. Acceder a la aplicación
# Abrir http://localhost:5173 en el navegador
```

### Detener el servicio

```bash
docker compose down
```

## 💻 Desarrollo sin Docker (local)

### Requisitos previos

- [Node.js](https://nodejs.org/) >= 20
- npm >= 10

### Instrucciones

```bash
# 1. Instalar dependencias
npm install

# 2. Arrancar el servidor de desarrollo
npm run dev

# 3. Acceder a http://localhost:5173
```

### Build de producción

```bash
npm run build
npm run preview   # Para previsualizar el build
```

## ⚙️ Variables de Entorno

| Variable | Descripción | Valor por defecto |
|----------|-----------|-------------------|
| `VITE_API_URL` | URL base de la API Laravel | `http://localhost:8000` |
| `VITE_N8N_WEBHOOK_URL` | URL del webhook del chatbot N8N | — |

## 📚 Documentación Técnica

El frontend dispone de documentación detallada en la carpeta `docs/`:

- [🏗️ Arquitectura y Stack](docs/arquitectura.md)
- [🐳 Entornos: Desarrollo vs Producción](docs/entorns.md)
- [🔄 CI/CD y Despliegue](docs/ci_cd.md)

## 📁 Estructura del proyecto

```
frontend/
├── src/
│   ├── assets/          # Imágenes y recursos estáticos
│   ├── components/      # Componentes reutilizables
│   ├── router/          # Configuración de rutas
│   ├── services/        # Servicios HTTP (Axios)
│   ├── stores/          # Stores Pinia
│   └── views/           # Páginas/Vistas
├── public/              # Archivos públicos
├── Dockerfile           # Imagen Docker (multi-stage)
├── docker-compose.yml   # Composición para desarrollo
├── nginx.conf           # Configuración Nginx para el contenedor
├── vite.config.js       # Configuración de Vite
└── package.json         # Dependencias del proyecto
```
