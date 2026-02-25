# 👥 Guía de Contribución y Normas del Equipo

Este documento establece las normas de trabajo colaborativo para garantizar la calidad del código y la fluidez en el desarrollo del proyecto.

## 🌿 Estrategia de Ramas (Branching Strategy)

Utilizamos una versión simplificada de **Gitflow**:

- **`main`**: Rama de **Producción**. El código aquí SIEMPRE debe ser estable y desplegable.
  - Los pushes directos están PROHIBIDOS.
  - Solo recibe cambios vía Pull Request (PR).
  
- **`develop`**: Rama de **Integración**. Aquí se fusionan las funcionalidades terminadas.
  - Es la base para crear nuevas ramas de funcionalidad.

- **`feature/nombre-de-la-tarea`**: Ramas temporales para desarrollo.
  - Ejemplo: `feature/login-page`, `feature/api-products`.
  - Se crean desde `develop`.
  - Se borran después de hacer merge.

- **`fix/descripcion`**: Ramas para corregir bugs críticos.
  - Ejemplo: `fix/cors-error`.

### Flujo de Trabajo Típico
1. `git checkout develop`
2. `git pull origin develop` (actualizar)
3. `git checkout -b feature/nueva-funcionalidad`
4. ... trabajar en ella, commits ...
5. `git push origin feature/nueva-funcionalidad`
6. Crear Pull Request en GitHub (`feature/...` -> `develop`)

## 📝 Commit Policy (Política de Commits)

Seguimos la convención **Conventional Commits** para mantener un historial claro:

- `feat: Mensaje`: Una nueva funcionalidad.
- `fix: Mensaje`: Corrección de un error.
- `docs: Mensaje`: Cambios solo en la documentación.
- `style: Mensaje`: Cambios de formato, espacios, etc. (no lógica).
- `refactor: Mensaje`: Refactorización de código (sin cambios lógicos).
- `test: Mensaje`: Añadir o corregir tests.
- `chore: Mensaje`: Tareas de mantenimiento (build, deps...).

**Ejemplo correcto:** `feat: Añadir validación al formulario de registro`
**Ejemplo incorrecto:** `cambios en el login`

## 💅 Code Style (Estilo de Código)

### Frontend (Vue)
- Utilizamos **ESLint** con la configuración recomendada de Vue 3 (`plugin:vue/vue3-recommended`).
- Nombres de componentes: **PascalCase** (`ProductCard.vue`).
- Props y Emits definidos explícitamente.

### Backend (Laravel)
- Utilizamos **Laravel Pint** (basado en PHP-CS-Fixer) para estandarizar el estilo PSR-12.
- Nombres de clases: **PascalCase**.
- Nombres de métodos/variables: **camelCase**.
- Nombres de tablas: **snake_case** (plural).

## ✔️ Criterios de Aceptación (Definition of Done)

Una tarea se considera "terminada" cuando:
1. El código cumple el estilo definido.
2. Funciona en el entorno local (Docker).
3. Se ha documentado si es necesario.
4. Ha pasado la revisión (Code Review) de un compañero.
5. El pipeline de CI/CD (Tests) ha pasado en verde.

## 🤝 Reparto de Responsabilidades

- **Frontend Leader**: Álvaro Pérez
  - Responsable de Vue, CSS, UX/UI.
- **Backend Leader**: Pau Albero
  - Responsable de Laravel, BD, API.
- **DevOps Shared**: Ambos
  - Responsables de Docker, AWS, GitHub Actions.

> *Todos los miembros del equipo deben conocer el funcionamiento básico del área del otro y poder hacer cambios menores.*
