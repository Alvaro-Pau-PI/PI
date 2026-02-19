<template>
  <div class="product-detail-container">
    <div v-if="productStore.loading" class="loading-state">
      <div class="spinner"></div>
      <p>Carregant producte...</p>
    </div>
    <div v-else-if="productStore.error" class="error-state">
      <span class="material-icons error-icon">error_outline</span>
      <p>{{ productStore.error }}</p>
    </div>
    
    <div v-else-if="product" class="detail-wrapper">
      <!-- Breadcrumb -->
      <nav class="breadcrumb">
        <router-link to="/">Inici</router-link>
        <span class="separator">›</span>
        <router-link to="/products">Productes</router-link>
        <span class="separator">›</span>
        <span class="current">{{ product.name }}</span>
      </nav>

      <!-- Tarjeta principal de producto -->
      <div class="product-card-glass">
        <div class="product-layout">
          <!-- Columna Imagen -->
          <div class="product-image-section">
            <div class="image-frame">
              <img :src="getImageUrl(product.image)" :alt="product.name" class="main-image" />
            </div>
            <!-- Badge Eco en la imagen -->
            <div v-if="product.eco_score >= 70" class="image-eco-badge" :class="ecoScoreClass">
              {{ ecoEmoji }} {{ product.eco_score }}
            </div>
          </div>

          <!-- Columna Info -->
          <div class="product-info-section">
            <!-- Categoría -->
            <span class="product-category">{{ product.category || 'Producte' }}</span>
            
            <h1 class="product-title">{{ product.name }}</h1>
            <p class="product-ref">REF: {{ product.sku || 'N/A' }}</p>
            
            <!-- Valoración media -->
            <div class="rating-summary" v-if="product.reviews && product.reviews.length > 0">
              <div class="stars-inline">
                <span v-for="n in 5" :key="n" class="star" :class="{ filled: n <= averageRating }">★</span>
              </div>
              <span class="rating-count">({{ product.reviews.length }} valoracions)</span>
            </div>

            <div class="price-stock-box">
              <h2 class="product-price">{{ formatPrice(product.price) }}</h2>
              <span class="stock-badge" :class="{'in-stock': hasStock, 'out-stock': !hasStock}">
                <span class="material-icons stock-icon">{{ hasStock ? 'check_circle' : 'cancel' }}</span>
                {{ hasStock ? `En Estoc (${product.stock} u.)` : 'Esgotat' }}
              </span>
            </div>

            <div class="product-description">
              <p>{{ product.description }}</p>
            </div>

            <!-- Botones de acción principales -->
            <div class="actions-box">
              <button class="add-cart-btn" :disabled="!hasStock" @click="addToCart">
                <span class="material-icons btn-icon">shopping_cart</span>
                <span class="btn-label">Afegir al Carret</span>
              </button>
              <button class="wishlist-btn" @click="toggleFavorite" :class="{ 'active': isFavorite }" :title="isFavorite ? 'Treure de preferits' : 'Afegir a preferits'">
                <span class="material-icons">{{ isFavorite ? 'favorite' : 'favorite_border' }}</span>
              </button>
            </div>

            <!-- Características rápidas -->
            <div class="quick-features">
              <div class="feature-item">
                <span class="material-icons">local_shipping</span>
                <span>Enviament gratuït +50€</span>
              </div>
              <div class="feature-item">
                <span class="material-icons">verified_user</span>
                <span>Garantia 2 anys</span>
              </div>
              <div class="feature-item">
                <span class="material-icons">replay</span>
                <span>Devolució 30 dies</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Sección de Sostenibilidad -->
        <div v-if="hasSustainabilityData" class="sustainability-section">
          <h3 class="section-title eco-title">
            <span class="material-icons">eco</span>
            Compromís AlberoPerez Sostenible
          </h3>
          <div class="sustainability-grid">
            <div v-if="product.eco_score" class="eco-card" :class="ecoScoreClass">
              <div class="eco-card-header">
                <span class="eco-emoji">{{ ecoEmoji }}</span>
                <div class="eco-score-circle">
                  <span class="score-value">{{ product.eco_score }}</span>
                  <span class="score-label">/100</span>
                </div>
              </div>
              <span class="eco-rating-text">{{ ecoRatingText }}</span>
            </div>
            <div v-if="product.is_refurbished" class="eco-card badge-refurbished">
              <span class="eco-emoji">♻️</span>
              <span class="eco-card-label">Reacondicionat</span>
            </div>
            <div v-if="product.has_eco_packaging" class="eco-card badge-packaging">
              <span class="eco-emoji">📦</span>
              <span class="eco-card-label">Embalatge Reciclable</span>
            </div>
            <div v-if="product.is_local_supplier" class="eco-card badge-local">
              <span class="eco-emoji">🏠</span>
              <span class="eco-card-label">Proveïdor Local</span>
            </div>
          </div>
          <div v-if="product.carbon_footprint" class="carbon-info">
            <span class="material-icons">public</span>
            Petjada de carboni: <strong>{{ product.carbon_footprint }} kg CO2</strong>
          </div>
        </div>
      </div>

      <!-- Sección de Valoraciones rediseñada -->
      <div class="reviews-section">
        <div class="reviews-header">
          <div class="reviews-title-group">
            <h3 class="section-title">
              <span class="material-icons">rate_review</span>
              Valoracions
            </h3>
            <span class="reviews-count" v-if="product.reviews && product.reviews.length > 0">
              {{ product.reviews.length }} {{ product.reviews.length === 1 ? 'valoració' : 'valoracions' }}
            </span>
          </div>
          <button class="write-review-btn" @click="handleReviewClick" 
            :disabled="userHasReviewed" 
            :class="{ 'already-reviewed': userHasReviewed }">
            <span class="material-icons">{{ userHasReviewed ? 'check_circle' : 'edit' }}</span>
            {{ userHasReviewed ? 'Ja has valorat' : 'Escriure Valoració' }}
          </button>
        </div>

        <!-- Rating Overview -->
        <div v-if="product.reviews && product.reviews.length > 0" class="rating-overview">
          <div class="rating-big">
            <span class="big-number">{{ averageRating.toFixed(1) }}</span>
            <div class="big-stars">
              <span v-for="n in 5" :key="n" class="star" :class="{ filled: n <= Math.round(averageRating) }">★</span>
            </div>
            <span class="total-reviews">{{ product.reviews.length }} valoracions</span>
          </div>
          <div class="rating-bars">
            <div v-for="star in [5,4,3,2,1]" :key="star" class="bar-row">
              <span class="bar-label">{{ star }} ★</span>
              <div class="bar-track">
                <div class="bar-fill" :style="{ width: getStarPercentage(star) + '%' }"></div>
              </div>
              <span class="bar-count">{{ getStarCount(star) }}</span>
            </div>
          </div>
        </div>

        <!-- Lista de reviews -->
        <div v-if="!product.reviews || product.reviews.length === 0" class="no-reviews">
          <span class="material-icons empty-icon">forum</span>
          <p class="empty-title">Encara no hi ha valoracions</p>
          <p class="empty-subtitle">Sigues el primer en compartir la teva experiència amb aquest producte!</p>
          <button v-if="!userHasReviewed" class="write-review-btn primary" @click="handleReviewClick">
            <span class="material-icons">star</span>
            Escriure la primera valoració
          </button>
        </div>
        
        <div v-else class="reviews-list">
          <div v-for="review in product.reviews" :key="review.id" class="review-card">
            <div class="review-card-header">
              <div class="reviewer-info">
                <div class="reviewer-avatar">
                  {{ (review.user ? review.user.name : 'A').charAt(0).toUpperCase() }}
                </div>
                <div class="reviewer-details">
                  <span class="reviewer-name">{{ review.user ? review.user.name : 'Anònim' }}</span>
                  <span class="review-date" v-if="review.created_at">{{ formatDate(review.created_at) }}</span>
                </div>
              </div>
              <div class="review-rating">
                <span v-for="n in 5" :key="n" class="star" :class="{ filled: n <= review.rating }">★</span>
              </div>
            </div>
            <p class="review-text">{{ review.text || review.comment }}</p>
          </div>
        </div>
      </div>

      <!-- Productos Relacionados -->
      <RelatedProducts v-if="product" :product-id="product.id" :limit="4" />

      <!-- Modal de Reseña -->
      <ReviewModal 
        v-if="showReviewModal" 
        :productId="product.id"
        @close="showReviewModal = false"
        @submit="handleReviewSubmit"
      />

      <!-- Enlace de retorno -->
      <div class="back-link">
        <router-link to="/products">
          <span class="material-icons">arrow_back</span>
          Tornar al catàleg
        </router-link>
      </div>
    </div>
    
    <div v-else class="error-state">
      <span class="material-icons error-icon">search_off</span>
      <p>Producte no trobat</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed, ref, watch } from 'vue';
