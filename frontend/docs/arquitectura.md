# 🏗️ Arquitectura del Frontend (Vue 3)

## 🎯 Visión General
El frontend es una **Single Page Application (SPA)** desarrollada con Vue 3 modularizado mediante Vite. El objetivo es ofrecer una experiencia de usuario fluida, rápida y accesible, siguiendo los principios de diseño atómico y componentización.

## 🛠️ Stack Tecnológico

| Tecnología | Versión | Justificación |
|-----------|--------|--------------|
| **Vue.js** | 3.x | Reactividad eficiente con Composition API y `<script setup>`. |
| **Vite** | 5.x | Build tool de última generación, HMR instantáneo. |
| **Pinia** | 2.x | Gestión de estado centralizado, sustituto moderno de Vuex. |
| **Vue Router** | 4.x | Enrutamiento SPA para navegación sin recargas. |
| **Axios** | 1.x | Cliente HTTP para comunicar con la API Laravel. |
| **Bootstrap** | 5.x | Framework CSS para diseño responsive rápido. |
| **VeeValidate** | 4.x | Validación de formularios robusta y accesible. |

## 📂 Estructura de Directorios

```text
frontend/
├── public/              # Assets estáticos públicos (favicon, robots.txt)
├── src/
│   ├── assets/          # Imágenes, fuentes y estilos globales (SCSS)
│   ├── components/      # Componentes Vue reutilizables (Botones, Cards...)
│   │   ├── common/      # Componentes genéricos
│   │   └── layout/      # Navbar, Footer, Sidebar
│   ├── composables/     # Lógica reutilizable (Hooks)
│   ├── router/          # Configuración de rutas (index.js)
│   ├── services/        # Servicios de API (Axios instances)
│   ├── stores/          # Estado global (Pinia stores: auth, cart...)
│   ├── views/           # Páginas principales (Home, ProductDetail...)
│   ├── App.vue          # Componente raíz
│   └── main.js          # Punto de entrada de la aplicación
├── .env.*               # Variables de entorno
├── index.html           # Template HTML principal
└── vite.config.js       # Configuración de build y proxy
```

## 🧩 Patrones de Diseño

### 1. Composition API
Utilizamos `<script setup>` para una lógica más limpia y mejor inferencia de tipos. Toda la lógica de estado se mantiene separada de la presentación siempre que sea posible.

### 2. Gestión de Estado (Pinia)
El estado global se divide en módulos:
- `auth.js`: Gestión de usuarios, tokens y permisos.
- `cart.js`: Gestión del carrito de la compra (persistente en LocalStorage).
- `products.js`: Catálogo y filtros.

### 3. Servicios HTTP
No hacemos llamadas `axios` directamente en los componentes. Utilizamos una capa de servicios (`src/services/api.js`) que encapsula la configuración base, interceptores de autenticación y gestión de errores.

## 🔒 Seguridad Frontend

- **XSS Protection**: Vue escapa automáticamente el contenido en las plantillas.
- **CSRF**: Axios está configurado para enviar el token CSRF (`X-XSRF-TOKEN`) automáticamente con las cookies de Sanctum.
- **Rutas Protegidas**: El router verifica la meta-propiedad `requiresAuth` y redirige al login si no hay sesión válida.
