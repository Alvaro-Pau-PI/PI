# Plan de Implementación: Mejoras Frontend Avanzadas (Vue)

Objetivo: Refinar la interfaz SPA con filtros, paginación, watchers y validación de formularios reactiva.

## 1. Backend (Laravel) - `ProductController.php`
Modificar el método `apiIndex` para soportar filtrado y paginación.
- **Filtros requeridos:**
  - `search`: búsqueda por nombre idéntico o parcial (`like %...%`).
  - `category`: coincidencia exacta.
  - `min_price` / `max_price`: rango de precio.
- **Paginación:**
  - Cambiar `Product::all()` por `Product::paginate(12)`.
  - Retornar la estructura paginada completa (data, links, meta).

## 2. Store (Pinia) - `stores/products.js`
- Actualizar el estado (`state`):
  - `products`: ahora almacenará `response.data` (array de productos).
  - `pagination`: almacenará `current_page`, `last_page`, `total`, etc.
  - `filters`: objeto con `term`, `category`, `minPrice`, `maxPrice`.
- Actualizar `fetchProducts(page = 1)`:
  - Construir query string basada en `filters` y `page`.
  - Realizar petición GET con parámetros.
  - Actualizar `products` y `pagination`.
- Añadir acciones helper para reiniciar filtros o cambiar página.

## 3. Vista de Productos - `views/ProductsView.vue`
- Crear zona de filtros (Sidebar o Topbar):
  - Input texto para búsqueda.
  - Select para categoría (Hardcoded o dinámico, usaremos lista estática según modelo).
  - Inputs numéricos para rango de precio.
  - Botón "Limpiar Filtros".
- Implementar **Watchers**:
  - `watch(filters, ...)`: Debounce para búsqueda, recarga automática al cambiar filtros.
- Mostrar Paginación:
  - Botones "Anterior" / "Siguiente".
  - Indicador "Página X de Y".

## 4. Validación de Formularios - `views/LoginView.vue`
- Refactorizar formulario existente para usar **Vee-Validate** y **Yup**.
- Esquema de validación:
  - Email: requerido, formato email válido.
  - Password: requerido, min 6 caracteres.
- Componentes visuales:
  - `<Form>` y `<Field>` de vee-validate.
  - Mensajes de error en tiempo real (`<ErrorMessage>` o slot).
  - Deshabilitar botón envío si hay errores o está `isSubmitting`.

## 5. Riesgos y Consideraciones
- **Compatibilidad API**: Asegurar que el frontend maneje la estructura de respuesta paginada de Laravel (diferente a `Product::all()` que devuelve array directo).
- **Debounce**: Implementar correctamente para evitar spam de peticiones al escribir.
