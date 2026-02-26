# Iteración 4: Cliente SPA con Vue y control de roles

En este sprint hemos construido la capa cliente del proyecto como una **Single Page Application (SPA)** con **Vue 3**, consumiendo la API REST de Laravel desarrollada en las iteraciones anteriores. Se han implementado la navegación dinámica, la autenticación mediante token y un sistema completo de roles y permisos.

## Objetivos principales

- **C1. Interfaz SPA con Vue.js**: Crear una interfaz moderna, reactiva y modular con componentes reutilizables y navegación sin recargas.
- **C2. Autenticación mediante API**: Integrar el flujo de login/logout con el backend Laravel usando Axios y gestionar el estado de sesión con Pinia.
- **C3. Gestión de roles y permisos**: Implementar un sistema de acceso granular tanto en el frontend (rutas y componentes) como en el backend (middleware y policies de Laravel).

## Actividades realizadas

### C1. Interfaz SPA con Vue.js

- Inicialización del proyecto con **Vite** en la carpeta `frontend/`, configurando la estructura de carpetas (`views/`, `components/`, `router/`, `stores/`).
- Creación de los componentes principales: `Navbar.vue`, `Footer.vue` y las tarjetas de producto.
- Definición de las rutas SPA con **Vue Router**, eliminando las recargas completas entre páginas.
- Integración con la API de Laravel mediante **Axios** para cargar el catálogo de productos dinámicamente.
- Aplicación de directivas reactivas (`v-for`, `v-if`, `computed`) para renderizar datos del backend.
- Transiciones suaves entre vistas y diseño responsivo coherente con el tema oscuro corporativo.

### C2. Autenticación mediante API

- Creación del **Pinia store** (`authStore`) que gestiona el token, el usuario y el estado de sesión.
- Implementación de `LoginView.vue` y `RegisterView.vue` conectadas a los endpoints `/api/login` y `/api/register` de Laravel.
- Configuración de una instancia **Axios centralizada** con interceptores que añaden automáticamente el token Bearer a cada petición y gestionan los errores `401` (cierre de sesión automático).
- Gestión de la **persistencia de sesión**: al reabrir la aplicación, se restaura el estado del usuario desde `localStorage`.
- Actualización dinámica del navbar y los contenidos según el estado de autenticación, sin necesidad de recargar la página.
- Protección de rutas privadas con guards de Vue Router (`requiresAuth`).

### C3. Gestión de roles y permisos

- Definición de los roles en la base de datos: **Administrador**, **Usuario básico**.
- Implementación del middleware y las **policies de Laravel** para proteger los endpoints de la API según el rol del usuario.
- En el frontend, el rol del usuario se incluye en la respuesta del login y se almacena en `authStore`.
- Creación del composable `useRole()` que encapsula la lógica de permisos y permite utilizar `can('accion')` en cualquier componente.
- Control visual condicional con `v-if` en menús, botones y secciones según el rol del usuario autenticado.
- Guards de rol en el router que redirigen a `/forbidden` si el usuario intenta acceder a una ruta sin los permisos necesarios.
- Panel de administración (`AdminView.vue`) accesible únicamente para el rol administrador.

## Resultados obtenidos

- Una SPA completamente funcional, conectada en tiempo real con el backend Laravel.
- Sistema de autenticación seguro con token Bearer, interceptores y persistencia de sesión.
- Control de acceso implementado en dos capas (backend y frontend), siguiendo el principio de mínimo privilegio.
- Interfaz coherente con el diseño corporativo, responsiva y con navegación fluida entre vistas.
- Componentes modulares y reutilizables que facilitan el mantenimiento y la escalabilidad del proyecto.

---

Esta iteración ha completado la arquitectura full-stack del proyecto, uniendo el **backend Laravel (API REST)** con el **cliente Vue 3 (SPA)**, dejando el sistema listo para las integraciones externas y el despliegue final de los sprints 5 y 6.