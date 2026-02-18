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
      <ProductCard 
        v-for="product in relatedProducts" 
        :key="product.id" 
        :product="product"
        :compact="true"
      />
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import http from '@/services/http';
import ProductCard from './ProductCard.vue';

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
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: var(--spacing-lg);
  max-width: 1200px;
  margin: 0 auto;
}

.product-card {
  flex: 0 1 280px; /* Base width 280px, allow shrinking but not growing beyond max-width */
  max-width: 300px; /* Opcional: limitar ancho máximo */
  width: 100%;
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

/* Legacy styles removed: .card-link, .card-image, .card-info, etc. are now inside ProductCard component */

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