import { useProductStore } from '@/stores/products';
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';
import { useWishlistStore } from '@/stores/wishlist';
import { useRoute, useRouter } from 'vue-router';
import ReviewModal from '@/components/ReviewModal.vue';
import RelatedProducts from '@/components/RelatedProducts.vue';
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();
const productStore = useProductStore();
const authStore = useAuthStore();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();
const showReviewModal = ref(false);

const product = computed(() => productStore.currentProduct);
const hasStock = computed(() => product.value?.stock > 0);
const isFavorite = computed(() => product.value ? wishlistStore.isInWishlist(product.value.id) : false);

// Comprovar si l'usuari actual ja ha valorat aquest producte
const userHasReviewed = computed(() => {
  if (!authStore.user || !product.value?.reviews) return false;
  return product.value.reviews.some(r => r.user_id === authStore.user.id);
});

// Cálculo de media de valoraciones
const averageRating = computed(() => {
  if (!product.value?.reviews || product.value.reviews.length === 0) return 0;
  const sum = product.value.reviews.reduce((acc, r) => acc + (r.rating || 0), 0);
  return sum / product.value.reviews.length;
});

// Conteo de valoraciones por estrella
const getStarCount = (star) => {
  if (!product.value?.reviews) return 0;
  return product.value.reviews.filter(r => r.rating === star).length;
};

