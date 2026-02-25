<template>
  <article class="product-card" :class="{ 'has-active-offer': hasOffer }" :aria-label="`Producto: ${product.name}`">
    <!-- Imagen del producto con optimización -->
    <div class="product-card__image-wrapper" ref="wrapperRef" @mousemove="handleMouseMove" @mouseleave="handleMouseLeave">
      <router-link :to="`/products/${product.id}`" class="product-card__image-link">
        <ImagenOptimizada
          :src="currentImageSrc"
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
      
      <!-- Badge de Oferta Principal -->
      <div v-if="hasOffer" class="product-card__offer-badges">
        <span class="discount-pill">-{{ discountPercentage }}%</span>
        <span v-if="expiringSoon" class="expiring-pill" title="Oferta por tiempo limitado">⏳ ¡Termina pronto!</span>
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
      
      <!-- PiePagina: Precio y Stock -->
      <div class="product-card__footer">
        <div class="product-card__price-section">
          <div class="price-wrapper">
            <span v-if="hasOffer" class="product-card__price-original">{{ formattedOriginalPrice }}</span>
            <span class="product-card__price" :class="{'price-discounted': hasOffer}">{{ formattedEffectivePrice }}</span>
          </div>
          
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
import ImagenOptimizada from './ImagenOptimizada.vue';
import { useCartStore } from '@/stores/cart';
import { useWishlistStore } from '@/stores/wishlist';
import { computed, ref } from 'vue';
import Swal from 'sweetalert2';
import { isOfferValid, getEffectivePrice, getDiscountPercentage, isExpiringSoon } from '@/utils/offers';

/**
 * TarjetaProducto Component
 * 
 * Tarjeta de producto con soporte completo para etiquetas de sostenibilidad ASG.
 * Muestra información del producto, badges eco, y optimiza la carga de imágenes.
 * 
 * @component
 * @example
 * <TarjetaProducto :product="productData" />
 */
