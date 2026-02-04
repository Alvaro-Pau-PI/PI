<template>
  <div class="home-container">
    <section class="banner">
      <img src="/img/banner2.png" alt="Banner Intel Core i9 15a Generación" />
    </section>

    <main>
      <h2>Novedades Destacadas</h2>
      <p>Descubre los componentes más recientes y potentes del mercado.</p>
      
      <div class="catalog-btn-container">
        <router-link to="/products" class="btn-catalog">Ver Catálogo Completo →</router-link>
      </div>

      <section class="productos">
        <div v-if="loading" class="loading">Carregant novetats...</div>
        <div v-else class="products-grid">
           <div v-for="product in featuredProducts" :key="product.id" class="product-card">
              <div class="card-image">
                 <img :src="getImageUrl(product.image)" :alt="product.name" />
              </div>
              <div class="card-info">
                <h3>{{ product.name }}</h3>
                <p class="price">{{ formatPrice(product.price) }}</p>
                <div class="actions">
                   <router-link :to="'/products/' + product.id" class="btn-details">Detalls</router-link>
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
import { onMounted, computed, ref } from 'vue';
import { useProductStore } from '@/stores/products';

const productStore = useProductStore();
const loading = ref(true);

const featuredProducts = computed(() => {
    return productStore.products.slice(0, 4);
});

onMounted(async () => {
    loading.value = true;
    if (productStore.products.length === 0) {
        await productStore.fetchProducts();
    }
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

</style>