// Porcentaje de la barra de progreso por estrella
const getStarPercentage = (star) => {
  if (!product.value?.reviews || product.value.reviews.length === 0) return 0;
  return (getStarCount(star) / product.value.reviews.length) * 100;
};

onMounted(() => {
  productStore.fetchProduct(route.params.id);
});

// Refrescar producto al cambiar la ruta
watch(() => route.params.id, (newId) => {
  if (newId) {
    productStore.fetchProduct(newId);
    window.scrollTo(0, 0);
  }
});

const hasSustainabilityData = computed(() => {
  if (!product.value) return false;
  return product.value.eco_score || 
         product.value.is_refurbished || 
         product.value.has_eco_packaging || 
         product.value.is_local_supplier ||
         product.value.carbon_footprint;
});

const ecoEmoji = computed(() => {
  const score = product.value?.eco_score || 0;
  if (score >= 80) return '🌿';
  if (score >= 70) return '♻️';
  return '🌱';
});

const ecoRatingText = computed(() => {
  const score = product.value?.eco_score || 0;
  if (score >= 80) return 'Excel·lent';
  if (score >= 70) return 'Molt Bo';
  if (score >= 60) return 'Bo';
  return 'Aceptable';
});

const ecoScoreClass = computed(() => {
  const score = product.value?.eco_score || 0;
  if (score >= 80) return 'eco-excellent';
  if (score >= 70) return 'eco-very-good';
  return 'eco-good';
});

const formatPrice = (price) => {
  const numPrice = parseFloat(price);
  return isNaN(numPrice) ? '0.00 €' : `${numPrice.toFixed(2)} €`;
};

const getImageUrl = (path) => {
  if (!path) return '/img/placeholder.png';
  if (path.startsWith('http')) return path;
  return '/' + path;
};

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('ca-ES', { year: 'numeric', month: 'short', day: 'numeric' });
};

