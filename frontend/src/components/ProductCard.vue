<template>
  <article class="product-card" :aria-label="`Producto: ${product.name}`">
    <!-- Imagen del producto con optimización -->
    <div class="product-card__image-wrapper">
      <router-link :to="`/products/${product.id}`" class="product-card__image-link">
        <OptimizedImage
          :src="productImage"
          :alt="`Imagen de ${product.name} - ${product.category}`"
          width="300"
          height="300"
          lazy
          img-class="product-card__image"
        />
      </router-link>
      
      <!-- Overlay Acciones Rápidas -->
      <div class="product-card__actions">
        <button 
          @click.prevent="addToCart" 
          class="action-btn action-btn--cart" 
          :disabled="product.stock <= 0"
          title="Añadir al carrito"
        >
          <span class="material-icons">shopping_cart</span>
        </button>
        <button 
          @click.prevent="toggleFavorite" 
          class="action-btn action-btn--fav"
          :class="{ 'active': isFavorite }"
          title="Añadir a favoritos"
        >
          <span class="material-icons">{{ isFavorite ? 'favorite' : 'favorite_border' }}</span>
        </button>
      </div>
      
      <!-- Badges de sostenibilidad -->
      <div v-if="hasSustainabilityBadges" class="product-card__eco-badges">
        <!-- Eco Score -->
        <span 
          v-if="product.eco_score >= 70" 
          class="eco-badge eco-badge--score"
          :class="ecoScoreClass"
          :aria-label="`Puntuación eco: ${product.eco_score} de 100`"
        >
          {{ ecoScoreLabel }}
        </span>
        
        <!-- Reacondicionado -->
        <span 
          v-if="product.is_refurbished && !compact" 
          class="eco-badge eco-badge--refurbished"
          aria-label="Producto reacondicionado"
          title="Producto reacondicionado profesionalmente"
        >
          ♻️ Reacondicionado
        </span>
        
        <!-- Embalaje Eco -->
        <span 
          v-if="product.has_eco_packaging && !compact" 
          class="eco-badge eco-badge--packaging"
          aria-label="Embalaje reciclado"
          title="Embalaje 100% reciclado y reciclable"
        >
          📦 Embalaje Eco
        </span>
        
        <!-- Proveedor Local -->
        <span 
          v-if="product.is_local_supplier && !compact" 
          class="eco-badge eco-badge--local"
          aria-label="Proveedor local"
          title="Proveedor local - Menor huella de carbono"
        >
          🏠 Local
        </span>
        
        <!-- Reciclable -->
        <span 
          v-if="product.is_recyclable && !product.is_refurbished && !compact" 
          class="eco-badge eco-badge--recyclable"
          aria-label="Materiales reciclables"
          title="Fabricado con materiales reciclables"
        >
          🌱 Reciclable
        </span>
      </div>
    </div>

    <!-- Contenido del producto -->
    <div class="product-card__content">
      <!-- Categoría -->
      <span class="product-card__category">{{ product.category }}</span>
      
      <!-- Nombre -->
      <h3 class="product-card__title">
        <router-link :to="`/products/${product.id}`">
          {{ product.name }}
        </router-link>
      </h3>
      
      <!-- Descripción breve (solo primeras 80 caracteres) -->
      <p v-if="product.description" class="product-card__description">
        {{ truncatedDescription }}
      </p>
      
      <!-- Footer: Precio y Stock -->
      <div class="product-card__footer">
        <div class="product-card__price-section">
          <span class="product-card__price">{{ formattedPrice }}</span>
          
          <!-- Huella de carbono (si disponible) -->
          <span 
            v-if="product.carbon_footprint" 
            class="product-card__carbon"
            :title="`Huella de carbono estimada: ${product.carbon_footprint} kg CO2`"
          >
            🌍 {{ product.carbon_footprint }} kg CO2
          </span>
        </div>
        
        <div class="product-card__stock">
          <span 
            :class="stockClass"  
            :aria-label="stockLabel"
          >
            {{ stockText }}
          </span>
        </div>
      </div>
    </div>
  </article>
</template>

