<template>
  <div class="product-detail-container">
    <div v-if="productStore.loading" class="loading">Carregant...</div>
    <div v-else-if="productStore.error" class="error">{{ productStore.error }}</div>
    
    <div v-else-if="product" class="detail-wrapper">
       <div class="product-card-glass">
        <div class="product-layout">
            <!-- Imagen Producto -->
            <div class="product-image-section">
                <img :src="getImageUrl(product.image)" :alt="product.name" class="main-image" />
            </div>

            <!-- Info Producto -->
            <div class="product-info-section">
                <h1 class="product-title">{{ product.name }}</h1>
                <p class="product-ref">REF: {{ product.sku || 'N/A' }}</p>
                
                <div class="price-stock-box">
                    <h2 class="product-price">{{ formatPrice(product.price) }}</h2>
                    <span class="stock-badge" :class="{'in-stock': hasStock, 'out-stock': !hasStock}">
                        {{ hasStock ? `En Estoc (${product.stock} u.)` : 'Esgotat' }}
                    </span>
                </div>

                <div class="product-description">
                    <p>{{ product.description }}</p>
                </div>

                <div class="actions-box">
                    <button class="add-cart-btn" :disabled="!hasStock">
                        AFEGIR AL CARRET <span class="material-icons">shopping_cart</span>
                    </button>
                    <button class="wishlist-btn">
                        <span class="material-icons">favorite_border</span>
                    </button>
                </div>

                <button class="review-btn" @click="showReviewModal = true">
                    <span class="material-icons" style="font-size: 1.2em; color: #facc15;">star</span>
                    Afegir Avaluació
                </button>
            </div>
        </div>

        <!-- Sección de Reseñas -->
        <div class="reviews-section">
            <h3>Valoracions: {{ product.name }}</h3>
            <div v-if="!product.reviews || product.reviews.length === 0" class="no-reviews">
                Encara no hi ha valoracions. Sigues el primer!
            </div>
            <div v-else class="reviews-list">
                <div v-for="review in product.reviews" :key="review.id" class="review-item">
                    <div class="review-header">
                        <span class="review-user">{{ review.user ? review.user.name : 'Anònim' }}</span>
                        <div class="review-stars">
                            <span v-for="n in 5" :key="n" :class="{ filled: n <= review.rating }">★</span>
                        </div>
                    </div>
                    <p class="review-comment">{{ review.text }}</p>
                </div>
            </div>
        </div>
       </div>

        <ReviewModal 
            v-if="showReviewModal" 
            :productId="product.id"
            @close="showReviewModal = false"
            @submit="handleReviewSubmit"
        />

        <div class="back-link">
            <router-link to="/products">← Tornar al catàleg</router-link>
        </div>
    </div>
    
    <div v-else class="error">Producte no trobat</div>
  </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue';
import { useProductStore } from '@/stores/products';
import { useRoute } from 'vue-router';
import ReviewModal from '@/components/ReviewModal.vue';

const route = useRoute();
const productStore = useProductStore();
const showReviewModal = ref(false);

const product = computed(() => productStore.currentProduct);
const hasStock = computed(() => product.value?.stock > 0);

onMounted(() => {
  productStore.fetchProduct(route.params.id);
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

const handleReviewSubmit = async (reviewData) => {
    await productStore.addReview(product.value.id, reviewData);
};
</script>

<style scoped>
.product-detail-container {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  padding: 40px 20px;
  color: #EAEAEA;
  min-height: 90vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

/* Tarjeta contenedora principal estilo 'Glass' */
.product-card-glass {
  background: rgba(30, 35, 45, 0.6);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 24px;
  padding: 50px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

.product-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

@media (max-width: 1024px) {
  .product-layout {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  .product-card-glass {
    padding: 30px;
  }
}

.product-image-section {
  display: flex;
  justify-content: center;
  align-items: center;
  background: transparent;
  padding: 20px;
  min-height: 400px;
}

.main-image {
  width: 100%;
  max-width: 450px; /* Tamaño más controlado */
  height: auto;
  max-height: 500px;
  object-fit: contain;
  filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4));
  transition: transform 0.3s ease;
}

.main-image:hover {
  transform: scale(1.05);
}

.product-info-section {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.product-title {
  font-size: 2.5em; /* Tamaño equilibrado */
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 15px;
  color: #ffffff;
}

.product-ref {
  color: #94a3b8;
  font-size: 0.95em;
  margin-bottom: 25px;
  letter-spacing: 1px;
  text-transform: uppercase;
  font-weight: 500;
}

.price-stock-box {
    margin-bottom: 30px;
    display: flex;
    flex-wrap: wrap; /* Para móviles */
    align-items: center;
    gap: 20px;
}

.product-price {
    color: #38bdf8;
    font-size: 2.8em;
    font-weight: 700;
    margin: 0;
}

.stock-badge {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.9em;
    font-weight: 600;
    text-transform: uppercase;
}
.in-stock {
    background: rgba(16, 185, 129, 0.2);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.4);
}
.out-stock {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.4);
}

.product-description {
    font-size: 1.1em;
    line-height: 1.6;
    color: #cbd5e1;
    margin-bottom: 40px;
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 20px;
}

.actions-box {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
}

.add-cart-btn {
    flex: 2;
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    color: white;
    border: none;
    padding: 18px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.1em;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
}
.add-cart-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(14, 165, 233, 0.4);
}
.add-cart-btn:disabled {
    background: #475569;
    cursor: not-allowed;
    box-shadow: none;
    color: #94a3b8;
}

.wishlist-btn {
    flex: 0 0 60px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.wishlist-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: white;
}

.review-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 0.95em;
    transition: color 0.2s;
    padding: 0;
    width: auto;
    display: flex;
    align-items: center;
    gap: 5px;
}
.review-btn:hover {
    color: #fff;
    text-decoration: underline;
}

.reviews-section {
    background: #111827;
    padding: 30px;
    border-radius: 16px;
    border: 1px solid #1f2937;
    margin-top: 40px;
}
.review-item {
    border-bottom: 1px solid #3A4150;
    padding: 15px 0;
}
.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}
.review-stars span.filled {
    color: #f39c12;
}

.back-link {
    margin-top: 20px;
    text-align: center;
}
.back-link a {
    color: #9BA3B0;
    text-decoration: none;
}

.loading, .error, .no-reviews {
    text-align: center;
    padding: 40px;
    color: #9BA3B0;
}
.error {
    color: #e74c3c;
}
</style>