const handleReviewClick = () => {
  if (!authStore.user) {
    router.push({ 
      path: '/login', 
      query: { redirect: route.fullPath } 
    });
    return;
  }
  showReviewModal.value = true;
};

const handleReviewSubmit = async (reviewData) => {
  try {
    await productStore.addReview(product.value.id, reviewData);
    showReviewModal.value = false;
  } catch (e) {
    console.error('Error enviant ressenya:', e);
    if (e.response?.status === 409) {
      Swal.fire({
        icon: 'warning',
        title: 'Ja has valorat',
        text: e.response.data.message || 'Només pots deixar una valoració per producte.',
        background: '#1a1f2e',
        color: '#ffffff',
        confirmButtonColor: '#00A1FF'
      });
      showReviewModal.value = false;
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No s\'ha pogut enviar la ressenya. Si us plau, torna-ho a intentar.',
        background: '#1a1f2e',
        color: '#ffffff',
        confirmButtonColor: '#ff4757'
      });
    }
  }
};

const addToCart = () => {
  if (product.value) {
    cartStore.addItem(product.value);
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      background: '#1a1a2e',
      color: '#fff',
      iconColor: '#10b981',
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });
    Toast.fire({ icon: 'success', title: 'Afegit al carret! 🛒' });
  }
};

const toggleFavorite = () => {
  if (product.value) {
    wishlistStore.toggleWishlist(product.value);
  }
};
</script>

<style scoped>
/* ══════════════════════════════════════════════════════════════
   CONTENEDOR PRINCIPAL
   ══════════════════════════════════════════════════════════════ */
.product-detail-container {
  display: block !important;
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  padding: 30px 20px 60px;
  color: #EAEAEA;
  min-height: 80vh;
}