<script>
import OptimizedImage from './OptimizedImage.vue';
import { useCartStore } from '@/stores/cart';
import { useWishlistStore } from '@/stores/wishlist';
import { computed } from 'vue';
import Swal from 'sweetalert2';

/**
 * ProductCard Component
 * 
 * Tarjeta de producto con soporte completo para etiquetas de sostenibilidad ASG.
 * Muestra información del producto, badges eco, y optimiza la carga de imágenes.
 * 
 * @component
 * @example
 * <ProductCard :product="productData" />
 */
export default {
  name: 'ProductCard',
  
  components: {
    OptimizedImage
  },
  
  props: {
    /**
     * Objeto del producto con todos sus datos
     */
    product: {
      type: Object,
      required: true
    },
    /**
     * Si es true, solo muestra los badges esenciales (Eco Score)
     */
    compact: {
      type: Boolean,
      default: true
    }
  },

  setup(props) {
    const cartStore = useCartStore();
    const wishlistStore = useWishlistStore();

    const isFavorite = computed(() => wishlistStore.isInWishlist(props.product.id));

    const addToCart = () => {
      cartStore.addItem(props.product);
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Afegit al carret',
        showConfirmButton: false,
        timer: 1500,
         background: '#1a1a1a',
         color: '#fff'
      });
    };

    const toggleFavorite = () => {
      wishlistStore.toggleWishlist(props.product);
    };

    return {
      addToCart,
      toggleFavorite,
      isFavorite
    };
  },
  
  computed: {
    /**
     * Asegura que la ruta de la imagen sea absoluta si es local
     */
    productImage() {
      const img = this.product.image;
      if (!img) return '/img/placeholder-product.jpg';
      if (img.startsWith('http') || img.startsWith('/')) return img;
      return `/${img}`;
    },

    /**
     * Verifica si el producto tiene badges de sostenibilidad
     */
    hasSustainabilityBadges() {
      if (this.compact) {
        return this.product.eco_score >= 70;
      }
      return this.product.eco_score >= 70 
        || this.product.is_refurbished 
        || this.product.has_eco_packaging
        || this.product.is_local_supplier
        || this.product.is_recyclable;
    },
    
    /**
     * Clase CSS dinámica para el eco score
     */
    ecoScoreClass() {
      if (this.product.eco_score >= 80) return 'eco-badge--excellent';
      if (this.product.eco_score >= 70) return 'eco-badge--very-good';
      return 'eco-badge--good';
    },
    
    /**
     * Etiqueta visual del eco score
     * 🌿 = Excelente (80+)
     * ♻️ = Muy Bueno (70-79)
     * 🌱 = Bueno (60-69, aunque el filtro corta en 70)
     */
    ecoScoreLabel() {
      const score = this.product.eco_score;
      if (score >= 80) return `🌿 ${score}`;
      if (score >= 70) return `♻️ ${score}`;
      return `🌱 ${score}`;
    },
    
    /**
     * Precio formateado en euros
     */
    formattedPrice() {
      return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
      }).format(this.product.price);
    },
    
    /**
     * Descripción truncada a 80 caracteres
     */
    truncatedDescription() {
      if (!this.product.description) return '';
      return this.product.description.length > 80
        ? this.product.description.substring(0, 80) + '...'
        : this.product.description;
    },
    
    /**
     * Clase CSS para el estado del stock
     */
    stockClass() {
      if (this.product.stock > 10) return 'stock-high';
      if (this.product.stock > 0) return 'stock-low';
      return 'stock-out';
    },
    
    /**
     * Texto del stock
     */
    stockText() {
      if (this.product.stock > 10) return 'En stock';
      if (this.product.stock > 0) return `Solo ${this.product.stock} disponibles`;
      return 'Agotado';
    },
    
    /**
     * Label de accesibilidad para el stock
     */
    stockLabel() {
      return `Stock disponible: ${this.product.stock} unidades`;
    }
  }
}
</script>