export default {
  name: 'TarjetaProducto',
  
  components: {
    ImagenOptimizada
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

    const hasOffer = computed(() => isOfferValid(props.product));
    const effectivePrice = computed(() => getEffectivePrice(props.product));
    const discountPercentage = computed(() => getDiscountPercentage(props.product));
    const expiringSoon = computed(() => isExpiringSoon(props.product));

    const isFavorite = computed(() => wishlistStore.isInWishlist(props.product.id));

    // AliExpress style image scrubbing hover effect
    const hoverIndex = ref(0);
    const wrapperRef = ref(null);

    const galleryImages = computed(() => {
      const images = [];
      if (props.product.image) images.push(props.product.image);
      
      if (props.product.images && Array.isArray(props.product.images)) {
        props.product.images.forEach(img => {
          if (img && !images.includes(img)) images.push(img);
        });
      } else if (props.product.name) {
        const knownExtraImages = [
          "AMD Ryzen 5 7600X-2.webp", "AMD Ryzen 5 7600X-3.webp",
          "ASUS Dual GeForce RTX 4070 Super-2.webp", "ASUS Dual GeForce RTX 4070 Super-3.webp", "ASUS Dual GeForce RTX 4070 Super-4.webp", "ASUS Dual GeForce RTX 4070 Super-5.webp",
          "ASUS TUF GAMING B650-PLUS WIFI-2.jpg", "ASUS TUF GAMING B650-PLUS WIFI-3.jpg", "ASUS TUF GAMING B650-PLUS WIFI-4.jpg",
          "CPU-AMD-7800X3D-2.webp", "Corsair 4000D Airflow Cristal Templado-2.webp", "Corsair 4000D Airflow Cristal Templado-3.jpg",
          "Corsair Vengeance DDR5 32GB (2x16GB)-2.webp", "Corsair Vengeance DDR5 32GB (2x16GB)-3.webp",
          "Intel Core i5-13600K-2.webp", "Intel Core i9-14900K-2.webp",
          "Kingston FURY Beast DDR4 16GB (2x8GB)-2.webp", "Kingston FURY Beast DDR4 16GB (2x8GB)-3.webp",
          "MSI MPG A1000G PCIE5 1000W 80 Plus Gold-2.webp", "MSI MPG A1000G PCIE5 1000W 80 Plus Gold-3.webp",
          "Samsung 990 PRO 2TB-2.webp", "Samsung 990 PRO 2TB-3.webp", "Samsung 990 PRO 2TB-4.webp",
          "WD_BLACK SN850X 1TB-2.webp", "WD_BLACK SN850X 1TB-3.webp"
        ];
        const extras = knownExtraImages.filter(img => img.startsWith(props.product.name + '-'));
        extras.forEach(img => {
          const fullPath = 'img/productos/' + img;
          if (!images.includes(fullPath)) images.push(fullPath);
        });
        if (props.product.sku === 'CPU-AMD-7800X3D' || props.product.name.includes('7800X3D')) {
          if (!images.includes('img/productos/CPU-AMD-7800X3D-2.webp')) {
            images.push('img/productos/CPU-AMD-7800X3D-2.webp');
          }
        }
      }
      return images.length > 0 ? images : ['/img/placeholder-product.jpg'];
    });

    const currentImageSrc = computed(() => {
      const img = galleryImages.value[hoverIndex.value] || galleryImages.value[0];
      if (img.startsWith('http')) return img;
      const baseUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      return img.startsWith('/') ? `${baseUrl}${img}` : `${baseUrl}/${img}`;
    });

    const handleMouseMove = (e) => {
      const images = galleryImages.value;
      if (images.length <= 1 || !wrapperRef.value) return;
      
      const rect = wrapperRef.value.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const width = rect.width;
      
      const segment = width / images.length;
      let index = Math.floor(x / segment);
      if (index >= images.length) index = images.length - 1;
      if (index < 0) index = 0;
      
      hoverIndex.value = index;
    };

    const handleMouseLeave = () => {
      hoverIndex.value = 0;
    };

    const addToCart = () => {
      cartStore.addItem(props.product);
      Swal.fire({
        toast: true,
        position: 'bottom-end',
        icon: 'success',
        title: 'Añadido al carrito',
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
      isFavorite,
      wrapperRef,
      handleMouseMove,
      handleMouseLeave,
      currentImageSrc,
      galleryImages,
      hasOffer,
      effectivePrice,
      discountPercentage,
      expiringSoon
    };
  },
  
  computed: {
    /**
     * Asegura que la ruta de la imagen sea absoluta si es local
     * (Retenido temporalmente por retrocompatibilidad lógica)
     */
    productImage() {
      return this.currentImageSrc;
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
     * Precio formateado en euros (Original)
     */
    formattedOriginalPrice() {
      return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
      }).format(this.product.price);
    },

    /**
     * Precio formateado en euros (Efectivo / Oferta)
     */
    formattedEffectivePrice() {
      return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR'
      }).format(this.effectivePrice);
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

.product-card.has-active-offer {
  border: 1px solid rgba(255, 107, 129, 0.3);
  box-shadow: 0 4px 15px rgba(255, 71, 87, 0.1);
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

.action-btn--fav.active .material-icons {
  animation: heartbeat 0.6s ease-out forwards;
}

@keyframes heartbeat {
  0% { transform: scale(1); }
  25% { transform: scale(1.4); }
  50% { transform: scale(1); }
  75% { transform: scale(1.2); }
  100% { transform: scale(1); }
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
  width: 80%;
  height: 80%;
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
  color: var(--color-primary, #00A1FF); /* Blue by default */
  text-decoration: none;
  transition: color 0.2s ease;
}

.product-card__title a:hover {
  color: #005f99; /* Darker blue on hover */
}

.product-card__description {
  font-size: 0.875rem;
  color: var(--color-text-secondary, #4b5563);
  line-height: 1.5;
  margin: 0;
}

/* PiePagina */
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

.price-wrapper {
  display: flex;
  flex-direction: column;
  line-height: 1.2;
}

.product-card__price-original {
  font-size: 0.9rem;
  color: var(--color-text-muted, #9ca3af);
  text-decoration: line-through;
}

.product-card__price {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-primary, #3b82f6);
}

.product-card__price.price-discounted {
  color: #ff4757;
}

/* Offer Badges */
.product-card__offer-badges {
  position: absolute;
  top: 12px;
  right: 12px;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 6px;
  z-index: 2;
}

.discount-pill {
  background: linear-gradient(135deg, #ff4757, #ff6b81);
  color: white;
  font-weight: 800;
  font-size: 0.85rem;
  padding: 4px 10px;
  border-radius: 20px;
  box-shadow: 0 4px 10px rgba(255, 71, 87, 0.3);
  animation: pulse-soft 2s infinite;
}

.expiring-pill {
  background: rgba(0, 0, 0, 0.75);
  backdrop-filter: blur(4px);
  color: #ffeb3b;
  font-size: 0.7rem;
  font-weight: bold;
  padding: 3px 8px;
  border-radius: 4px;
  border: 1px solid rgba(255, 235, 59, 0.3);
}

@keyframes pulse-soft {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
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
  
  .product-card__footer {
    flex-wrap: wrap; 
    gap: 10px;
    align-items: center;
  }

  .product-card__price-section {
    width: 100%;
    flex-direction: row;
    justify-content: space-between;
    align-items: baseline;
  }

  .product-card__stock {
    width: 100%;
    text-align: left;
  }

  .eco-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
  }
}
</style>
