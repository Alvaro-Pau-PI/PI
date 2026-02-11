<template>
  <div class="products-container">
    <h1>Catàleg de Productes</h1>
    
    <div v-if="productStore.loading" class="loading">Carregant...</div>
    <div v-else-if="productStore.error" class="error">{{ productStore.error }}</div>
    
    <div v-else class="products-grid">
      <div v-for="product in productStore.products" :key="product.id" class="product-card">
        <div class="card-image">
           <img :src="getImageUrl(product.image)" :alt="product.name" />
        </div>
        <div class="card-info">
          <h3>{{ product.name }}</h3>
          <p class="price">{{ formatPrice(product.price) }}</p>
          <div class="actions">
             <router-link :to="'/products/' + product.id" class="btn-details">Veure Detalls</router-link>
             <button class="btn-cart">Afegir</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useProductStore } from '@/stores/products';

const productStore = useProductStore();

onMounted(() => {
  productStore.fetchProducts();
});

const getImageUrl = (path) => {
  if (!path) return '/img/placeholder.png';
  if (path.startsWith('http')) return path;
  return '/' + path; // Asumiendo que path es relativo a public/
};

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2) + ' €';
};
</script>

<style scoped>
.products-container {
  padding: 40px 20px;
  max-width: 1400px;
  margin: 0 auto;
  color: #EAEAEA;
}

h1 {
  text-align: center;
  font-size: 2.5em;
  margin-bottom: 40px;
  background: linear-gradient(135deg, #00A1FF, #00E4FF);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 25px;
  margin-top: 30px;
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
.btn-cart {
  flex: 1;
  padding: 8px;
  background: #00A1FF;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
</style>
