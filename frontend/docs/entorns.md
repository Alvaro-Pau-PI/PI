# 🐳 Entornos de Desarrollo y Producción (Frontend)

El ciclo de vida del frontend pasa por dos entornos claramente diferenciados: Desarrollo (local) y Producción (Cloud). Documentamos las diferencias y cómo operar en cada uno.

## 1. Entorno de Desarrollo (Local)

El objetivo es ofrecer una experiencia de desarrollo rápida (DX) con hot-reloading y herramientas de debugging.

### ✨ Características
- **Servidor**: Vite Dev Server (puerto 5173).
- **Modo**: `development` (logs detallados, Vue DevTools habilitado).
- **API**: Conecta a `http://localhost:8000`.
- **HMR**: Hot Module Replacement activo (los cambios se reflejan instantáneamente).

### 🚀 Cómo arrancar (Opción A: Docker)
Esta es la opción recomendada para garantizar consistencia con el equipo.

```bash
cd frontend
cp .env.example .env
docker compose up --build
```
> Acceso: `http://localhost:5173`

### 💻 Cómo arrancar (Opción B: Node.js Nativo)
Más rápido si tienes Node configurado localmente.

```bash
cd frontend
npm install
npm run dev
```

### ⚙️ Variables de Entorno (.env)
```ini
VITE_API_URL=http://localhost:8000
VITE_APP_ENV=local
```

---

## 2. Entorno de Producción (Cloud - AWS)

El objetivo es el rendimiento, la seguridad y la estabilidad.

### ✨ Características
- **Servidor**: Nginx (sirviendo archivos estáticos compilados).
- **Modo**: `production` (código minificado, logs deshabilitados, tree-shaking).
- **API**: Conecta a `http://18.206.113.196:8000` (o `https://api.proyecto03.ddaw.es` si el DNS ya está delegado).
- **Optimización**: Assets comprimidos (Gzip/Brotli) y cache-control headers.

### 🏗️ Proceso de Build
El código Vue se transpila a JavaScript/CSS estático optimizado:

```bash
npm run build
# Genera la carpeta /dist con:
# - index.html
# - assets/ (js, css, img con hash para cache-busting)
```

### 🚀 Despliegue
Se realiza automáticamente mediante GitHub Actions (ver `ci_cd.md`).

El `Dockerfile` de producción utiliza un **Multi-stage build**:
1. **Stage Build**: Node.js compila el proyecto (`npm run build`).
2. **Stage Production**: Nginx Alpine sirve solamente la carpeta `dist/`.

### ⚙️ Variables de Entorno (Secretos GitHub)
```ini
VITE_API_URL=http://18.206.113.196:8000
VITE_APP_ENV=production
```

**Una vez delegado el DNS**:
```ini
VITE_API_URL=https://api.proyecto03.ddaw.es
```

---

## 🔄 Diferencias Clave

| Característica | Desarrollo | Producción |
|---------------|-----------------|-----------|
| **Servidor web** | Vite (ESBuild) | Nginx |
| **API URL** | localhost:8000 | 18.206.113.196:8000 (o api.proyecto03.ddaw.es si DNS delegado) |
| **Debug** | ✅ Activado | ❌ Desactivado |
| **Sourcemaps** | ✅ Sí | ❌ No (por seguridad) |
| **Tamaño Assets** | Sin minificar | Minificado y ofuscado |
