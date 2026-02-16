# Mejoras Avanzadas en Frontend (Vue) y Backend

## Resumen de Cambios
Se ha implementado un sistema completo de filtros, paginación y validación reactiva en la SPA.

### 1. Backend (Laravel)
- **`ProductController.php`**: Method `apiIndex` actualizado.
  - Soporte para parámetros de consulta: `search`, `category`, `min_price`, `max_price`.
  - Implementación de paginación con `paginate(12)`.

### 2. Store (Pinia)
- **`stores/products.js`**:
  - Estado ampliado para manejar `filters` y `pagination`.
  - Acción `fetchProducts` reescrita para consumir la API paginada y filtrada.
  - Watchers integrados en la vista para reactividad.

### 3. Vistas (Frontend)
- **`ProductsView.vue`**:
  - Nueva estructura con Sidebar de filtros.
  - Controles de filtrado por nombre, categoría y precio.
  - Paginación funcional (Anterior/Siguiente).
  - Estilos actualizados y diseño responsivo.
- **`LoginView.vue`**:
  - Refactorizada con **Vee-Validate** y **Yup**.
  - Validación en tiempo real (email válido, contraseña min 6 chars).
  - Feedback visual de errores (`is-invalid`, mensajes rojos).

## Cómo Verificar y Probar
1. **Ejecutar servidores** (si no están corriendo):
   ```bash
   # Terminal 1 (Laravel)
   cd laravel
   php artisan serve
   
   # Terminal 2 (Vue)
   cd frontend
   npm run dev
   ```

2. **Probar Filtros y Paginación**:
   - Navegar a "Productes".
   - Escribir en el buscador (ej. "Intel"). Verás que la lista se actualiza (con debounce).
   - Filtrar por categoría o precio.
   - Usar los botones de paginación abajo si hay suficientes resultados.

3. **Probar Validación Login**:
   - Ir a "Iniciar Sessió".
   - Intentar enviar el formulario vacío. Verás mensajes de error.
   - Escribir un email inválido (ej. "hola"). Verás error "Email no vàlid".
   - Escribir una contraseña corta. Verás error "Mínim 6 caràcters".
   - Al cumplir los requisitos, los errores desaparecen.
