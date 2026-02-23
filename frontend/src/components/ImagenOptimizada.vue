<template>
  <picture>
    <!-- Fuente WebP optimizada (navegadores modernos) -->
    <source 
      v-if="webpSrc" 
      :srcset="webpSrc" 
      type="image/webp"
    >
    
    <!-- Fallback a formato original (navegadores antiguos) -->
    <img
      :src="src"
      :alt="alt"
      :width="width"
      :height="height"
      :loading="lazy ? 'lazy' : 'eager'"
      :class="imgClass"
      @error="handleImageError"
    >
  </picture>
</template>

<script>
/**
 * Componente ImagenOptimizada
 * 
 * Componente para cargar imágenes optimizadas con soporte WebP
 * y lazy loading. Implementa sostenibilidad reduciendo el peso
 * de las transferencias de datos.
 * 
 * @component
 * @example
 * <ImagenOptimizada 
 *   src="/img/product.jpg" 
 *   webp-src="/img/product.webp"
 *   alt="Procesador Intel i9"
 *   width="300"
 *   height="300"
 *   lazy
 * />
 */
export default {
  name: 'ImagenOptimizada',
  
  props: {
    /**
     * Ruta de la imagen original (JPG/PNG)
     */
    src: {
      type: String,
      required: true
    },
    
    /**
     * Ruta de la imagen en formato WebP (opcional pero recomendado)
     * Si no se proporciona, se intenta generar automáticamente
     */
    webpSrc: {
      type: String,
      default: null
    },
    
    /**
     * Texto alternativo para accesibilidad (WCAG)
     */
    alt: {
      type: String,
      required: true
    },
    
    /**
     * Ancho de la imagen en píxeles (para evitar CLS)
     */
    width: {
      type: [Number, String],
      default: null
    },
    
    /**
     * Alto de la imagen en píxeles (para evitar CLS)
     */
    height: {
      type: [Number, String],
      default: null
    },
    
    /**
     * Activar lazy loading nativo del navegador
     */
    lazy: {
      type: Boolean,
      default: true
    },
    
    /**
     * Clases CSS adicionales para la imagen
     */
    imgClass: {
      type: String,
      default: ''
    }
  },
  
  computed: {
    /**
     * Si no se proporciona webpSrc, intenta generarla automáticamente
     * reemplazando la extensión del archivo por .webp
     */
    computedWebpSrc() {
      if (this.webpSrc) {
        return this.webpSrc;
      }
      
      // Intentar generar ruta WebP automáticamente
      if (this.src && (this.src.endsWith('.jpg') || this.src.endsWith('.jpeg') || this.src.endsWith('.png'))) {
        return this.src.replace(/\.(jpg|jpeg|png)$/i, '.webp');
      }
      
      return null;
    }
  },
  
  methods: {
    /**
     * Maneja errores de carga de imagen
     * Útil para debugging y fallback
     */
    handleImageError(event) {
      console.warn(`[ImagenOptimizada] Error cargando imagen: ${this.src}`);
      this.$emit('error', event);
    }
  }
}
</script>

<style scoped>
/* Estilos base para prevenir CLS (Cumulative Layout Shift) */
img {
  display: block;
  max-width: 100%;
  height: auto;
}

/* Para imágenes que aún se están cargando */
img[loading="lazy"] {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

/* Cuando la imagen está cargada, eliminar animación */
img[loading="lazy"]:not([src=""]) {
  animation: none;
  background: none;
}
</style>
