# 🧩 Sprint 5 y Sprint 6 — Integraciones externas, Swagger, Docker, Despliegue final, calidad y entrega del producto

Este sprint tiene como objetivo ampliar y profesionalizar el proyecto intermodular incorporando **integraciones externas** con **OAuth2**, documentación formal de la API con **Swagger/OpenAPI**, **dockerización completa** y un flujo básico de **CI/CD**. 
Además, como actividad vinculada a la asignatura **Introducción a la Nube Pública (NUV)**, se desplegará una versión del proyecto en **AWS**, documentando el proceso y comparándolo con el despliegue local mediante **Docker Compose**.
Completaremos el proyecto con un resultado **profesional, estable y listo para producción**. 
Se trabajará en la **presentación final (UI/UX)**, la **digitalización del negocio (tecnologías inteligentes)**, la **sostenibilidad (ASG y ecodiseño)**, y el **despliegue completo con Docker en un servidor real con HTTPS**.

Además, se hará una revisión total de **calidad y documentación**, incluyendo **manual de usuario**, **accesibilidad** y una **sesión final de demostración** con el cliente.

Incluye los requisitos del cliente y mapea el trabajo a los resultados de aprendizaje:

- **C1** Integración con servicios externos y gestión segura de tokens.(DWES) - RA9
- **C2** Documentación de la API propia con Swagger (OpenAPI).(DWES) - RA9
- **C3** Mejoras avanzadas de Vue (watchers, filtros, paginación, validación en tiempo real).(DWEC) RA3.g, RA4.d, RA5.g, RA6.e
- **C4** Presentación estética, consistencia y accesibilidad profesional (DIW).
- **C5** Mejora digital: datos, IA o recomendaciones inteligentes (DIG).
- **C6** E-commerce sostenible: criterios ASG y ecodiseño (SOST).
- **C7** Despliegue Cloud, DNS y CI/CD (DDAW + NUV). DAW RA2, RA2.i, DAW RA6.h, DAW RA3.i
- **C8** Documentación final, manual de usuario, validación y presentación al cliente.DWES RA8.g, DWES RA9.f, DIW RA5.g, DIW RA6.f

---

## Índice

1. [🔗 C1. Integración con servicios externos (OAuth2)] DWES
2. [📚 C2. Documentación de la API propia con Swagger (OpenAPI)] DWES
3. [✨ C3. Mejoras avanzadas al front-end (Vue)] DWEC
4. [🎨 C4. Diseño final y accesibilidad profesional] DIW
5. [🤖 C5. Digitalización de la tienda con tecnologías inteligentes] DIG
6. [🌱 C6. Sostenibilidad: criterios ASG y ecodiseño] SOST
7. [🐳 C7. Despliegue Cloud, DNS y CI/CD] DDAW + NUV
8. [📚 C8. Documentación final, manual de usuario y presentación]
9. [✅ Entregables y criterios de validación]

---

## C1. 🔗 Integración con servicios externos (OAuth2) - (DWES)

### 1️⃣ Objetivos

Añadir una integración con **al menos una API externa** que requiera **OAuth2**, para ampliar funcionalidades o mejorar la experiencia de usuario.

Ejemplos válidos (elige **uno** como mínimo):

- **Login con Google** (OpenID Connect sobre OAuth2).
- **Importación de datos** desde un servicio externo relacionado (p. ej. Google Calendar/Drive, GitHub, Spotify, etc.) si tiene sentido con vuestro proyecto.

**Referencia:** **DWES RA9** (integración con servicios existentes, gestión segura de tokens).

---

### 2️⃣ Requisitos previos

✅ Backend Laravel (API REST) funcionando 
✅ Frontend Vue (SPA) con Axios / Pinia 
✅ Variables de entorno preparadas (`.env`) 
✅ Cuenta desarrollador del proveedor (Google Console / etc.)

---

### 3️⃣ Diseño recomendado (Laravel + OAuth2)

