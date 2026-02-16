<template>
  <section v-if="relatedProducts.length > 0" class="related-products">
    <h3>
      <span class="icon">🔗</span>
      También te puede interesar
    </h3>
    
    <div v-if="loading" class="loading">
      Cargando productos relacionados...
    </div>
    
    <div v-else class="products-grid">
      <div v-for="product in relatedProducts" :key="product.id" class="product-card">
        <router-link :to="`/products/${product.id}`" class="card-link">
          <div class="card-image">
            <img :src="getImageUrl(product.image)" :alt="product.name" loading="lazy" />
          </div>
          <div class="card-info">
            <h4>{{ product.name }}</h4>
            <p class="category-tag">{{ product.category }}</p>
            
            <!-- Rating si existe -->
            <div v-if="product.reviews_avg_rating" class="rating">
              <span class="stars">⭐ {{ formatRating(product.reviews_avg_rating) }}</span>
              <span class="review-count">({{ product.reviews_count }} reviews)</span>
            </div>
            
            <p class="price">{{ formatPrice(product.price) }}</p>
          </div>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import http from '@/services/http';

const props = defineProps({
  productId: {
    type: [Number, String],
    required: true
  },
  limit: {
    type: Number,
    default: 4
  }
});

const relatedProducts = ref([]);
const loading = ref(false);

const fetchRelatedProducts = async () => {
  if (!props.productId) return;
  
  loading.value = true;
  try {
    const response = await http.get(`/api/products/${props.productId}/related`, {
      params: { limit: props.limit }
    });
    relatedProducts.value = response.data.data || [];
  } catch (error) {
    console.error('Error fetching related products:', error);
    relatedProducts.value = [];
  } finally {
    loading.value = false;
  }
};

// Cargar al montar
onMounted(() => {
  fetchRelatedProducts();
});

// Recargar si cambia el producto
watch(() => props.productId, () => {
  fetchRelatedProducts();
});

const getImageUrl = (path) => {
  if (!path) return '/img/placeholder.png';
  if (path.startsWith('http')) return path;
  return '/' + path;
};

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2) + ' €';
};

const formatRating = (rating) => {
  return parseFloat(rating).toFixed(1);
};
</script>

<style scoped>
.related-products {
  margin-top: var(--spacing-3xl);
  padding: var(--spacing-2xl) var(--spacing-lg);
  background: var(--bg-body);
  border-top: 2px solid var(--border-color);
}

.related-products h3 {
  text-align: center;
  font-size: var(--font-size-2xl);
  color: var(--color-primary);
  margin-bottom: var(--spacing-2xl);
  font-family: var(--font-headings);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: var(--spacing-sm);
}

.related-products h3 .icon {
  font-size: 1.2em;
}

.loading {
  text-align: center;
  padding: var(--spacing-2xl);
  color: var(--text-secondary);
  font-size: var(--font-size-lg);
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: var(--spacing-lg);
  max-width: 1200px;
  margin: 0 auto;
}

.product-card {
  background: var(--bg-card);
  border-radius: var(--radius-lg);
  border: var(--border-width) solid var(--border-color);
  overflow: hidden;
  transition: transform var(--transition-base), box-shadow var(--transition-base), border-color var(--transition-base);
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  transform: translateY(-5px);
  border-color: var(--color-primary);
  box-shadow: var(--shadow-lg);
}

.card-link {
  text-decoration: none;
  color: inherit;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.card-image {
  height: 180px;
  padding: var(--spacing-md);
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-image img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.card-info {
  padding: var(--spacing-md);
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.card-info h4 {
  font-size: var(--font-size-base);
  margin: 0 0 var(--spacing-xs) 0;
  color: var(--text-primary);
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  line-height: 1.4;
  min-height: 2.8em;
}

.category-tag {
  font-size: var(--font-size-xs);
  color: var(--text-secondary);
  margin-bottom: var(--spacing-sm);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.rating {
  display: flex;
  align-items: center;
  gap: var(--spacing-xs);
  margin-bottom: var(--spacing-sm);
  font-size: var(--font-size-sm);
}

.stars {
  color: var(--color-warning);
  font-weight: var(--font-weight-semibold);
}

.review-count {
  color: var(--text-muted);
  font-size: var(--font-size-xs);
}

.price {
  font-size: var(--font-size-xl);
  color: var(--color-primary);
  font-weight: var(--font-weight-bold);
  margin-top: auto;
  margin-bottom: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .related-products {
    padding: var(--spacing-xl) var(--spacing-md);
  }
  
  .related-products h3 {
    font-size: var(--font-size-xl);
  }
  
  .products-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: var(--spacing-md);
  }
  
  .card-image {
    height: 140px;
  }
}

@media (max-width: 480px) {
  .products-grid {
    grid-template-columns: 1fr;
  }
}
</style>
