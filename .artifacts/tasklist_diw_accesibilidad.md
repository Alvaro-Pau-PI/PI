# Lista de Tareas: DIW - Diseño Final y Accesibilidad

## 📋 Estado General
- 🟦 **To Do** - Por hacer
- 🟨 **In Progress** - En progreso  
- 🟩 **Done** - Completado

---

## FASE 1: Preparación y Estructura

### 1.1 Crear estructura de carpetas CSS
- [ ] 🟦 Crear `/frontend/src/assets/css/`
- [ ] 🟦 Crear `/frontend/src/assets/css/1-tokens/`
- [ ] 🟦 Crear `/frontend/src/assets/css/2-base/`
- [ ] 🟦 Crear `/frontend/src/assets/css/3-components/`
- [ ] 🟦 Crear `/frontend/src/assets/css/4-layout/`
- [ ] 🟦 Crear `/frontend/src/assets/css/utilities/`

### 1.2 Backup de archivos actuales
- [ ] 🟦 Copiar `styles.css` a `styles.backup.css`

---

## FASE 2: Sistema de Tokens (Variables CSS)

### 2.1 Crear archivo de variables
- [ ] 🟦 Crear `1-tokens/_variables.css`
- [ ] 🟦 Definir variables de colores (primary, bg, text, etc.)
- [ ] 🟦 Definir variables de tipografía (fonts, sizes, weights)
- [ ] 🟦 Definir variables de espaciado (spacing-xs a 2xl)
- [ ] 🟦 Definir variables de bordes y radios
- [ ] 🟦 Definir variables de sombras
- [ ] 🟦 Definir variables de transiciones

### 2.2 Crear breakpoints
- [ ] 🟦 Crear `1-tokens/_breakpoints.css`
- [ ] 🟦 Definir media queries estándar (sm, md, lg, xl)

---

## FASE 3: Estilos Base

### 3.1 Reset y normalización
- [ ] 🟦 Crear `2-base/_reset.css`
- [ ] 🟦 Reset básico (margin, padding, box-sizing)
- [ ] 🟦 Configurar `:root` y `body`

### 3.2 Tipografía base
- [ ] 🟦 Crear `2-base/_typography.css`
- [ ] 🟦 Importar Google Fonts (Montserrat, Roboto)
- [ ] 🟦 Definir estilos de headings (h1-h6)
- [ ] 🟦 Definir estilos de párrafos y enlaces
- [ ] 🟦 Crear clase `.sr-only` para screen readers

### 3.3 Layout base
- [ ] 🟦 Crear `2-base/_layout.css`
- [ ] 🟦 Contenedores principales
- [ ] 🟦 Sistema básico de Grid/Flexbox

---

## FASE 4: Componentes Reutilizables

### 4.1 Sistema de botones
- [ ] 🟦 Crear `3-components/_buttons.css`
- [ ] 🟦 Clase base `.btn`
- [ ] 🟦 Variantes: `.btn-primary`, `.btn-accent`, `.btn-outline`
- [ ] 🟦 Estados: `:hover`, `:focus-visible`, `:disabled`
- [ ] 🟦 Tamaños: `.btn-sm`, `.btn-lg`

### 4.2 Sistema de cards
- [ ] 🟦 Crear `3-components/_cards.css`
- [ ] 🟦 Clase base `.card`
- [ ] 🟦 Variantes: `.card-product`, `.card-elevated`
- [ ] 🟦 Estados hover con micro-animaciones

### 4.3 Sistema de formularios
- [ ] 🟦 Crear `3-components/_forms.css`
- [ ] 🟦 Estilos para `input`, `textarea`, `select`
- [ ] 🟦 Estilos para `label` (normal y `.form-label`)
- [ ] 🟦 Estados de validación (`:valid`, `:invalid`)
- [ ] 🟦 Mensajes de error `.error-msg`
- [ ] 🟦 Focus visible mejorado

### 4.4 Badges y pills
- [ ] 🟦 Crear `3-components/_badges.css`
- [ ] 🟦 `.badge` base
- [ ] 🟦 Variantes: `.badge-primary`, `.badge-accent`, `.badge-success`
- [ ] 🟦 Badge de precio (`.precio`)

### 4.5 Sistema de rating (estrellas)
- [ ] 🟦 Crear `3-components/_rating.css`
- [ ] 🟦 Migrar estilos `.star-rating` existentes
- [ ] 🟦 Mejorar accesibilidad con aria-labels

---

## FASE 5: Layout Específico