La forma más estándar en Laravel es usar **Laravel Socialite** para inicio de sesión con proveedores OAuth2.

**Flujo general (Authorization Code):**
1. El usuario pulsa “Inicia sesión con Google” (frontend).
2. El backend redirige a Google con `client_id`, `redirect_uri`, `scope`, `state`.
3. Google devuelve un `code` al `redirect_uri`.
4. El backend cambia `code` por `access_token` y (opcional) `refresh_token`.
5. El backend crea/actualiza usuario local y genera un token propio (p. ej. Sanctum) para el frontend.
6. El frontend guarda el token propio y trabaja con la API propia.

> Importante: **no expongáis client_secret al frontend**. El secreto debe estar **siempre** en el backend.

---

### 4️⃣ Implementación orientativa

#### A) Paquetes y configuración
- Instalar Socialite y configurar el proveedor (Google).
- Añadir credenciales a `.env`:
  - `GOOGLE_CLIENT_ID=...`
  - `GOOGLE_CLIENT_SECRET=...`
  - `GOOGLE_REDIRECT_URI=https://.../api/oauth/google/callback`

#### B) Rutas API
- `GET /api/oauth/google/redirect` → redirige al proveedor
- `GET /api/oauth/google/callback` → recibe el `code`, obtiene tokens, crea usuario, devuelve token de vuestro sistema

#### C) Gestión segura de tokens
- Guardar tokens externos **cifrados** (si es necesario reutilizarlos) o solo usarlos para una operación puntual.
- Controlar expiración y renovación (si el proyecto lo requiere).
- Revocar o “desconectar” cuenta externa (opcional pero recomendado).

---

### 5️⃣ Testing y seguridad

- Validar el parámetro `state` (CSRF).
- Comprobar permisos mínimos (scopes mínimos).
- Probar errores típicos:
  - `invalid_grant`, `redirect_uri_mismatch`, token caducado.
- No registrar tokens en logs.
- Documentar el flujo y capturas de pantalla del funcionamiento.

---

## C2. 📚 Documentación de la API propia con Swagger (OpenAPI) - (DWES)

### 1️⃣ Objetivos

Generar una **documentación completa e interactiva** de la API REST del proyecto: endpoints, parámetros, ejemplos, códigos de estado y autenticación, accesible vía interfaz web.

**Referencia:** **DWES RA9** (facilitar integraciones y transparencia para terceros).

---

### 2️⃣ Herramienta recomendada en Laravel

Opciones habituales:
- `l5-swagger` (basado en swagger-php)
- `scribe` (muy cómodo para docs automáticas)

En este sprint, se acepta cualquier opción que produzca **OpenAPI** y una UI navegable (Swagger UI o similar).

---

### 3️⃣ Contenido mínimo que debe incluir la doc

- **Todos los endpoints** de vuestro backend (mínimo los principales CRUD y auth).
- Esquemas (modelos DTO) y ejemplos JSON.
- Códigos de estado (`200`, `201`, `400`, `401`, `403`, `404`, `422`, `500`).
- Autenticación:
  - Bearer Token (Sanctum/JWT) o cookie, según vuestro caso.
- Posibilidad de **probar** peticiones desde la UI.

---

### 4️⃣ Verificación

- URL funcional (p. ej. `/api/documentation` o `/docs`).
- Capturas y explicación de cómo se autoriza en Swagger (dónde poner el Bearer token).
- Documentar cómo regenerar la doc (comando Artisan o script).

---

## C3. ✨ Mejoras avanzadas al front-end (Vue) - (DWEC)

### 1️⃣ Objetivos

Refinar la interfaz SPA con funcionalidades avanzadas y reactivas:

- **Filtros y paginación** en listados (p. ej. productos por categoría/precio, etc.)
- Uso de **watchers** para reaccionar a cambios de modelo/estado global
- Validación en tiempo real con **Vee-Validate + Yup**
- Compatibilidad entre navegadores y buenas prácticas

**Referencias:** **DWEC RA3.g, RA4.d, RA5.g, RA6.e**.