<style scoped>
/* Tarjeta de producto */
.product-card {
  background: var(--color-surface, #ffffff);
  border-radius: var(--radius-lg, 12px);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  height: 100%;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.product-card__actions {
  position: absolute;
  bottom: 0px;
  right: 0px;
  display: flex;
  gap: 8px;
  padding: 12px;
  transform: translateY(100%);
  transition: transform 0.3s ease;
  z-index: 2;
  background: linear-gradient(0deg, rgba(0,0,0,0.6) 0%, transparent 100%);
  width: 100%;
  justify-content: flex-end;
}

.product-card:hover .product-card__actions {
  transform: translateY(0);
}

.action-btn {
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #333;
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.action-btn:hover:not(:disabled) {
  transform: scale(1.15) translateY(-2px);
  background: white;
  color: var(--color-primary);
  box-shadow: 0 8px 20px rgba(0, 161, 255, 0.3);
}

.action-btn:active:not(:disabled) {
  transform: scale(0.95);
}

.action-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.action-btn--fav.active {
  color: #ef4444;
}

/* Imagen */
.product-card__image-wrapper {
  position: relative;
  aspect-ratio: 4 / 3;
  width: 100%;
  box-sizing: border-box;
  overflow: hidden;
  background: linear-gradient(145deg, #ffffff, #f0f0f0);
  border-radius: 12px 12px 0 0;
  border-bottom: 2px solid #2d3342;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 25px;
}

.product-card__image-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.product-card__image-link :deep(picture) {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.product-card__image {
  width: 100%;
  height: 100%;
  object-fit: contain;
  transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.product-card:hover .product-card__image {
  transform: scale(1.06);
}

/* Badges Eco */
.product-card__eco-badges {
  position: absolute;
  top: 12px;
  left: 12px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  max-width: calc(100% - 24px);
}

.eco-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 10px;
  font-size: 0.75rem;
  font-weight: 600;
  border-radius: var(--radius-sm, 6px);
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(8px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  white-space: nowrap;
}

.eco-badge:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
}

/* Eco Score Variantes */
.eco-badge--score.eco-badge--excellent {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
}

.eco-badge--score.eco-badge--very-good {
  background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
  color: white;
}

.eco-badge--score.eco-badge--good {
  background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%);
  color: white;
}

/* Otros badges */
.eco-badge--refurbished {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: white;
}

.eco-badge--packaging {
  background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
  color: white;
}

.eco-badge--local {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  color: white;
}

.eco-badge--recyclable {
  background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
  color: white;
}

/* Contenido */
.product-card__content {
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex-grow: 1;
}

.product-card__category {
  font-size: 0.75rem;
  text-transform: uppercase;
  color: var(--color-text-muted, #6b7280);
  font-weight: 600;
  letter-spacing: 0.5px;
}

.product-card__title {
  font-size: 1.125rem;
  font-weight: 600;
  margin: 0;
  line-height: 1.4;
}

.product-card__title a {
  color: var(--color-text, #111827);
  text-decoration: none;
  transition: color 0.2s ease;
}

.product-card__title a:hover {
  color: var(--color-primary, #3b82f6);
}

.product-card__description {
  font-size: 0.875rem;
  color: var(--color-text-secondary, #4b5563);
  line-height: 1.5;
  margin: 0;
}

/* Footer */
.product-card__footer {
  margin-top: auto;
  padding-top: 12px;
  border-top: 1px solid var(--color-border, #e5e7eb);
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}

.product-card__price-section {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.product-card__price {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-primary, #3b82f6);
}

.product-card__carbon {
  font-size: 0.7rem;
  color: var(--color-text-muted, #6b7280);
  display: flex;
  align-items: center;
  gap: 2px;
}

.product-card__stock {
  text-align: right;
}

.product-card__stock span {
  font-size: 0.875rem;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: var(--radius-sm, 4px);
}

.stock-high {
  background: #d1fae5;
  color: #065f46;
}

.stock-low {
  background: #fed7aa;
  color: #92400e;
}

.stock-out {
  background: #fee2e2;
  color: #991b1b;
}

/* Responsive */
@media (max-width: 768px) {
  .product-card__price {
    font-size: 1.25rem;
  }
  
  .product-card__title {
    font-size: 1rem;
  }
  
  .eco-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
  }
}
</style>
