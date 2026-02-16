# Lista de Tareas

## Backend (Laravel)
- [x] Modificar `app/Http/Controllers/ProductController.php` - `apiIndex`.
  - [x] Implementar recibo de parámetros `request`.
  - [x] Añadir clausula `when` para `search` (`name` o `description`).
  - [x] Añadir clausula `when` para `category`.
  - [x] Añadir clausula `when` para `min_price`.
  - [x] Añadir clausula `when` para `max_price`.
  - [x] Cambiar `get()`/`all()` por `paginate(12)`.

## Frontend (Vue)
- [x] Instalar dependencias `vee-validate`, `yup` (Ya hecho).
- [x] Modificar `frontend/src/stores/products.js`:
  - [x] Actualizar estado (`state`) con `products`, `pagination`, `filters`.
  - [x] Reescribir acción `fetchProducts(page = 1)` para usar filtros y paginación.
  - [x] Añadir acción `resetFilters`.
- [x] Actualizar `frontend/src/views/ProductsView.vue`:
  - [x] Añadir input búsqueda, select categoría, inputs precio.
  - [x] Añadir paginación (botones Next/Prev).
  - [x] Implementar watchers para filtros.
  - [x] Mostrar loading state.
- [x] Refactorizar `frontend/src/views/LoginView.vue`:
  - [x] Importar `Form`, `Field`, `ErrorMessage` de `vee-validate`.
  - [x] Importar `yup`.
  - [x] Definir esquema de validación (`yup.object(...)`).
  - [x] Reemplazar inputs estándar por componentes `vee-validate`.
  - [x] Manejar `onSubmit` usando el handler de `vee-validate`.

## Validación Final
- [x] Verificar funcionamiento de filtros en `ProductsView`.
- [x] Verificar paginación en `ProductsView`.
- [x] Verificar validación en `LoginView`.