---

### 2️⃣ Filtros y paginación (patrón recomendado)

- Controles UI: input de búsqueda, select de categoría, rango de precio, etc.
- Estado en Pinia o dentro de la vista:
  - `filters` (objeto reactivo)
  - `page`, `perPage`
- Peticiones a API con query params:
  - `/api/products?search=...&category=...&min=...&max=...&page=...`

> Si la API aún no admite paginación/filtros, hay que implementarlo también en el backend (p. ej. Eloquent `paginate()` + `when()` para filtros).

---

### 3️⃣ Watchers (ejemplos de uso útil)

- Si cambia un filtro → volver a pedir datos automáticamente
- Si cambia `auth.user` o un valor global → refrescar vistas
- Debounce en búsqueda (opcional, recomendado)

---

### 4️⃣ Formularios con Vee-Validate + Yup

**Requisito:**
- Validación inmediata: campos obligados, formatos, longitudes, etc.
- Feedback visual: mensajes de error y estados de campo (valid/invalid)
- Esquemas Yup reutilizables para formularios

Ejemplos de formularios típicos:
- Login/Register
- Crear/editar producto
- Perfil de usuario

---

## C4. 🎨 Diseño final y accesibilidad profesional (DIW)

### 1️⃣ Objetivos

Aplicar una capa final de **pulido visual**, asegurando:

- Estética profesional y coherente en todas las páginas.
- Hojas de estilo **organizadas** y **mantenibles**.
- Cumplimiento de buenas prácticas de accesibilidad (WCAG básico):
  - contraste adecuado
  - navegación con teclado
  - textos alternativos (`alt`)
  - formularios accesibles (`label`, `aria-*`)
- Uso equilibrado de texto, imagen y espacios en blanco.
- Imágenes **optimizadas** (peso, formatos modernos, dimensiones correctas).

**Referencias DIW:**
- **DIW (Accesibilidad y usabilidad)** → contraste, navegación, componentes accesibles, responsive.
- **DIW (Maquetación moderna)** → CSS Grid/Flexbox, consistencia visual.

---

### 2️⃣ Requisitos y buenas prácticas de estilos

✅ Variables CSS (`:root`) para colores, tipografías, espaciados y breakpoints 
✅ Sistema de layout con **Flexbox/Grid** 
✅ Componentes reutilizables (botones, cards, formularios, badges) 
✅ Uso de `rem`, `clamp()`, `minmax()` cuando sea útil 
✅ Archivos CSS modulares y bien organizados

---

### 3️⃣ Checklist de accesibilidad

- [ ] Todas las imágenes con `alt` significativo (o `alt=""` si decorativas).
- [ ] Formularios con `label` vinculados (`for` + `id`).
- [ ] Validaciones con mensajes claros y asociados al campo (`aria-describedby`).
- [ ] Focus visible y navegación con teclado (Tab / Shift+Tab).
- [ ] Contraste AA mínimo (evitar gris claro sobre blanco).
- [ ] Botones y enlaces con texto descriptivo (evitar “clic aquí”).
- [ ] Jerarquía semántica: `h1 → h2 → h3`, `nav`, `main`, `footer`.

---

### 4️⃣ Estado del desarrollo

#### 🟦 To Do
- Auditoría de contraste y focus.
- Revisar formularios (labels, errores, aria).
- Uniformizar botones y cards.

#### 🟨 In Progress
- Refactor CSS y estructura de archivos.
- Optimización de imágenes y responsive.

#### 🟩 Done
- Diseño coherente en todas las vistas.
- Layout profesional y accesible.

---

## C5. 🤖 Digitalización de la tienda con tecnologías inteligentes (DIG)

### 1️⃣ Objetivos

Integrar una **mejora digital** basada en tecnologías habilitadoras digitales:

- ☁️ Cloud (servicios en producción, deploy, persistencia)
- 🧠 IA / recomendaciones (productos sugeridos, destacados)
- 📊 Análisis de datos (tendencias, top ventas, productos más vistos)

