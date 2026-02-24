# 🛍️ EcoTech Frontend — Vue 3 + Vite

Frontend SPA de la botiga en línia EcoTech, desenvolupat amb **Vue 3**, **Vite**, **Pinia** i **Vue Router**.

## 📦 Stack Tecnològic

| Tecnologia | Versió | Funció |
|-----------|--------|--------|
| Vue.js | 3.x | Framework SPA |
| Vite | 7.x | Bundler i dev server |
| Pinia | 3.x | Gestió d'estat |
| Vue Router | 5.x | Navegació SPA |
| Axios | 1.x | Client HTTP |
| VeeValidate + Yup | 4.x / 1.x | Validació de formularis |
| SweetAlert2 | 11.x | Alertes i modals |

## 🐳 Desenvolupament amb Docker

### Requisits previs

- [Docker](https://docs.docker.com/get-docker/) i [Docker Compose](https://docs.docker.com/compose/install/) instal·lats.

### Instruccions

```bash
# 1. Clonar el repositori (si encara no ho has fet)
git clone <url-del-repo>
cd frontend

# 2. Crear el fitxer de variables d'entorn
cp .env.example .env

# 3. (Opcional) Editar .env per ajustar la URL del backend
nano .env

# 4. Arrancar el contenidor
docker compose up --build

# 5. Accedir a l'aplicació
# Obrir http://localhost:5173 al navegador
```

### Aturar el servei

```bash
docker compose down
```

## 💻 Desenvolupament sense Docker (local)

### Requisits previs

- [Node.js](https://nodejs.org/) >= 20
- npm >= 10

### Instruccions

```bash
# 1. Instal·lar dependències
npm install

# 2. Arrancar el servidor de desenvolupament
npm run dev

# 3. Accedir a http://localhost:5173
```

### Build de producció

```bash
npm run build
npm run preview   # Per a previsualitzar el build
```

## ⚙️ Variables d'Entorn

| Variable | Descripció | Valor per defecte |
|----------|-----------|-------------------|
| `VITE_API_URL` | URL base de l'API Laravel | `http://localhost:8000` |
| `VITE_N8N_WEBHOOK_URL` | URL del webhook del chatbot N8N | — |

## 📚 Documentació Tècnica

El frontend disposa de documentació detallada a la carpeta `docs/`:

- [🏗️ Arquitectura i Stack](docs/arquitectura.md)
- [🐳 Entorns: Desenvolupament vs Producció](docs/entorns.md)
- [🔄 CI/CD i Desplegament](docs/ci_cd.md)

## 📁 Estructura del projecte

```
frontend/
├── src/
│   ├── assets/          # Imatges i recursos estàtics
│   ├── components/      # Components reutilitzables
│   ├── router/          # Configuració de rutes
│   ├── services/        # Serveis HTTP (Axios)
│   ├── stores/          # Stores Pinia
│   └── views/           # Pàgines/Vistes
├── public/              # Fitxers públics
├── Dockerfile           # Imatge Docker (multi-stage)
├── docker-compose.yml   # Composició per a desenvolupament
├── nginx.conf           # Configuració Nginx per al contenidor
├── vite.config.js       # Configuració de Vite
└── package.json         # Dependències del projecte
```
 
