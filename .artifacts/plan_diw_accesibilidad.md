# Plan de Implementación: Diseño Final y Accesibilidad Profesional (DIW)

## 🎯 Objetivo General
Aplicar una capa final de polimiento visual y accesibilidad, asegurando que el proyecto cumpla con los estándares profesionales de DIW (Diseño de Interfaces Web) y WCAG básico.

## 📊 Análisis del Estado Actual

### Estructura Detectada
```
frontend/src/
├── assets/
│   ├── styles.css (14KB - archivo monolítico con todos los estilos)
│   └── vue.svg
├── components/
│   ├── Footer.vue
│   ├── Navbar.vue
│   ├── HelloWorld.vue
│   └── ReviewModal.vue
├── views/
│   └── (7 vistas diferentes)
├── style.css (estilos base de Vite - 1.2KB)
└── main.js (importa assets/styles.css)
```

### Problemas Identificados

#### 1. **Arquitectura CSS**
- ❌ Archivo monolítico de 837 líneas sin modularización
- ❌ No hay variables CSS (`:root`) para tokens de diseño
- ❌ Valores "hardcoded" (colores, espaciados, tipografías)
- ❌ Difícil mantenibilidad y escalabilidad

#### 2. **Accesibilidad**
- ⚠️ Algunos botones sin texto descriptivo (solo iconos)
- ⚠️ Posible falta de `aria-*` en componentes interactivos
- ⚠️ Sistema de estrellas en reviews sin labels accesibles
- ⚠️ Formularios sin `aria-describedby` para errores
- ⚠️ Contraste a validar (gris #9BA3B0 sobre fondos oscuros)

#### 3. **Componentes Reutilizables**
- ❌ Estilos de botones definidos inline y dispersos
- ❌ Cards sin clases consistentes
- ❌ Formularios con estilos repetidos

#### 4. **Responsive**
- ✅ Ya implementado con media queries
- ⚠️ Podría mejorarse con breakpoints en variables

## 🔧 Cambios Propuestos

### 1. Refactorización CSS Modular

**Estructura Nueva:**
```
frontend/src/assets/
├── css/
│   ├── 1-tokens/
│   │   ├── _variables.css      # Variables CSS (:root)
│   │   └── _breakpoints.css    # Media queries breakpoints
│   │
│   ├── 2-base/
│   │   ├── _reset.css          # Reset básico
│   │   ├── _typography.css     # Tipografías y jerarquía
│   │   └── _layout.css         # Contenedores, grid, flex
│   │
│   ├── 3-components/
│   │   ├── _buttons.css        # Sistema de botones
│   │   ├── _cards.css          # Cards reutilizables
│   │   ├── _forms.css          # Inputs, textareas, labels
│   │   ├── _badges.css         # Badges (precio, roles)
│   │   └── _Rating.css         # Sistema de estrellas
│   │
│   ├── 4-layout/
│   │   ├── _header.css         # Cabecera/Navbar
│   │   ├── _footer.css         # Pie de página
│   │   └── _grid-products.css  # Grid de productos
│   │
│   └── main.css                # Importa todo en orden
│
└── styles.css (DEPRECADO - se mantiene temporalmente)
```

**Migración:**
- Se copiará `styles.css` como backup
- Se extraerán valores a variables
- Se distribuirán estilos por módulos
- Se actualizará `main.js` para importar `css/main.css`

### 2. Sistema de Tokens (Variables CSS)

**Variables a definir en `_variables.css`:**

```css
:root {
  /* === COLORES === */
  /* Principales */
  --color-primary: #00A1FF;
  --color-primary-dark: #007ecc;
  --color-accent: #FF6C00;
  
  /* Fondos */
  --bg-body: #1A1D24;
  --bg-card: #242833;
  --bg-input: #1A1D24;
  
  /* Bordes */
  --border-color: #3A4150;
  --border-focus: #00A1FF;
  
  /* Textos */
  --text-primary: #EAEAEA;
  --text-secondary: #9BA3B0;
  --text-muted: #777;
  
  /* Estados */
  --color-success: #2ecc71;
  --color-error: #ff4d4d;
  --color-warning: #ffc107;
  
  /* === TIPOGRAFÍA === */
  --font-primary: 'Roboto', Arial, sans-serif;
  --font-headings: 'Montserrat', sans-serif;
  
  --font-size-base: 1rem;
  --font-size-sm: 0.875rem;
  --font-size-lg: 1.125rem;
  --font-size-xl: 1.5rem;
  --font-size-2xl: 2rem;
  
  --line-height-base: 1.6;
  --line-height-tight: 1.2;
  
  /* === ESPACIADO === */
  --spacing-xs: 0.5rem;    /* 8px */
  --spacing-sm: 0.75rem;   /* 12px */
  --spacing-md: 1rem;      /* 16px */
  --spacing-lg: 1.5rem;    /* 24px */
  --spacing-xl: 2rem;      /* 32px */
  --spacing-2xl: 3rem;     /* 48px */
  
  /* === BORDES === */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-full: 9999px;
  
  /* === SOMBRAS === */
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.15);
  --shadow-lg: 0 8px 20px rgba(0, 0, 0, 0.3);
  --shadow-focus: 0 0 0 3px rgba(0, 161, 255, 0.3);
  
  /* === TRANSICIONES === */
  --transition-fast: 0.15s ease;
  --transition-base: 0.25s ease;
  --transition-slow: 0.4s ease;
  
  /* === BREAKPOINTS (en comentarios para referencia) === */
  /* sm: 640px */
  /* md: 768px */
  /* lg: 1024px */
  /* xl: 1280px */
}
```

### 3. Mejoras de Accesibilidad

#### 3.1 Formularios
**Antes:**
```html
<input type="email" placeholder="Escribe tu email aquí">
```

**Después:**
```html
<label for="newsletter-email" class="sr-only">Correo electrónico para newsletter</label>
<input 
  id="newsletter-email"
  type="email" 
  placeholder="Escribe tu email aquí"
  aria-label="Correo electrónico para newsletter"
>
```

#### 3.2 Botones con Iconos
**Antes:**
```html
<button>
  <span class="material-icons">logout</span>
</button>
```

**Después:**
```html
<button aria-label="Cerrar sesión" title="Cerrar sesión">
  <span class="material-icons" aria-hidden="true">logout</span>
</button>
```

#### 3.3 Sistema de Estrellas (Rating)
**Mejoras:**
- Añadir `aria-label` descriptivo
- Asegurar navegación con teclado
- Feedback visual claro del rating seleccionado

#### 3.4 Validaciones de Formularios
```html
<input 
  id="campo-nombre"
  aria-describedby="error-nombre"
  aria-invalid="true"
>
<span id="error-nombre" class="error-msg" role="alert">
  El nombre es obligatorio
</span>
```

#### 3.5 Focus Visible
Asegurar que todos los elementos interactivos tengan un `:focus-visible` claro:
```css
button:focus-visible,
a:focus-visible,
input:focus-visible {
  outline: 3px solid var(--color-primary);
  outline-offset: 2px;
}
```

### 4. Sistema de Componentes Reutilizables

#### 4.1 Botones
```css
/* Botón primario */
.btn {
  padding: var(--spacing-sm) var(--spacing-lg);
  border-radius: var(--radius-md);
  font-family: var(--font-headings);
  font-weight: 600;
  transition: var(--transition-base);
  cursor: pointer;
  border: none;
}

.btn-primary {
  background: var(--color-primary);
  color: white;
}

.btn-primary:hover {
  background: var(--color-primary-dark);
}

.btn-accent {
  background: var(--color-accent);
  color: white;
}
```

#### 4.2 Cards
```css
.card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: var(--spacing-lg);
  transition: var(--transition-base);
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}
```

### 5. Auditoría de Contraste

**Ratios a validar (WCAG AA = 4.5:1 para texto normal):**

| Combinación | Contraste | ¿Cumple? |
|-------------|-----------|----------|
| #EAEAEA sobre #1A1D24 | ~12:1 | ✅ AAA |
| #9BA3B0 sobre #1A1D24 | ~7:1 | ✅ AA |
| #00A1FF sobre #1A1D24 | ~4.8:1 | ✅ AA |
| #777 sobre #1A1D24 | ~3.5:1 | ⚠️ NO (placeholders OK) |

**Acciones:**
- ✅ Los colores principales cumplen
- ⚠️ Revisar uso de `#777` solo en placeholders (permitido)

### 6. Optimización de Imágenes

**Checklist:**
- [ ] Verificar formatos (WebP preferido)
- [ ] Validar atributos `alt` descriptivos
- [ ] Implementar lazy loading donde corresponda
- [ ] Asegurar dimensiones explícitas (width/height)

## 📦 Archivos a Crear/Modificar

### Crear
1. `/frontend/src/assets/css/1-tokens/_variables.css`
2. `/frontend/src/assets/css/1-tokens/_breakpoints.css`
3. `/frontend/src/assets/css/2-base/_reset.css`
4. `/frontend/src/assets/css/2-base/_typography.css`
5. `/frontend/src/assets/css/2-base/_layout.css`
6. `/frontend/src/assets/css/3-components/_buttons.css`
7. `/frontend/src/assets/css/3-components/_cards.css`
8. `/frontend/src/assets/css/3-components/_forms.css`
9. `/frontend/src/assets/css/3-components/_badges.css`
10. `/frontend/src/assets/css/3-components/_rating.css`
11. `/frontend/src/assets/css/4-layout/_header.css`
12. `/frontend/src/assets/css/4-layout/_footer.css`
13. `/frontend/src/assets/css/4-layout/_grid-products.css`
14. `/frontend/src/assets/css/main.css`
15. `/frontend/src/assets/css/utilities/_sr-only.css`

### Modificar
1. `/frontend/src/main.js` - Cambiar import de styles
2. `/frontend/src/components/Navbar.vue` - Mejorar accesibilidad
3. `/frontend/src/components/Footer.vue` - Añadir labels
4. `/frontend/src/components/ReviewModal.vue` - Accesibilidad rating
5. Todas las vistas con formularios

### Backup
1. `/frontend/src/assets/styles.css` → `styles.backup.css`

## ⚠️ Riesgos y Mitigaciones

| Riesgo | Impacto | Mitigación |
|--------|---------|------------|
| Romper estilos existentes | Alto | Crear backup, migrar gradualmente |
| Inconsistencias de clase | Medio | Buscar y reemplazar sistemáticamente |
| Problemas de carga CSS | Bajo | Verificar orden de imports |
| Regresión visual | Medio | Probar en navegador tras cada módulo |

## 🧪 Plan de Pruebas

1. **Visual:** Comparar antes/después en navegador
2. **Contraste:** Usar herramientas de contraste (Chrome DevTools)
3. **Teclado:** Navegar con Tab/Shift+Tab por toda la app
4. **Screen Reader:** Probar con lector de pantalla básico
5. **Responsive:** Verificar en diferentes tamaños de pantalla

## 📈 Criterios de Éxito

- ✅ Todas las variables CSS definidas y utilizadas
- ✅ Estilos modulares y organizados
- ✅ Contraste mínimo AA en todos los textos principales
- ✅ Formularios con labels y aria-describedby
- ✅ Navegación completa con teclado
- ✅ Focus visible en todos los elementos interactivos
- ✅ Imágenes con alt descriptivo
- ✅ Sin errores de consola
- ✅ Diseño responsive funcional

## 📅 Estimación de Tiempo

| Fase | Tiempo Estimado |
|------|-----------------|
| Creación de estructura CSS | 30 min |
| Migración a variables | 45 min |
| Refactorización componentes | 60 min |
| Mejoras accesibilidad | 45 min |
| Pruebas y ajustes | 30 min |
| **TOTAL** | **3.5 horas** |

## 📚 Referencias DIW

- **Contraste:** WCAG 2.1 AA (4.5:1 texto, 3:1 UI)
- **Navegación:** Tab order lógico, focus visible
- **Semántica:** HTML5 (header, nav, main, footer, section)
- **ARIA:** Labels, describedby, roles cuando necesario
- **Responsive:** Mobile-first, breakpoints estándar