El equipo deberá identificar e implementar **una mejora concreta**, como por ejemplo:

- Recomendador simple: “Productos relacionados” (categoría/etiquetas/precio).
- “Productos destacados” según estadísticas (más vendidos / mejor valorados).
- Panel mínimo de analytics para admin (visitas, conversiones, top productos).
- Búsqueda inteligente con sugerencias (autocomplete básico).

---

### 2️⃣ Propuesta de implementación (ejemplos)

#### ✅ Opción A: Recomendaciones inteligentes (simple)
- Algoritmo: mismo `category_id`, precio parecido y buena valoración.
- Endpoint: `GET /api/products/{id}/recommendations`
- Vista: carrusel/listado “Recomendados para ti”.

#### ✅ Opción B: Productos destacados por datos
- Guardar métricas: `views`, `orders_count`, `rating_avg`.
- Endpoint: `GET /api/products/featured`
- Home: sección “Top productos de la semana”.

#### ✅ Opción C: Mini-analytics admin
- Tabla / gráfico con Top 5 ventas y Top 5 más vistos.
- Endpoint: `GET /api/admin/analytics/summary`
- Vista Admin: dashboard simple.

---

### 3️⃣ Estado del desarrollo

#### 🟦 To Do
- Elegir una mejora digital concreta (A/B/C).
- Definir datos necesarios y modelado DB si hace falta.

#### 🟨 In Progress
- Implementación endpoint + integración al front.

#### 🟩 Done
- Mejora digital visible y justificada a la entrega.

---

## C6. 🌱 Sostenibilidad: criterios ASG y ecodiseño (SOST)

### 1️⃣ Objetivos

Aplicar sostenibilidad al proyecto e-commerce incorporando:

- **Ambiental:** reducción de peso, optimización de imágenes, menos peticiones, eficiencia.
- **Social:** accesibilidad, inclusión, información clara, UX sin barreras.
- **Gobernanza:** transparencia (políticas, trazabilidad), buenas prácticas y calidad del código.

---

### 2️⃣ Mejoras sostenibles recomendadas

- 🖼️ Imágenes en **WebP/AVIF**, lazy loading, dimensiones adaptativas.
- 📦 Reducción de assets: minify, tree-shaking, compresión gzip/brotli en Nginx.
- ♻️ Economía circular: sección “Reacondicionados”, “Reutilizables” o “Materiales reciclados”.
- 🏷️ Etiqueta eco a los productos:
  - “Eco Score”
  - “Embalaje reciclado”
  - “Proveedor local”
- 🧾 Política de sostenibilidad visible (página informativa).

---

### 3️⃣ Estado del desarrollo

#### 🟦 To Do
- Definir qué mejoras ASG se aplican al proyecto.
- Añadir etiqueta eco y criterios al catálogo.

#### 🟨 In Progress
- Optimización de imágenes y assets.
- Ajustes en UI/UX para más claridad e inclusión.

#### 🟩 Done
- Evidencia clara de sostenibilidad + justificación en documentación.

---

## C7. 🧪 Despliegue Cloud, DNS y CI/CD (DDAW + NUV)

### 🎯 Objetivo

En esta fase del proyecto intermódulo, el equipo deberá llevar a producción la aplicación desarrollada de:

-   **Vue** (frontend)
-   **Laravel** (backend)

aplicando prácticas profesionales de:

-   Automatización de Tareas
-   Containerización
-   Integración y entrega Continua
-   Gestión de dominios y DNS
-   Seguridad HTTPS
-   Diseño de infraestructura escalable en AWS

------------------------------------------------------------------------

## 🧭 PARTE 1 --- DNS del proyecto 
### Objetivo

Gestionar una zona DNS propia delegada a vuestro servidor. La zona de trabajo será `projecteXX.ddaw.es` donde XX será el número de grupo asignado para la realización del proyecto.

### Tareas a realizar

