# 🏗️ Arquitectura del Frontend (Vue 3)

## 🎯 Visió General
El frontend és una **Single Page Application (SPA)** desenvolupada amb Vue 3 modulat mitjançant Vite. L'objectiu és oferir una experiència d'usuari fluida, ràpida i accessible, seguint els principis de disseny atòmic i componentització.

## 🛠️ Stack Tecnològic

| Tecnologia | Versió | Justificació |
|-----------|--------|--------------|
| **Vue.js** | 3.x | Reactivitat eficient amb Composition API i `<script setup>`. |
| **Vite** | 5.x | Build tool d'última generació, HMR instantani. |
| **Pinia** | 2.x | Gestió d'estat centralitzat, substitut modern de Vuex. |
| **Vue Router** | 4.x | Enrutament SPA per a navegació sense recàrregues. |
| **Axios** | 1.x | Client HTTP per a comunicar amb l'API Laravel. |
| **Bootstrap** | 5.x | Framework CSS per a disseny responsive ràpid. |
| **VeeValidate** | 4.x | Validació de formularis robusta i accessible. |

## 📂 Estructura de Directoris

```text
frontend/
├── public/              # Assets estàtics públics (favicon, robots.txt)
├── src/
│   ├── assets/          # Imatges, fonts i estils globals (SCSS)
│   ├── components/      # Components Vue reutilitzables (Botons, Cards...)
│   │   ├── common/      # Components genèrics
│   │   └── layout/      # Navbar, Footer, Sidebar
│   ├── composables/     # Lògica reutilitzable (Hooks)
│   ├── router/          # Configuració de rutes (index.js)
│   ├── services/        # Serveis d'API (Axios instances)
│   ├── stores/          # Estat global (Pinia stores: auth, cart...)
│   ├── views/           # Pàgines principals (Home, ProductDetail...)
│   ├── App.vue          # Component arrel
│   └── main.js          # Punt d'entrada de l'aplicació
├── .env.*               # Variables d'entorn
├── index.html           # Template HTML principal
└── vite.config.js       # Configuració de build i proxy
```

## 🧩 Patrons de Disseny

### 1. Composition API
Utilitzem `<script setup>` per a una lògica més neta i millor inferència de tipus. Tota la lògica d'estat es manté separada de la presentació sempre que sigui possible.

### 2. Gestió d'Estat (Pinia)
L'estat global es divideix en mòduls:
- `auth.js`: Gestió d'usuaris, tokens i permisos.
- `cart.js`: Gestió del carret de la compra (persistent en LocalStorage).
- `products.js`: Catàleg i filtres.

### 3. Serveis HTTP
No fem crides `axios` directament als components. Utilitzem una capa de serveis (`src/services/api.js`) que encapsula la configuració base, interceptors d'autenticació i gestió d'errors.

## 🔒 Seguretat Frontend

- **XSS Protection**: Vue escapa automàticament el contingut en les plantilles.
- **CSRF**: Axios està configurat per enviar el token CSRF (`X-XSRF-TOKEN`) automàticament amb les cookies de Sanctum.
- **Rutes Protegides**: El router verifica la meta-propietat `requiresAuth` i redirigeix al login si no hi ha sessió vàlida.
