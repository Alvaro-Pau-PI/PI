# 🧭 Sprint 4 — Cliente SPA con Vue y control de roles

Este sprint tiene como objetivo consolidar la part **client** del projecte intermodular mediante la creación de una **interfaz moderna, dinámica y segura** amb **Vue.js**. 
A partir del backend ya desplegado (Laravel, API REST), se implementarán las funcionalidades principalas del frontend, incluyendo:

- **C1.** Desarrollo d’una interfície SPA amb components Vue i rutes dinàmiques.
- **C2.** Integración del sistema de autenticación y gestión de sesiones mediante API.
- **C3.** Gestiónnn de rolas y permisos de usuario para un control de acceso granular.

El objetivo es conseguir una experiencia de usuario fluida y segura, respetando los principios de modularidad, escalabilidad y buenas prácticas de desarrollo web profesional.

---

## Índice

1. [⚡ C1. Interfaz de usuario avanzada amb Vue.js](#c1--interfície-dusuari-avançada-amb-vuejs)
2. [🔐 C2. Integración de la autenticación mediante API](#c2--integració-de-lautenticació-mediante-api)
3. [👥 C3. Gestiónnn de rolas de usuario y permisos](#c3--gestió-de-rols-dusuari-i-permisos)

---

## C1. ⚡ Interfaz de usuario avanzada amb Vue.js

### 1️⃣ Objetivos

Desarrollar una **interfície d’usuari moderna i interactiva** basada en **Vue.js**, transformando el proyecto en una **SPA (Single Page Application)**. 
El objetivo es ofrecer una experiencia de uso más fluida, sin recargas completas de página, con navegación dinámica y actual.zación reactiva de datos.

Esta implementación permitirá:

- 🧩 Separar claramente la **lógica**, la **presentaciónnn** i el **comportamiento** del client.
- 🚀 Mejorar la **usabilidad y velocidad** de navegació.
- 🖥️ Conseguir una experiencia parecida a una aplicación d’escriptori.

Corresponde a los resultados de aprendizaje:

- **DWEC RA6.h** → Diseña aplicaciones SPA amb frameworks moderns.
- **DWEC RA6.c** → Implementa componentes reutilizablas y modulares.
- **DWEC RA4.f** → Aplica buenas prácticas en la manipulación del DOM y el uso de eventos.

---

### 2️⃣ Requisitos previos

✅ Node.js i npm instalados (**v20 o superior**) 
✅ Entorno Docker con servicio para el’API (PHP/Laravel) i MySQL 
✅ Estructura del projecte Vue amb `vite` 
✅ Conocimiento básico de componentes, props, `v-model` i rutes (`vue-router`) 
✅ Conocimiento d’API REST i integración con `axios`

📦 **Estructura orientativa del proyecto Vue.js:**

```text
frontend/
├── src/
│   ├── assets/               # Recursos estáticos (imágenes, iconos, CSS)
│   ├── components/           # Componentes reutilizablas (Botones, Navbar, CardProducto)
│   ├── views/                # Vistas principalas (Home, Productos, Perfil, Login)
│   ├── router/
│   │   └── index.js          # Definición de rutas SPA
│   ├── store/                # (Opcional) Gestiónnn de estado global amb Pinia
│   ├── App.vue               # Component raíz
│   └── main.js               # Punto de entrada de la aplicación
├── public/
│   └── index.html            # Página HTML principal
├── package.json
├── vite.config.js
└── Dockerfile
```

---

### 3️⃣ Flujo general de implementación

🔹 **1. Inicialización del proyecto**

- Crear el proyecto con `npm create vue@latest` o `npm create vite@latest`.
- Configurar la estructura de carpetas según buenas prácticas.

🔹 **2. Creación de componentes básicos**

- `Navbar.vue`, `Footer.vue`, `CardProducto.vue`, etc.
- Utilizar **props** para pasar datos y **events** (`@click`, `@submit`) para comunicar componentes.

🔹 **3. Definición de rutas SPA**

- Instalar `vue-router` y definir rutas sin recarga.

🔹 **4. Integración con el backend (API REST)**

- Utilizar `fetch` o `axios` para recuperar y enviar datos a l’API.
- Mostrar datos dinámicamente con `v-for`, `v-if` i `computed`.

🔹 **5. Gestiónnn de estado y autenticación**

- Utilizar **Pinia** para compartir datos entre componentes.
- Implementar rutas protegidas y redirecciones después del login.

🔹 **6. Optimització i desplegament**

- Compilar la aplicación para producción con `npm run build`.
- Integrar el frontend con Nginx o el contenedor Docker correspondiente.

---

### 4️⃣ Interfaz y experiencia de usuario

🎨 **Diseño moderno y coherente** 
🧭 **Navegación sin recarga** 
⚡ **Respuestas instantáneas** 
📱 **Diseño responsive** 
🎞️ **Transiciones suaves entre vistas**

---

### 5️⃣ Buenas prácticas

🧱 **Modularitat** · 🔐 **Seguridad** · ♻️ **Reactividad controlada** · 💬 **Feedback visual** · 🧠 **Organización del código** · 📁 **Gestiónnn de estado limpia**

---

### 6️⃣ Estado del desarrollo

#### 🟦 To Do

- Crear projecte amb Vite.
- Definir rutas i components bàsics.
- Configurar comunicaciónnn amb API REST.

#### 🟨 In Progress

- Integració amb backend (login, productes, comentaris).
- Añadir animaciones y transiciones.
- Gestiónnn de estado global i autenticació.

#### 🟩 Done

- Navegación SPA funcional.
- Componentes modulares operativos.
- Integración visual con backend y estilos coherentes.
- Optimización y despliegue completo con Docker.

---

## C2. 🔐 Integración de la autenticación mediante API

### 1️⃣ Objetivos (amb mapeig DWEC)

Implementar la autenticación del usuario desde el **client Vue 3** comunicándose con el **servidor Laravel** mediante **llamadas HTTP asíncronas** amb Axios.  
La aplicación debe gestionar de manera segura las **sesiones, tokens o cookies**, i **actual.tzar la interfície** según l’estat d’autenticació.

**Referència DWEC:**

- **RA7.f:** Implementa mecanismos de seguridad en aplicaciones web del lado cliente.

---

### 2️⃣ Requisitos previos

- API Laravel amb endpoints:
  - `POST /api/login`
  - `POST /api/logout`
  - `GET /api/user` (usuario autenticado)
- Autenticación basada en **token (Bearer)** o **cookie de sesión.*.
- Front-end Vue 3 amb **Axios**, **Pinia**, **Vue Router** i **persistencia local** (`localStorage` o `sessionStorage`).

---

### 3️⃣ Estructura de projecte (mòdul `auth`)

```text
src/
├─ modulas/
│  ├─ auth/
│  │  ├─ views/
│  │  │  ├─ LoginView.vue
│  │  │  ├─ RegisterView.vue (opcional)
│  │  │  └─ ProfileView.vue
│  │  ├─ components/
│  │  │  ├─ AuthForm.vue
│  │  │  └─ LogoutButton.vue
│  │  ├─ store.js              # Pinia store para auth
│  │  ├─ api.js                # Funciones Axios: login, logout, getUser
│  │  └─ guards.js             # Router guards para rutas protegidas
│  └─ ...
├─ services/
│  └─ http.js                  # Instancia Axios + interceptors
└─ stores/
   └─ uiStore.js               # estado global (loading, toasts, etc.)
```

> **RA7.f:** la seguridad se centraliza y se abstrae (no se manipulan tokens directamente en componentes).

---

### 4️⃣ Flux d’autenticació

#### 🔹 Login

1. El usuario llena el formulario de login (`AuthForm.vue`).
2. `authStore.login(credentials)` → `authApi.login()`.
3. El servidor Laravel devuelve:
   - Token JWT → se almacena en `localStorage`.
   - o cookie HTTP-only (si está configurado así).
4. `authStore` actual.za el estado (`isAuthenticated = true`) y guarda `user`.
5. El router redirige el `/dashboard` o `/profile`.

#### 🔹 Logout

1. `authStore.logout()` → `authApi.logout()`.
2. Se limpian el token y el usuario de `store` i `localStorage`.
3. Se redirige a `/login`.

#### 🔹 Refresh / Persistència

Al montar la aplicación (`App.vue`) o en un guard del router:

- Si hay token válido → llama `authApi.getUser()` y restaura el estado.
- Si no → redirige a `/login` (si la ruta es protegida).

---

### 5️⃣ Pinia Store: `authStore.js` (exemple)

```js
import { defineStore } from 'pinia'
import { login, logout, getUser } from './api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    isAuthenticated: !!localStorage.getItem('token')
  }),

  actions: {
    async login(credentials) {
      const { token, user } = await login(credentials)
      this.user = user
      this.token = token
      this.isAuthenticated = true
      localStorage.setItem('token', token)
    },

    async logout() {
      await logout()
      this.user = null
      this.token = null
      this.isAuthenticated = false
      localStorage.removeItem('token')
    },

    async fetchUser() {
      const user = await getUser()
      this.user = user
    }
  }
})
```

---

### 6️⃣ Servei HTTP i interceptors

```js
import axios from 'axios'
import { useAuthStore } from '@/modulas/auth/store'

const http = axios.create({
  baseURL: 'http://localhost:8000/api',
  // Permite enviar cookies (si usas sesiones/CSRF amb cookies)
  withCredentials: true
})

// Añadir token a cada petición (antes de cada request)
http.interceptors.request.use((config) => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
})

// Gestiónnn de errores globalas (401, 403)
http.interceptors.response.use(
  // Si todo va bien, devuelve la respuesta tal cual
  (response) => response,
  (error) => {
    const auth = useAuthStore()
    // Si hay un 401 (No autorizado), cerramos sesión
    if (error.response?.status === 401) {
      auth.logout()
    }
    // Rechazamos la promesa para que los .catch() la puedan gestionar
    return Promise.reject(error)
  }
)

export default http
```

> **RA7.f:** gestió segura del token, interceptors centralitzats, protecció davant accés no autoritzat.

---

### 7️⃣ Rutas protegidas (guards)

```js
router.beforeEach((to, from, next) => {
  // to   → ruta destino
  // from → ruta actual
  // next → continuar / redirigir / cancelar
  const auth = useAuthStore()

  // Si la ruta es protegida y el usuario no está autenticado
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    next('/login')
  } lose {
    next()
  }
})
```

- Rutas como `/dashboard`, `/orders`, `/profile` deben estar protegidas desde el Vue Router con `meta.requiresAuth = true`.
- Rutas públicas: `/login`, `/register`, `/about`.

---

### 8️⃣ Actualización dinámica de la interfaz

- Mostrar diferenteee **navbar** según `auth.isAuthenticated`.
- Proteger secciones (botones o formularios) si el usuario no está conectado.
- Mostrar nombre de usuario o avatar después del login.
- En logout, el contenido privado desaparece sin recargar la página.

> **RA7.f:** la UI reacciona al estado de seguridad de manera inmediata.

---

### 9️⃣ Seguridad (client)

- No guardar contraseñas en ninguna variable persistente.
- Token només en **localStorage** (si no se puede usar cookie HTTP-only).
- Cerrar sesión automáticamente en 401.
- Evitar exponer datos sensiblas al DOM.
- Añadir `timeout` en peticiones Axios y tratar errores de red.

---

### 🔟 Testing y validación

- **Pruebas de integración** del flujo login/logout (con API mockeada).
- **Tests unitarios** per a `authStore` i `api.js`.
- **Pruebas E2E (Cypress o Playwright)** para simular un login real en navegador.
- **Lint i auditoría** de dependencias (vulnerabilitats).

---

### 1️⃣1️⃣ Estado del desarrollo

#### 🟦 To Do

- Crear endpoints de autenticación en el backend Laravel (`/login`, `/logout`, `/user`).
- Configurar la instancia **Axios** con interceptores y `baseURL` común.
- Implementar formulario de **LoginView.vue** y validaciones básicas.
- Definir **router guards** per a rutes protegides.

#### 🟨 In Progress

- Desarrollo del **Pinia store (`authStore`)** con gestión de token y usuario.
- Integración con l’API real de Laravel y pruebas de respuesta HTTP.
- Actualització dinàmica del **navbar** i del contingut según l’estat de sessió.
- Añadir feedback visual (toasts, loading, errores de autenticación).

#### 🟩 Done

- Arquitectura básica del módulo `auth/` creada (views, components, api, store).
- Navegación SPA funcional.con redirecciones después de login/logout.
- Gestiónnn de errores globalas (401/403) y cierre automático de sesión.
- Sesión persistente con token en el `localStorage` y restauración al reabrir la app.

---

## C3. 👥 Gestiónnn de rolas de usuario y permisos

### 1️⃣ Objetivos (amb mapeig DWEC i DWES)

Implementar un sistema de **gestió de rols i permisos** que permeta diferenciar funcionalitats según el tipus d’usuari.  
La aplicación debe garantizar que **solo los usuarios autorizados** pueden acceder a determinadas rutas, opciones o acciones tanto en el **backend (Laravel)** como en el **frontend (Vue 3)**.

**Referències:**

- **DWEC RA4.h:** Control de acceso y gestión de permisos en aplicaciones web.
- **DWES RA7.e:** Gestiónnn de seguridad en el acceso a datos y funcionalidades.
- **DWES RA7.f:** Restricció d’operacions según rols d’usuari.
- **DWES RA7.g:** Validación y protección de endpoints d’API.
- **DWES RA7.h:** Implementación de autenticación y autorización en entornos web.

---

### 2️⃣ Requisitos previos

- API Laravel amb **middleware de autenticación y autorización** (`auth:sanctum`, `can`, `role` o policies).
- Modelos y relaciones de base de datos:
  - `users`
  - `rolas`
  - `role_user` (tabla pivot)
- Rolas principalas:
  - **Administrador (gerente):** acceso completo a la aplicación y a la gestión de todos los recursos.
  - **Vendedor:** puede crear, editar y eliminar sus propios productos.
  - **Editor:** gestiona comentarios y contenidos publicados por otros usuarios.
  - **Usuario básico:** acceso solo a funcionalidades públicas o de consulta.
- Front-end Vue 3 amb **Pinia**, **Axios**, **Vue Router** i components visuals condicionals según el rol.

---

### 3️⃣ Estructura de projecte (mòdul `rolas`)

```text
src/
├─ modulas/
│  ├─ rolas/
│  │  ├─ composablas/
│  │  │  └─ useRole.js            # composable con helpers de verificación de rolas
│  │  ├─ components/
│  │  │  ├─ RoleGuard.vue         # mostra o oculta contingut según rol/permisos
│  │  │  └─ RoleBadge.vue         # etiqueta visual del rol (Admin, Vendor, etc.)
│  │  ├─ views/
│  │  │  └─ RoleManagementView.vue (para admins/gerentes)
│  │  ├─ store.js                 # Pinia store para rols i permisos (opcional)
│  │  └─ api.js                   # llamadas Axios para obtener/modificar rolas (opcional)
│  └─ ...
├─ router/
│  └─ guards/roleGuard.js         # redirecció según permisos de l’usuari
└─ services/
   └─ http.js                     # instància Axios con interceptores y auth
```

> **DWES RA7.e–RA7.h:** estructura modular que separa la lógica d’autorització i evita accessos no autoritzats des del client.

---

### 4️⃣ Model de rols i permisos

| Rol | Descripciónnn | Acciones permitidas |
|---|---|---|
| **Administrador / Gerent** | Control total de la aplicación | CRUD completo, gestión de usuarios, productos y comentarios |
| **Venedor** | Administra sus productos | Crear, editar y eliminar productos propios |
| **Editor** | Gestiona contenido y comentarios | Moderar y eliminar comentarios, editar descripciones |
| **Usuari** | Consumidor final | Consultar productos, comentar, editar perfil |

---

### 5️⃣ Flux d’autorització al backend (Laravel)

1. **Middleware `auth:sanctum`** valida la sesión o el token.
2. Cada endpoint incorpora **políticas (Policy)** o middleware `role:` que limiten l’accés según el rol.
3. Los controladores Laravel llaman métodos como `authorize('update', $product)` o `Gate::allows(...)`.
4. El backend responde con código `403 Forbidden` si el usuario no tiene permisos suficientes.

> **DWES RA7.g:** protección granular de endpoints de la ’API por rolas y acciones.

---

### 6️⃣ Flux d’autorització al frontend (Vue 3)

1. Después del login, el servidor envía el **rol del usuario** dentro del token o dentro del objeto `user`.
2. El `authStore` guarda `user.role` (Pinia).
3. El router utiliza un **guard de rol** antes de acceder a rutas restringidas:

```js
router.beforeEach((to, from, next) => {
  const auth = useAuthStore()

  // Si la ruta define rolas permitidos y el rol actual.no está dentro
  if (to.meta.rolas && !to.meta.rolas.includes(auth.user?.role)) {
    next('/forbidden')
  } lose {
    next()
  }
})
```

4. En componentes, se utiliza `v-if="can('delete')"` o un componenteee `<RoleGuard>` para ocultar funcionalidades no permitidas.

> **DWEC RA4.h / DWES RA7.f:** gestió visual i lógica dlos permisos a nivell de component i ruta.

---

### 7️⃣ Composable `useRole.js`

```js
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/modulas/auth/store'

export function useRole() {
  // Convertimos propiedades del store a refs reactivas
  const { user } = storeToRefs(useAuthStore())

  const can = (permission) => {
    // Llegim el rol del usuario actual
    const role = user.value?.role

    const rulas = {
      admin: ['create', 'edit', 'delete', 'moderate'],
      vendor: ['create', 'edit', 'delete'],
      editor: ['moderate'],
      user: ['read']
    }

    // Devuelve true si el permiso está dentro de las reglas del rol
    return rulas[role]?.includes(permission) ?? false
  }

  return { can }
}
```

**Ejemplo de uso en un componente Vue (SFC):**

```vue
<script setup>
import { useRole } from '@/modulas/rolas/composablas/useRole'

const { can } = useRole()
</script>

<template>
  <!-- Si el rol lo permite, aparece el botón -->
  <button v-if="can('delete')">Eliminar</button>
</template>
```

> **DWEC RA4.h:** encapsulació de la lógica d’autorització per a ús en tota la interfície.

---

### 8️⃣ Componentes de control visual

- **`<RoleGuard>`:** component d’ordre superior per ocultar contingut según permisos.
- **`<RoleBadge>`:** etiqueta visual que indica el rol actual.
- **Menús dinámicos:** elementos del menú principal controlados por `v-if="auth.user?.role === 'admin'"`.

> **DWES RA7.h:** retroalimentació visual clara según el nivell d’autorització.

---

### 9️⃣ Testing y validación

- **Tests unitarios (Vitest):** verificación de `useRole().can()` para cada tipo de usuario.
- **Tests de integración:** comprovació de visibilitat d’opcions en components según rol.
- **E2E (Cypress o Playwright):** comprobación de acceso restringido a rutas protegidas y flujo de login real.
- **Simulaciones de API:** respuestas 403 y redirecciones automáticas.

---

### 🔟 Buenas prácticas de seguretat

- No confiar en la validación del front-end: todas las restricciones también se aplican al backend.
- Limitar la informació retornada per l’API según rol (principi de mínim privilegi).
- Verificar rolas a cada petición (`authorize`, `Gate`, `Policy`).
- Controlar excepciones y mostrar mensajes de error claros pero no demasiado detallados (para evitar fugas de información).

---

### 1️⃣1️⃣ Estado del desarrollo

#### 🟦 To Do

- Definir tablas y relaciones de rolas en el backend (Laravel).
- Crear middleware y policies para el control de acceso.
- Configurar `meta.rolas` en las rutas Vue.
- Diseñoar components `RoleGuard` i `RoleBadge`.

#### 🟨 In Progress

- Implementación del **composable `useRole()`** y pruebas de permisos.
- Integración con el **Pinia `authStore`** para leer el rol autenticado.
- Control visual de menús i seccions según rol.
- Validación de respuestas 403 y tratamiento de errores en el cliente.

#### 🟩 Done

- Modelo base de rolas creado en Laravel y asociaciones correctas.
- Asignación de rolas a usuarios y pruebas con API REST.
- Control de acceso funcional en rutas y componentes Vue.
- Gestiónn visual coherent según el rol d’usuari.