1.  Implantar un **servidor DNS máster** en la nube (máquina virtual) o utilizando un servicio DNS en la nube.
2.  Crear la zona:

```text
    projecteXX.ddaw.es
```

3.  Definir como mínimo los registros necesarios para publicar vuestras aplicaciones:

4.  Facilitar al administrador del DNS padre los datos necesarios para hacer la **delegación**.

------------------------------------------------------------------------

## 🐳 PARTE 2 --- Entorno de desarrollo con Docker

### Objetivo

Permitir que cualquier miembro del equipo pueda arrancar las 2 aplicaciones en local.

### Requisitos

Mediante `docker-compose` se han de arrancar de forma independiente los 2 proyectos:

### Obligatorio

-   Dockerfile para cada aplicación
-   variables de entorno
-   persistencia de la base de datos (en las que sea necesario)
-   Incluir en el README de cada proyecto las instrucciones para hacerlo

------------------------------------------------------------------------

## 🚀 PARTE 3 --- Entorno de Producción y CI/CD

### Objetivo

Automatizar completamente el paso de código a producción en la nube.

### Repositorios independientes → pipelines independientes

Cada aplicación ha de tener su propio flujo de despliegue.

### CI/CD mínimo requerido

#### Frontend

-   Instalación de dependencias
-   Build de producción
-   Despliegue automático

#### Backend (Laravel)

-   Instalación de dependencias
-   Test
-   Despliegue automático
-   **ejecución obligatoria de migraciones** después del deploy

### Aislamiento de entornos

Frontend y backend han de funcionar de manera independiente: 
- Servicios/contenedores/Máquinas virtuales separadas
- Configuraciones propias
- Capacidad de desplegar uno sin afectar al otro

### HTTPS con Let's Encrypt

Las 2 aplicaciones han de ser accesibles con certificados válidos:

### Tecnologías válidas

Podéis utilizar:

-   máquinas virtuales
-   Docker
-   ECS 
-   Auto Scaling
-   Deployer o herramientas equivalentes

------------------------------------------------------------------------

## ☁️ PARTE 4 --- Arquitectura escalable en AWS

### 🎯 Objetivo

Diseñar una arquitectura en **AWS** capaz de soportar el despliegue en producción de la aplicación Vue + Laravel garantizando:

-   Separación de responsabilidades
-   Seguridad entre capas
-   Posibilidad de escalado
-   Alta disponibilidad
-   Facilidad de mantenimiento

------------------------------------------------------------------------

### 🧱 Requisitos técnicos obligatorios

#### 1️⃣ Red

-   Creación de una **VPC propia**
-   Separación como mínimo en:
    -   subredes **públicas**
    -   subredes **privadas de aplicación**
    -   subredes **privadas de datos**

Se deberá explicar: 
- Rango IP utilizado
- Distribución por AZ
- Tablas de rutas
- Acceso a Internet
- Necesidad o no de NAT

------------------------------------------------------------------------

#### 2️⃣ Capa de entrada (Edge)

Ha de existir un punto único de entrada de tráfico.

Ejemplos válidos: 
- Una EC2 con Nginx (reverse proxy)
- Un Application Load Balancer

Este componente será responsable de: 
- Terminación HTTPS
- Redirecciones
- Envío del tráfico hacia backend o frontend

------------------------------------------------------------------------

#### 3️⃣ Capa de aplicación

Donde se ejecutan:

-   Servicios del backend Laravel
-   Servicios del frontend Vue

#### Nivel Avanzado

Ha de permitir **replicar instancias**.

Ejemplos válidos: 
- Auto Scaling Group
- ECS con diversas tareas
- múltiples contenedores en diferentes nodos

------------------------------------------------------------------------

#### 4️⃣ Capa de datos

La base de datos deberá estar en subredes privadas.

Se ha de implementar o proponer:

-   RDS Multi-AZ
-   Réplica de lectura
-   Esquema de backup y recuperación

------------------------------------------------------------------------