### 5.1 Header/Navbar
- [ ] 🟦 Crear `4-layout/_header.css`
- [ ] 🟦 Migrar estilos de `.cabecera`
- [ ] 🟦 Usar variables CSS en lugar de valores hardcoded
- [ ] 🟦 Responsive navegación

### 5.2 Footer
- [ ] 🟦 Crear `4-layout/_footer.css`
- [ ] 🟦 Migrar estilos de `footer` y `.footer`
- [ ] 🟦 Usar variables CSS

### 5.3 Grid de productos
- [ ] 🟦 Crear `4-layout/_grid-products.css`
- [ ] 🟦 Migrar estilos de `.productos` y `.producto`
- [ ] 🟦 Usar variables CSS
- [ ] 🟦 Responsive grid con `auto-fit`

---

## FASE 6: Archivo Principal

### 6.1 Crear main.css
- [ ] 🟦 Crear `css/main.css`
- [ ] 🟦 Importar en orden: tokens → base → components → layout
- [ ] 🟦 Añadir comentarios de organización

### 6.2 Actualizar imports
- [ ] 🟦 Modificar `main.js` para importar `css/main.css`
- [ ] 🟦 Comentar (no eliminar) import de `styles.css`

---

## FASE 7: Utilidades y Helpers

### 7.1 Screen reader only
- [ ] 🟦 Crear `utilities/_sr-only.css`
- [ ] 🟦 Clase `.sr-only` para ocultar visualmente pero mantener accesible

### 7.2 Utilidades de espaciado (opcional)
- [ ] 🟦 Clases de margin/padding si son necesarias

---

## FASE 8: Mejoras de Accesibilidad en Componentes

### 8.1 Navbar.vue
- [ ] 🟦 Añadir `aria-label` en botones con iconos
- [ ] 🟦 Asegurar `title` descriptivo en enlaces
- [ ] 🟦 Verificar focus visible
- [ ] 🟦 Elemento `<nav>` con `aria-label="Navegación principal"`

### 8.2 Footer.vue
- [ ] 🟦 Añadir `<label>` para input de newsletter
- [ ] 🟦 Asociar label con `for="newsletter-email"`
- [ ] 🟦 Añadir `aria-label` en botón de suscripción
- [ ] 🟦 Elemento `<footer>` con `role="contentinfo"`

### 8.3 ReviewModal.vue (o componente de reviews)
- [ ] 🟦 Añadir labels en sistema de estrellas
- [ ] 🟦 `aria-label` descriptivo para cada estrella
- [ ] 🟦 `role="group"` y `aria-labelledby` para el conjunto
- [ ] 🟦 Navegación con teclado funcional

### 8.4 Formularios en vistas
- [ ] 🟦 LoginView: labels y aria-describedby
- [ ] 🟦 ContactView: labels, errores con aria
- [ ] 🟦 Todos los inputs con `id` único
- [ ] 🟦 Mensajes de error con `role="alert"`

---

## FASE 9: Auditoría de Contraste

### 9.1 Validación de colores
- [ ] 🟦 Verificar #EAEAEA sobre #1A1D24 (texto principal)
- [ ] 🟦 Verificar #9BA3B0 sobre #1A1D24 (texto secundario)
- [ ] 🟦 Verificar #00A1FF sobre #1A1D24 (enlaces)
- [ ] 🟦 Verificar badges y botones
- [ ] 🟦 Documentar ratios de contraste

### 9.2 Ajustes si necesario
- [ ] 🟦 Ajustar colores que no cumplan WCAG AA
- [ ] 🟦 Actualizar variables CSS con nuevos valores

---

## FASE 10: Optimización de Imágenes

### 10.1 Auditoría de atributos alt
- [ ] 🟦 Revisar todas las imágenes en componentes
- [ ] 🟦 Logo: alt descriptivo "Logo AlberoPerez Tech"
- [ ] 🟦 Productos: alt con nombre del producto
- [ ] 🟦 Imágenes decorativas: `alt=""`
- [ ] 🟦 Banner: alt descriptivo

### 10.2 Lazy loading (si aplica)
- [ ] 🟦 Añadir `loading="lazy"` en imágenes fuera del viewport inicial

### 10.3 Dimensiones explícitas
- [ ] 🟦 Verificar que imágenes tengan width/height definidos

---

## FASE 11: Focus Visible Global

### 11.1 Implementar focus consistente
- [ ] 🟦 Crear regla global `:focus-visible`
- [ ] 🟦 Aplicar a: `a`, `button`, `input`, `textarea`, `select`
- [ ] 🟦 Usar variable `--color-primary` para outline
- [ ] 🟦 `outline-offset` para separación visual