/* ══════════════════════════════════════════════════════════════
   BREADCRUMB
   ══════════════════════════════════════════════════════════════ */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 30px;
  font-size: 0.9em;
}
.breadcrumb a {
  color: #8896AB;
  text-decoration: none;
  transition: color 0.2s;
}
.breadcrumb a:hover { color: #00A1FF; }
.breadcrumb .separator { color: #475569; }
.breadcrumb .current { color: #CBD5E1; }

/* ══════════════════════════════════════════════════════════════
   TARJETA PRINCIPAL GLASS
   ══════════════════════════════════════════════════════════════ */
.detail-wrapper {
  width: 100%;
}

.product-card-glass {
  width: 100%;
  background: rgba(30, 34, 43, 0.65);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 24px;
  padding: 45px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
  box-sizing: border-box;
}

.product-layout {
  display: grid;
  grid-template-columns: 2fr 3fr;
  gap: 50px;
  align-items: start;
  width: 100%;
}

/* ══════════════════════════════════════════════════════════════
   IMAGEN
   ══════════════════════════════════════════════════════════════ */
.product-image-section {
  position: relative;
}

.image-frame {
  background: linear-gradient(145deg, #ffffff, #f0f0f0);
  border-radius: 20px;
  padding: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 380px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.2);
}

.main-image {
  width: 100%;
  max-width: 400px;
  height: auto;
  max-height: 400px;
  object-fit: contain;
  transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.main-image:hover { transform: scale(1.06); }

.image-eco-badge {
  position: absolute;
  top: 15px;
  left: 15px;
  padding: 6px 14px;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.85em;
  backdrop-filter: blur(8px);
}
.image-eco-badge.eco-excellent { background: rgba(16, 185, 129, 0.85); color: #fff; }
.image-eco-badge.eco-very-good { background: rgba(34, 197, 94, 0.85); color: #fff; }
.image-eco-badge.eco-good { background: rgba(132, 204, 22, 0.85); color: #fff; }

/* ══════════════════════════════════════════════════════════════
   INFO DEL PRODUCTO
   ══════════════════════════════════════════════════════════════ */
.product-info-section {
  display: flex;
  flex-direction: column;
}

.product-category {
  display: inline-block;
  background: rgba(0, 161, 255, 0.12);
  color: #00A1FF;
  padding: 4px 14px;
  border-radius: 50px;
  font-size: 0.8em;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  width: fit-content;
  margin-bottom: 12px;
}

.product-title {
  font-size: 2.2em;
  font-weight: 700;
  line-height: 1.15;
  margin-bottom: 8px;
  color: #fff;
}

.product-ref {
  color: #64748B;
  font-size: 0.85em;
  margin-bottom: 16px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  font-weight: 500;
}

/* Valoración media inline */
.rating-summary {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 20px;
}
.stars-inline .star { font-size: 1.1em; color: #374151; }
.stars-inline .star.filled { color: #FBBF24; }
.rating-count { color: #64748B; font-size: 0.85em; }

/* Precio y stock */
.price-stock-box {
  margin-bottom: 20px;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 16px;
}

.product-price {
  color: #00E4FF;
  font-size: 2.6em;
  font-weight: 800;
  margin: 0;
  text-shadow: 0 0 20px rgba(0, 228, 255, 0.15);
}

.stock-badge {
  padding: 6px 14px;
  border-radius: 50px;
  font-size: 0.85em;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
}
.stock-icon { font-size: 1em; }
.in-stock {
  background: rgba(16, 185, 129, 0.15);
  color: #34D399;
  border: 1px solid rgba(16, 185, 129, 0.3);
}
.out-stock {
  background: rgba(239, 68, 68, 0.15);
  color: #F87171;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

/* Descripción */
.product-description {
  font-size: 1em;
  line-height: 1.7;
  color: #94A3B8;
  margin-bottom: 30px;
  border-top: 1px solid rgba(255,255,255,0.06);
  padding-top: 18px;
}

/* ══════════════════════════════════════════════════════════════
   BOTONES PRINCIPALES
   ══════════════════════════════════════════════════════════════ */
.actions-box {
  display: flex;
  gap: 12px;
  margin-bottom: 24px;
}

.add-cart-btn {
  flex: 1;
  background: linear-gradient(135deg, #0EA5E9 0%, #0077B6 100%);
  color: white;
  border: none;
  padding: 16px 28px;
  border-radius: 14px;
  font-weight: 700;
  font-size: 1.05em;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  box-shadow: 0 4px 20px rgba(14, 165, 233, 0.35);
  position: relative;
  overflow: hidden;
}
.add-cart-btn::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
  opacity: 0;
  transition: opacity 0.3s;
}
.add-cart-btn:hover:not(:disabled)::after { opacity: 1; }
.add-cart-btn:hover:not(:disabled) {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(14, 165, 233, 0.5);
}
.add-cart-btn:active:not(:disabled) { transform: translateY(-1px); }
.add-cart-btn:disabled {
  background: #334155;
  cursor: not-allowed;
  box-shadow: none;
  color: #64748B;
}
.btn-icon { font-size: 1.3em; }

.wishlist-btn {
  flex: 0 0 56px;
  height: 56px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #94A3B8;
  border-radius: 14px;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}
.wishlist-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.2);
  color: #fff;
  transform: scale(1.05);
}
.wishlist-btn.active {
  color: #EF4444;
  background: rgba(239, 68, 68, 0.1);
  border-color: rgba(239, 68, 68, 0.3);
}
.wishlist-btn .material-icons { font-size: 1.4em; }

/* ══════════════════════════════════════════════════════════════
   CARACTERÍSTICAS RÁPIDAS
   ══════════════════════════════════════════════════════════════ */
.quick-features {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}
.feature-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.82em;
  color: #64748B;
  background: rgba(255,255,255,0.03);
  padding: 8px 14px;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,0.05);
}
.feature-item .material-icons { font-size: 1.1em; color: #0EA5E9; }

/* ══════════════════════════════════════════════════════════════
   SOSTENIBILIDAD
   ══════════════════════════════════════════════════════════════ */
.sustainability-section {
  margin-top: 40px;
  padding-top: 30px;
  border-top: 1px solid rgba(255,255,255,0.06);
}

.section-title {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.2em;
  font-weight: 700;
  margin-bottom: 20px;
  color: #CBD5E1;
}
.section-title .material-icons { font-size: 1.3em; }
.eco-title { color: #34D399; }
.eco-title .material-icons { color: #34D399; }

.sustainability-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 12px;
  margin-bottom: 16px;
}

.eco-card {
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 14px;
  padding: 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 8px;
  transition: transform 0.2s, border-color 0.2s;
}
.eco-card:hover {
  transform: translateY(-2px);
  border-color: rgba(16, 185, 129, 0.3);
}
.eco-emoji { font-size: 1.8em; }
.eco-card-label { font-size: 0.85em; font-weight: 600; color: #94A3B8; }

.eco-card-header {
  display: flex;
  align-items: center;
  gap: 12px;
}
.eco-score-circle {
  display: flex;
  align-items: baseline;
  gap: 2px;
}
.score-value { font-size: 1.8em; font-weight: 800; color: #34D399; }
.score-label { font-size: 0.85em; color: #64748B; }
.eco-rating-text { font-size: 0.82em; font-weight: 600; color: #34D399; text-transform: uppercase; letter-spacing: 0.5px; }

.eco-excellent { border-color: rgba(16, 185, 129, 0.25); }
.eco-very-good { border-color: rgba(34, 197, 94, 0.25); }
.eco-good { border-color: rgba(132, 204, 22, 0.25); }

.badge-refurbished { border-color: rgba(14, 165, 233, 0.25); }
.badge-refurbished .eco-card-label { color: #38BDF8; }
.badge-packaging { border-color: rgba(168, 85, 247, 0.25); }
.badge-packaging .eco-card-label { color: #A78BFA; }
.badge-local { border-color: rgba(245, 158, 11, 0.25); }
.badge-local .eco-card-label { color: #FBBF24; }

.carbon-info {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.85em;
  color: #64748B;
}
.carbon-info .material-icons { color: #0EA5E9; font-size: 1.1em; }

/* ══════════════════════════════════════════════════════════════
   SECCIÓN DE VALORACIONES
   ══════════════════════════════════════════════════════════════ */
.reviews-section {
  background: rgba(30, 34, 43, 0.65);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.08);
  padding: 40px;
  border-radius: 24px;
  margin-top: 40px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.reviews-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 16px;
}

.reviews-title-group {
  display: flex;
  align-items: center;
  gap: 12px;
}
.reviews-title-group .section-title { margin-bottom: 0; }

.reviews-count {
  background: rgba(0, 161, 255, 0.1);
  color: #38BDF8;
  padding: 4px 12px;
  border-radius: 50px;
  font-size: 0.8em;
  font-weight: 600;
}

/* Botón Escribir Valoración */
.write-review-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  background: transparent;
  border: 2px solid rgba(251, 191, 36, 0.4);
  color: #FBBF24;
  border-radius: 12px;
  font-weight: 600;
  font-size: 0.92em;
  cursor: pointer;
  transition: all 0.3s ease;
}
.write-review-btn:hover {
  background: rgba(251, 191, 36, 0.1);
  border-color: #FBBF24;
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(251, 191, 36, 0.2);
}
.write-review-btn.primary {
  background: linear-gradient(135deg, #FBBF24 0%, #F59E0B 100%);
  color: #1E293B;
  border: none;
  padding: 14px 28px;
  font-size: 1em;
  box-shadow: 0 4px 16px rgba(251, 191, 36, 0.3);
}
.write-review-btn.primary:hover {
  box-shadow: 0 8px 28px rgba(251, 191, 36, 0.45);
}
.write-review-btn .material-icons { font-size: 1.15em; }

/* Botón deshabilitado cuando el usuario ya ha valorado */
.write-review-btn.already-reviewed,
.write-review-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  border-color: #34D399;
  color: #34D399;
  background: rgba(52, 211, 153, 0.08);
}
.write-review-btn.already-reviewed:hover {
  transform: none;
  box-shadow: none;
}

/* Rating Overview (resumen con barras) */
.rating-overview {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 40px;
  margin-bottom: 32px;
  padding-bottom: 28px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}

.rating-big {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-width: 120px;
}
.big-number {
  font-size: 3.5em;
  font-weight: 800;
  color: #fff;
  line-height: 1;
}
.big-stars { margin: 8px 0; }
.big-stars .star { font-size: 1.2em; color: #374151; }
.big-stars .star.filled { color: #FBBF24; }
.total-reviews { font-size: 0.82em; color: #64748B; }

.rating-bars { display: flex; flex-direction: column; gap: 8px; justify-content: center; }
.bar-row {
  display: grid;
  grid-template-columns: 40px 1fr 30px;
  align-items: center;
  gap: 10px;
}
.bar-label { font-size: 0.82em; color: #94A3B8; text-align: right; white-space: nowrap; }
.bar-track {
  height: 8px;
  background: rgba(255,255,255,0.06);
  border-radius: 10px;
  overflow: hidden;
}
.bar-fill {
  height: 100%;
  background: linear-gradient(90deg, #FBBF24, #F59E0B);
  border-radius: 10px;
  transition: width 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.bar-count { font-size: 0.8em; color: #64748B; }

/* Estado vacío de reviews */
.no-reviews {
  text-align: center;
  padding: 50px 20px;
}
.empty-icon { font-size: 3.5em; color: #334155; margin-bottom: 12px; }
.empty-title { font-size: 1.2em; font-weight: 600; color: #94A3B8; margin-bottom: 6px; }
.empty-subtitle { font-size: 0.9em; color: #64748B; margin-bottom: 24px; }

/* Tarjetas de review individuales */
.reviews-list { display: flex; flex-direction: column; gap: 16px; }

.review-card {
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 16px;
  padding: 20px 24px;
  transition: border-color 0.2s;
}
.review-card:hover { border-color: rgba(255,255,255,0.12); }

.review-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}
.reviewer-info { display: flex; align-items: center; gap: 12px; }
.reviewer-avatar {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: linear-gradient(135deg, #0EA5E9, #6366F1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1em;
  color: #fff;
}
.reviewer-details { display: flex; flex-direction: column; }
.reviewer-name { font-weight: 600; font-size: 0.95em; color: #E2E8F0; }
.review-date { font-size: 0.78em; color: #64748B; }
.review-rating .star { font-size: 1em; color: #374151; }
.review-rating .star.filled { color: #FBBF24; }

.review-text {
  font-size: 0.95em;
  line-height: 1.6;
  color: #94A3B8;
}

/* ══════════════════════════════════════════════════════════════
   ENLACE DE RETORNO
   ══════════════════════════════════════════════════════════════ */
.back-link {
  margin-top: 30px;
  text-align: center;
}
.back-link a {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #64748B;
  text-decoration: none;
  font-size: 0.9em;
  transition: color 0.2s;
}
.back-link a:hover { color: #00A1FF; }
.back-link .material-icons { font-size: 1.1em; }

/* ══════════════════════════════════════════════════════════════
   ESTADOS DE CARGA / ERROR
   ══════════════════════════════════════════════════════════════ */
.loading-state, .error-state {
  text-align: center;
  padding: 80px 20px;
  color: #64748B;
}
.spinner {
  width: 40px; height: 40px;
  border: 4px solid rgba(255,255,255,0.1);
  border-top-color: #0EA5E9;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 16px;
}
@keyframes spin { to { transform: rotate(360deg); } }
.error-icon { font-size: 3em; color: #EF4444; margin-bottom: 10px; }

/* ══════════════════════════════════════════════════════════════
   RESPONSIVE
   ══════════════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .product-layout { grid-template-columns: 1fr; gap: 30px; }
  .product-card-glass { padding: 28px; }
  .rating-overview { grid-template-columns: 1fr; gap: 24px; }
  .rating-big { flex-direction: row; gap: 16px; min-width: auto; }
  .big-number { font-size: 2.5em; }
}

@media (max-width: 640px) {
  .product-title { font-size: 1.6em; }
  .product-price { font-size: 2em; }
  .reviews-section { padding: 24px; }
  .reviews-header { flex-direction: column; align-items: flex-start; }
  .quick-features { flex-direction: column; }
  .sustainability-grid { grid-template-columns: 1fr 1fr; }
}
</style>