#### 5️⃣ Seguridad

Se ha de demostrar aislamiento entre capas mediante:

-   Security Groups
-   normas de entrada y salida

Ejemplo: 
- la base de datos solo acepta conexiones del backend
- el backend solo recibe tráfico del balanceador o proxy

------------------------------------------------------------------------

## ☁️ PARTE 5 --- DOCUMENTACIÓN TÉCNICA DEL PROYECTO

### 🎯 Objetivo

El proyecto intermodular deberá ir acompañado de una documentación técnica profesional que describa el sistema a lo largo de todo su ciclo de vida.

Esta documentación deberá permitir que un equipo externo sea capaz de:

- Comprender la solución implementada 
- Arrancar el entorno de desarrollo 
- Reproducir la infraestructura en la nube 
- Desplegar nuevas versiones 
- Operar y mantener el servicio 
- Validar el funcionamiento de la aplicación 
- Continuar la evolución del sistema 

---

### 📂 Modelo documental obligatorio

Dado que el sistema está compuesto por diferentes aplicaciones en repositorios independientes, la documentación deberá organizarse en dos niveles:

1. documentación propia de cada aplicación 
2. documentación global de la solución

---

### 📄 Documentación por repositorio

Cada aplicación (frontend y backend) deberá incluir su documentación específica dentro de su repositorio.

Esta deberá describir, como mínimo:

- Arquitectura interna.
- Tecnologías Utilizadas. 
- Configuración 
- Ejecución en desarrollo 
- Proceso de build 
- Particularidades del despliegue 
- Pruebas relevantes 
- Los flujos de CI/CD 
- Los mecanismos de escalabilidad y disponibilidad 
- Cualquier otra información que creáis necesaria

El equipo responsable del repositorio será también responsable de la calidad y exactitud de esta información.

---

### 💻 Documentación de los entornos

Para diferenciar claramente los contextos de ejecución, se deberá documentar separadamente en cada repositorio:

- el entorno de desarrollo 
- el entorno de producción 

Para cada entorno se deberá explicar:

- Objetivo 
- Infraestructura 
- Configuraciones específicas 
- Forma de acceso 
- Diferencias respecto a los otros entornos 
- Capturas de pantalla con el funcionamiento básico

---

### 🔄 Integración y entrega continua

Habrá que documentar el recorrido completo desde un cambio en el repositorio hasta su disponibilidad en producción.

Se deberán describir:

- fases del pipeline 
- procesos automáticos 
- despliegue 
- migraciones 
- actualización de servicios 

---

### 👥 Normas de contribución

La documentación deberá explicar cómo se ha organizado el trabajo del equipo.

Habrá que incluir:

- Estrategia de ramas 
- Proceso de revisión de código 
- Criterios de aceptación 
- Política de versiones 
- Code Style
- Distribución de responsabilidades 

### 👤 Usuarios de prueba

La documentación deberá incluir credenciales o mecanismos que permitan verificar el funcionamiento del sistema.

No se admitirán cuentas personales de los miembros del equipo.


### 🌐 Documentación global del sistema

Además de la documentación particular, se deberá entregar una documentación transversal que describa el comportamiento conjunto del sistema.

Esta documentación deberá permitir entender:

- la relación entre frontend, backend y base de datos 
- los diferentes entornos existentes 
- la infraestructura desplegada en AWS 
- el sistema DNS 
- las medidas de seguridad 
- Cualquier otra información que creáis necesaria

### ☁️ Infraestructura en la nube

La documentación de producción deberá describir detalladamente:

- Organización de la red 
- Separación de recursos públicos y privados 
- Ubicación de los servicios 
- Punto de entrada del tráfico 
- Integración con DNS 
- Configuración de HTTPS 

### 🎓 Evaluación

La documentación se valorará atendiendo a:

- Rigor técnico 
- Claridad 
- Completitud 
- Capacidad de justificación 
- Coherencia con la infraestructura real 

Un sistema funcional pero deficientemente documentado no se considerará una solución profesional.
---

