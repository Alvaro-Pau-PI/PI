# Iteración 3: Migración a Laravel v2 (mínimo viable)

En este sprint hemos dado el salto de la versión PHP nativo + JSON Server (v1) a una versión profesional con **Laravel (v2)**. El objetivo principal ha sido construir un backend mínimo viable sobre MySQL, consolidando un ecosistema MVC real con migraciones, modelos y autenticación moderna, preparando el terreno para futuras integraciones (SPA Vue y microservicios).

## Objetivos principales

- **Migración a Framework**: Entender y configurar un proyecto Laravel (MVC, Rutas, Eloquent) conectado a MySQL.
- **Autenticación Robusta**: Integrar Laravel Breeze y compararlo con la autenticación manual anterior.
- **Automatización**: Implementar la importación masiva de productos desde Excel directamente a la base de datos.
- **API First**: Exponer datos vía API REST para el futuro cliente SPA (Vue.js).
- **Testing**: Validar el funcionamiento crítico (API, Auth, Imports) mediante tests automatizados.

## Actividades realizadas

### C1. Configuración del entorno Laravel
- Inicialización del proyecto en la carpeta `laravel/`, manteniendo `legacy-php/` como referencia.
- Configuración del entorno (`.env`) para conectar con la instancia MySQL existente.
- Estructura de contenedores Docker preparada para servir la aplicación.

### C2. Modelo de Datos y Migraciones
- Creación de migraciones y modelos Eloquent para `Product`, `User` y `Review`.
- Definición de campos clave (`sku`, `price`, `stock`) y relaciones.
- Utilización de **Seeders** para poblar la base de datos con datos de prueba iniciales.

### C3. Autenticación con Laravel Breeze
- Implementación del stack de autenticación Breeze (versión Blade).
- Personalización de las vistas de login y registro para adaptarlas al tema oscuro corporativo.
- Redirección post-login ajustada para llevar al usuario a `/productes`.

### C4. Importación Automática (Excel)
- Desarrollo de un controlador de importación (`ProductImportController`).
- Validación estricta de datos (formatos numéricos, campos obligatorios) durante la carga de ficheros `.xlsx`.
- Gestión de errores y feedback al usuario.

### C5. Frontend y API
- **Vistas**: Creación de `products/index.blade.php` reutilizando los estilos "Dark Mode" de la v1 para una lista de productos responsiva.
- **API**: Definición de endpoints en `routes/api.php` (`GET /api/products`) para exponer el catálogo en formato JSON.

### C6. Interactividad (Reviews)
- Sistema de valoraciones implementado con JavaScript (AJAX/Fetch) consumiendo la API del backend.
- Permite a los usuarios autenticados dejar comentarios y puntuaciones sin recargar la página.
- Validaciones tanto en cliente (JS) como en servidor (Laravel Validation).

### C7. Pruebas (Testing)
- Batería de tests automatizados (`tests/Feature`) para verificar:
    - Respuestas correctas de la API de productos.
    - Ciclo de vida de las Reviews.
    - Proceso de importación.
    - Flujo de autenticación (Breeze).

## Resultados y Evidencias

El sistema ha sido migrado exitosamente a Laravel, cumpliendo con todos los criterios de evaluación.

### Evidencia Visual (Listado de Productos)
La siguiente captura muestra la vista principal de productos implementada con Blade y datos reales de MySQL:

![Listado de Productos](file:///home/batoi/.gemini/antigravity/brain/d7133841-694a-458c-8a15-7051bede9042/product_list_screenshot_1770131908360.png)

### Resumen de Pruebas
Se han ejecutado y superado **37 tests automatizados**, garantizando la estabilidad del backend v2.
```
PASS Tests\Feature\ProductApiTest
PASS Tests\Feature\ReviewApiTest
PASS Tests\Feature\ImportTest
...
Tests: 37 passed
```

---

**Siguientes pasos**: Este backend servirá de base para el **Sprint 4**, donde desarrollaremos una Single Page Application (SPA) con **Vue.js** que consumirá la API que acabamos de crear.
