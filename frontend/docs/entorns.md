# 🐳 Entorns de Desenvolupament i Producció (Frontend)

El cicle de vida del frontend passa per dos entorns clarament diferenciats: Desenvolupament (local) i Producció (Cloud). Documentem les diferències i com operar en cadascun.

## 1. Entorn de Desenvolupament (Local)

L'objectiu és oferir una experiència de desenvolupament ràpida (DX) amb hot-reloading i eines de debugging.

### ✨ Característiques
- **Servidor**: Vite Dev Server (port 5173).
- **Mode**: `development` (logs detallats, Vue DevTools habilitat).
- **API**: Connecta a `http://localhost:8000`.
- **HMR**: Hot Module Replacement actiu (els canvis es reflecteixen instantàniament).

### 🚀 Com arrancar (Opció A: Docker)
Aquesta és l'opció recomanada per garantir consistència amb l'equip.

```bash
cd frontend
cp .env.example .env
docker compose up --build
```
> Accés: `http://localhost:5173`

### 💻 Com arrancar (Opció B: Node.js Natiu)
Més ràpid si tens Node configurat localment.

```bash
cd frontend
npm install
npm run dev
```

### ⚙️ Variables d'Entorn (.env)
```ini
VITE_API_URL=http://localhost:8000
VITE_APP_ENV=local
```

---

## 2. Entorn de Producció (Cloud - AWS)

L'objectiu és el rendiment, la seguretat i l'estabilitat.

### ✨ Característiques
- **Servidor**: Nginx (servint fitxers estàtics compilats).
- **Mode**: `production` (codi minificat, logs deshabilitats, tree-shaking).
- **API**: Connecta a `https://api.AlberoPerezTech.ddaw.es`.
- **Optimització**: Assets comprimits (Gzip/Brotli) i cache-control headers.

### 🏗️ Procés de Build
El codi Vue es transpila a JavaScript/CSS estàtic optimitzat:

```bash
npm run build
# Genera la carpeta /dist amb:
# - index.html
# - assets/ (js, css, img amb hash per a cache-busting)
```

### 🚀 Desplegament
Es realitza automàticament mitjançant GitHub Actions (veure `ci_cd.md`).

El `Dockerfile` de producció utilitza un **Multi-stage build**:
1. **Stage Build**: Node.js compila el projecte (`npm run build`).
2. **Stage Production**: Nginx Alpine serveix solament la carpeta `dist/`.

### ⚙️ Variables d'Entorn (Secrets GitHub)
```ini
VITE_API_URL=https://api.AlberoPerezTech.ddaw.es
VITE_APP_ENV=production
```

---

## 🔄 Diferències Clau

| Característica | Desenvolupament | Producció |
|---------------|-----------------|-----------|
| **Servidor web** | Vite (ESBuild) | Nginx |
| **API URL** | localhost:8000 | api.AlberoPerezTech.ddaw.es |
| **Debug** | ✅ Activat | ❌ Desactivat |
| **Sourcemaps** | ✅ Sí | ❌ No (per seguretat) |
| **Mida Assets** | Sense minificar | Minificat i ofuscat |