## C8. 📚 Documentación final, manual de usuario y presentación (TODOS)

### 1️⃣ Objetivos

Cerrar el proyecto con una entrega completa:

- Revisión de código y refactorización necesaria.
- Documentación técnica completa:
  - arquitectura
  - instalación
  - configuración
  - despliegue
  - credenciales / roles (sin exponer secretos)
- Manual de usuario final (claro y accesible).
- Ayuda contextual dentro de la app (tooltips, textos de ayuda).
- Pruebas en diferentes navegadores y dispositivos.
- Presentación/demostración al cliente.

**Referencias:**
- **DWES RA8.g / RA9.f:** calidad, mantenimiento y documentación.
- **DIW RA5.g / RA6.f:** accesibilidad, usabilidad y pruebas multi-dispositivo.

---

### 2️⃣ Documentación técnica (mínimos)

📄 **README principal** debe incluir:

- Descripción del proyecto y stack tecnológico.
- Cómo ejecutar en desarrollo (Docker).
- Cómo desplegar en producción (docker-compose.prod).
- Variables de entorno necesarias (sin secretos).
- Estructura de carpetas y arquitectura.
- API básica (endpoints clave).
- Roles y permisos.

---

### 3️⃣ Manual de usuario (mínimos)

- Cómo registrarse/iniciar sesión (si aplica).
- Navegación del catálogo, filtros, búsqueda.
- Ver detalle de producto, añadir al carrito, compra (si aplica).
- Gestión de perfil y pedidos.
- Funciones de admin (si existen).
- FAQ y resolución de problemas frecuentes.

📌 **Ayuda contextual dentro de la app**
- Tooltips en botones con iconos.
- Texto de ayuda en pantallas complejas (checkout, formularios).
- Mensajes de error comprensibles (no técnicos).

---

### 4️⃣ Sesión de presentación al cliente (demo)

La demostración debe incluir:

- Recorrido completo por la web y funcionalidades.
- Explicación de cómo se cumplen los requisitos iniciales.
- Muestra de: SPRINT1, SPRINT2, SPRINT3, SPRINT4, SPRINT5 y SPRINT6
- Entrega final:
  - Diagrama de tareas Gantt
  - repositorio con tag/release estable
  - documentación técnica
  - manual de usuario

---

### 5️⃣ Estado del desarrollo

#### 🟦 To Do
- Completar README + documentación técnica.
- Escribir manual de usuario y añadir ayuda contextual.

#### 🟨 In Progress
- Refactorización y revisión final (lint, errores, optimización).
- Pruebas cross-browser y responsive.

#### 🟩 Done
- Entrega completa y validada con el cliente.

## ✅ Checklist de entregables — Sprint 5 y Sprint 6

> Formato checklist para marcar ✅

### 🔗 C1 — Integración externa (OAuth2) (DWES)
- [ ] Integración con **1 servicio externo** con OAuth2 (mínimo)
- [ ] Endpoints implementados:
- [ ] `GET /api/oauth/.../redirect`
- [ ] `GET /api/oauth/.../callback`
- [ ] Tokens gestionados de forma segura (sin `client_secret` al front)
- [ ] Migración en la BBDD con nuevos campos de Google en la tabla Users
- [ ] Evidencias: **capturas** + explicación del flujo + pruebas

---

### 📚 C2 — Documentación API con Swagger / OpenAPI (DWES)
- [ ] Swagger/OpenAPI accesible (ej: `/api/documentation` o `/docs`)
- [ ] Documentación con:
- [ ] Endpoints principales (CRUD + auth)
- [ ] Esquemas/modelos + ejemplos JSON
- [ ] Códigos de estado (200/201/400/401/403/404/422/500)
- [ ] Autenticación Bearer (Sanctum/JWT o equivalente)
- [ ] Se pueden probar peticiones desde la UI
- [ ] Capturas + cómo autorizar (dónde poner el token) + cómo regenerar la doc

---