---

## FASE 12: Pruebas y Validación

### 12.1 Pruebas visuales
- [ ] 🟦 Ejecutar servidor de desarrollo
- [ ] 🟦 Verificar Home page
- [ ] 🟦 Verificar Products page
- [ ] 🟦 Verificar Contact page
- [ ] 🟦 Verificar Login page
- [ ] 🟦 Verificar Profile/Admin page
- [ ] 🟦 Comparar con diseño anterior (backup)

### 12.2 Pruebas de accesibilidad
- [ ] 🟦 Navegación completa con teclado (Tab/Shift+Tab)
- [ ] 🟦 Verificar focus visible en todos los elementos
- [ ] 🟦 Probar formularios solo con teclado
- [ ] 🟦 Verificar mensajes de error legibles
- [ ] 🟦 Usar Chrome DevTools Lighthouse (Accessibility score)

### 12.3 Pruebas responsive
- [ ] 🟦 Mobile (320px - 640px)
- [ ] 🟦 Tablet (641px - 1024px)
- [ ] 🟦 Desktop (1025px+)
- [ ] 🟦 Verificar que grid de productos se adapta
- [ ] 🟦 Verificar que navbar es responsive

### 12.4 Validación de contraste
- [ ] 🟦 Usar herramienta de contraste (Chrome DevTools)
- [ ] 🟦 Verificar todos los pares texto/fondo
- [ ] 🟦 Documentar resultados

---

## FASE 13: Documentación Final

### 13.1 Crear guía de uso CSS
- [ ] 🟦 Documentar cómo usar las variables CSS
- [ ] 🟦 Documentar clases de componentes reutilizables
- [ ] 🟦 Ejemplos de uso para cada componente

### 13.2 Checklist final de accesibilidad
- [ ] 🟦 Crear checklist WCAG cumplido
- [ ] 🟦 Documentar posibles mejoras futuras

---

## FASE 14: Limpieza y Optimización

### 14.1 Eliminar código duplicado
- [ ] 🟦 Buscar estilos duplicados entre componentes
- [ ] 🟦 Centralizar en archivos modulares

### 14.2 Comentarios en CSS
- [ ] 🟦 Añadir comentarios explicativos en cada módulo
- [ ] 🟦 Documentar decisiones de diseño no obvias

### 14.3 Archivo styles.css original
- [ ] 🟦 Mantener como backup (no eliminar)
- [ ] 🟦 Añadir comentario de deprecación en la primera línea

---

## 📊 Resumen de Progreso

### Archivos a Crear: 15
- [ ] `css/1-tokens/_variables.css`
- [ ] `css/1-tokens/_breakpoints.css`
- [ ] `css/2-base/_reset.css`
- [ ] `css/2-base/_typography.css`
- [ ] `css/2-base/_layout.css`
- [ ] `css/3-components/_buttons.css`
- [ ] `css/3-components/_cards.css`
- [ ] `css/3-components/_forms.css`
- [ ] `css/3-components/_badges.css`
- [ ] `css/3-components/_rating.css`
- [ ] `css/4-layout/_header.css`
- [ ] `css/4-layout/_footer.css`
- [ ] `css/4-layout/_grid-products.css`
- [ ] `css/utilities/_sr-only.css`
- [ ] `css/main.css`

### Archivos a Modificar: 6+
- [ ] `main.js`
- [ ] `components/Navbar.vue`
- [ ] `components/Footer.vue`
- [ ] `components/ReviewModal.vue`
- [ ] `views/LoginView.vue`
- [ ] Otras vistas con formularios

---

## 🎯 Criterios de Aceptación

Al completar todas las tareas, el proyecto debe:

✅ Tener sistema completo de variables CSS  
✅ Estilos modulares y organizados  
✅ Contraste mínimo WCAG AA en todos los textos  
✅ Navegación completa con teclado  
✅ Focus visible en elementos interactivos  
✅ Formularios accesibles (labels, aria)  
✅ Imágenes con alt descriptivo  
✅ Sistema de componentes reutilizables  
✅ Responsive funcional en todos los breakpoints  
✅ Sin errores en consola  
✅ Lighthouse Accessibility score > 90

---

## 📝 Notas

- Cada fase debe probarse antes de continuar a la siguiente
- Mantener commits atómicos y descriptivos
- Documentar cualquier decisión de diseño importante
- Consultar con el usuario si hay dudas sobre UX/UI
