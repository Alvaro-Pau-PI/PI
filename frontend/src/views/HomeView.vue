<template>
  <div class="home-container">
    <section class="banner">
      <BannerCarousel :slides="bannerSlides" />
    </section>

    <main>
      <h2>🏆 Productos Destacados con IA</h2>
      <p>Los componentes más populares y mejor valorados según nuestro algoritmo inteligente.</p>
      
      <div class="catalog-btn-container">
        <router-link to="/products" class="btn-catalog">Ver Catálogo Completo →</router-link>
      </div>

      <section class="productos">
        <!-- Skeletons en lugar de texto -->
        <div v-if="loading" class="products-grid">
           <div v-for="i in 4" :key="'skeleton'+i" class="product-card skeleton-card">
              <div class="skeleton-img"></div>
              <div class="card-info skeleton-info">
                 <div class="skeleton-line title"></div>
                 <div class="skeleton-line badge"></div>
                 <div class="skeleton-line price"></div>
                 <div class="skeleton-btn"></div>
              </div>
           </div>
        </div>
        <div v-else class="products-grid">
           <div v-for="product in featuredProducts" :key="product.id" class="product-card">
              <div class="card-image">
                 <img :src="getImageUrl(product.image)" :alt="product.name" loading="lazy" />
              </div>
              <div class="card-info">
                <h3>{{ product.name }}</h3>
                
                <!-- Rating si existe -->
                <div v-if="product.reviews_avg_rating" class="rating-badge">
                  <span class="stars">⭐ {{ formatRating(product.reviews_avg_rating) }}</span>
                  <span class="review-count">({{ product.reviews_count }})</span>
                </div>
                
                <p class="price">{{ formatPrice(product.price) }}</p>
                <div class="actions">
                   <router-link :to="'/products/' + product.id" class="btn-details">Detalles</router-link>
                </div>
              </div>
           </div>
        </div>
      </section>

      <br>

      <h2>Montaje</h2>
      <video class="video-con-bordes" src="/img/VideoOrdenadorInfinito.mp4" autoplay muted loop playsinline>
        Tu navegador no soporta el elemento de video.
      </video>
    </main>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useProductStore } from '@/stores/products';
import http from '@/services/http';
import BannerCarousel from '@/components/BannerCarousel.vue';

const bannerSlides = [
  { image: '/img/banner1.png', alt: 'Setup Gaming' },
  { image: '/img/banner3.png', alt: 'Tecnología Avanzada' },
  { image: '/img/banner2.png', alt: 'Componentes IA' }
];

const productStore = useProductStore();
const loading = ref(true);
const featuredProducts = ref([]);

const fetchFeaturedProducts = async () => {
  try {
    const response = await http.get('/api/products/featured', {
      params: { limit: 4 }
    });
    featuredProducts.value = response.data.data || [];
  } catch (error) {
    console.error('Error fetching featured products:', error);
    // Fallback: usar primeros productos del store
    featuredProducts.value = productStore.products.slice(0, 4);
  }
};

onMounted(async () => {
    loading.value = true;
    // Resetear filtros para mostrar todas las novedades destacadas
    productStore.filters = {
        search: '',
        category: '',
        min_price: null,
        max_price: null
    };
    // Cargar productos destacados desde endpoint de IA
    await fetchFeaturedProducts();
    loading.value = false;
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
.home-container {
  color: #EAEAEA;
}

h2 {
  text-align: center;
  font-size: 2.5em;
  margin: 40px 0 10px 0;
  background: linear-gradient(135deg, #00A1FF, #00E4FF);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

main p {
  text-align: center;
  color: #9BA3B0;
  margin-bottom: 30px;
  font-size: 1.1em;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 25px;
  padding: 0 40px;
  margin-top: 30px;
  max-width: 1400px;
  margin-left: auto;
  margin-right: auto;
}

/* Diseño responsivo */
@media (max-width: 1200px) {
  .products-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    padding: 0 20px;
  }
}

@media (max-width: 480px) {
  .products-grid {
    grid-template-columns: 1fr;
  }
}

.product-card {
  background: #242833;
  border-radius: 12px;
  border: 1px solid #3A4150;
  overflow: hidden;
  transition: transform 0.2s;
}
.product-card:hover {
  transform: translateY(-5px);
  border-color: #00A1FF;
}
.card-image {
  height: 200px;
  padding: 20px;
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
  padding: 20px;
}
.card-info h3 {
  font-size: 1.1em;
  margin-bottom: 10px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.rating-badge {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 10px;
  font-size: 0.9em;
}

.stars {
  color: #ffc107;
  font-weight: 600;
}

.review-count {
  color: #888;
  font-size: 0.85em;
}

.price {
  font-size: 1.4em;
  color: #00A1FF;
  font-weight: bold;
  margin-bottom: 15px;
}
.actions {
  display: flex;
  gap: 10px;
}
.btn-details {
  flex: 1;
  padding: 8px;
  text-align: center;
  border: 1px solid #00A1FF;
  color: #00A1FF;
  border-radius: 6px;
  text-decoration: none;
}

.btn-details:hover {
  background: rgba(0, 161, 255, 0.1);
}

.catalog-btn-container {
  text-align: center;
  margin: 30px 0;
}

.btn-catalog {
  display: inline-block;
  padding: 12px 30px;
  background: linear-gradient(135deg, #00A1FF, #0088d4);
  color: white;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.05em;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(0, 161, 255, 0.2);
}

.btn-catalog:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 161, 255, 0.4);
}

/* --- SKELETON LOADERS --- */
.skeleton-card {
  border-color: rgba(255,255,255,0.05);
}

.skeleton-img {
  height: 200px;
  background: linear-gradient(90deg, #1A1D24 25%, #242833 50%, #1A1D24 75%);
  background-size: 200% 100%;
  animation: loading-skeleton 1.5s infinite linear;
}

.skeleton-info {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.skeleton-line {
  height: 20px;
  border-radius: 4px;
  background: linear-gradient(90deg, #2a2f3d 25%, #3A4150 50%, #2a2f3d 75%);
  background-size: 200% 100%;
  animation: loading-skeleton 1.5s infinite linear;
}

.skeleton-line.title {
  width: 80%;
  margin-bottom: 5px;
}

.skeleton-line.badge {
  width: 50%;
  height: 15px;
}

.skeleton-line.price {
  width: 40%;
  height: 25px;
  margin-bottom: 10px;
}

.skeleton-btn {
  height: 40px;
  border-radius: 6px;
  background: linear-gradient(90deg, #2a2f3d 25%, #3A4150 50%, #2a2f3d 75%);
  background-size: 200% 100%;
  animation: loading-skeleton 1.5s infinite linear;
}

@keyframes loading-skeleton {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

</style>