### ✨ C3 — Mejoras avanzadas Vue (DWEC)
- [ ] Listados con **filtros + paginación**
- [ ] Watchers aplicados (refresco automático cuando cambian filtros/estado)
- [ ] Formularios con **Vee-Validate + Yup** y validación en tiempo real
- [ ] (Si hace falta) Backend con filtros/paginación (`when()` + `paginate()`)

---

### 🎨 C4 — Diseño final y accesibilidad (DIW)
- [ ] UI coherente y profesional en todas las vistas
- [ ] CSS estructurado (variables, componentes reutilizables, responsive)
- [ ] Accesibilidad básica (WCAG):
- [ ] `alt` en imágenes
- [ ] `label` correctos en formularios
- [ ] Focus visible y navegación con teclado
- [ ] Buen contraste
- [ ] Estructura semántica (`h1/h2`, `nav/main/footer`, etc.)
- [ ] Imágenes optimizadas (WebP/AVIF, peso reducido, lazy loading)

---

## 🤖 C5 — Mejora digital / “inteligente” (DIG)
- [ ] 1 mejora digital implementada (elige 1):
- [ ] Recomendador (relacionados)
- [ ] Productos destacados por datos
- [ ] Mini-analytics admin
- [ ] Búsqueda inteligente (autocomplete básico)
- [ ] Endpoint(s) creado(s) + integración al front
- [ ] Justificación breve (qué aporta y cómo funciona)

---

## 🌱 C6 — Sostenibilidad (ASG + ecodiseño) (SOST)
- [ ] Optimización sostenible aplicada:
- [ ] Menos peso de assets / minify / compresión (gzip/brotli si hay Nginx)
- [ ] Imágenes modernas + lazy load
- [ ] Reducción de peticiones (cuando sea posible)
- [ ] Elemento visible “eco” (ej: etiqueta eco, embalaje reciclado, proveedor local…)
- [ ] Página o sección de sostenibilidad / criterios ASG (mínimo explicación)
- [ ] Evidencias + justificación en documentación

---

## 🐳☁️ C7 — Docker, DNS, Cloud y CI/CD (DDAW + NUV)

### DNS
- [ ] Zona `projecteXX.ddaw.es` creada
- [ ] Registros mínimos para publicar apps (A/CNAME, etc.)
- [ ] Datos entregados para la **delegación** al DNS padre

### Docker (desarrollo)
- [ ] `Dockerfile` para **frontend** y **backend**
- [ ] `docker-compose` para arrancarlo en local
- [ ] Variables de entorno (`.env.example`) y configuración
- [ ] Persistencia DB (volúmenes) donde toque
- [ ] README con instrucciones (cómo arrancar y parar)

### Producción + CI/CD
- [ ] Pipelines **separados** (repos independientes)
- [ ] Front: install → build → deploy automático
- [ ] Back: install → test → deploy automático
- [ ] Back: **migraciones obligatorias** después del deploy
- [ ] Front y back aislados (servicios/containers/VM separados)
- [ ] HTTPS con Let’s Encrypt en las 2 aplicaciones

### Arquitectura AWS (documentada)
- [ ] VPC + subredes públicas/privadas (app y datos)
- [ ] Edge único (ALB o Nginx reverse proxy) + terminación HTTPS
- [ ] Capa app escalable (ASG/ECS o equivalente)
- [ ] Capa datos privada (RDS Multi-AZ / backups / recuperación)
- [ ] Seguridad con Security Groups (aislamiento entre capas)

---

## 📚 C8 — Documentación final + manual + presentación
- [ ] README global con Documentación técnica completa (arquitectura, CI/CD, entornos, acceso)
- [ ] Manual de usuario (uso básico + FAQ) + ayuda contextual dentro de la app
- [ ] Pruebas en navegadores y dispositivos (evidencias)
- [ ] Tag/Release estable en el repositorio
- [ ] Gantt/planificación
- [ ] Demo al cliente (mostrando Sprints 1–6)
