<template>
  <div class="favorites-view">
    <div class="container animate-fade-in">
      <h1 class="page-title">Els meus preferits</h1>
      
      <div v-if="wishlistStore.items.length === 0" class="empty-wishlist">
        <span class="material-icons empty-icon">favorite_border</span>
        <p>No tens cap producte a preferits.</p>
        <router-link to="/products" class="btn btn-primary">Descobrir productes</router-link>
      </div>

      <div v-else class="products-grid">
         <div v-for="item in wishlistStore.items" :key="item.id" class="product-card glass-card">
            <!-- Reutilizando estilo básico, idealmente sería el componente ProductCard, 
                 pero aquí simplificamos para mostrar la lista -->
            <div class="image-container">
               <img :src="item.image" :alt="item.name" />
               <button @click="wishlistStore.toggleWishlist(item)" class="remove-fav-btn" title="Treure de preferits">
                 <span class="material-icons">favorite</span>
               </button>
            </div>
            <div class="card-content">
               <h3>{{ item.name }}</h3>
               <p class="price">{{ item.price }}€</p>
               <router-link :to="`/products/${item.id}`" class="btn btn-secondary btn-sm">Veure detall</router-link>
            </div>
         </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useWishlistStore } from '@/stores/wishlist';

const wishlistStore = useWishlistStore();
</script>

<style scoped>
.favorites-view {
  padding: 2rem 0;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.product-card {
  background: var(--color-surface);
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.05);
  transition: transform 0.3s;
  position: relative;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}

.image-container {
  height: 200px;
  position: relative;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.remove-fav-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(0,0,0,0.5);
  border: none;
  color: #ff4d4d;
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
}

.remove-fav-btn:hover {
  transform: scale(1.1);
  background: white;
}

.card-content {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.price {
  font-weight: bold;
  color: var(--color-primary);
  font-size: 1.2rem;
}

.empty-wishlist {
  text-align: center;
  padding: 3rem;
  color: rgba(255, 255, 255, 0.6);
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
  display: block;
}

.glass-card {
  background: rgba(30, 30, 30, 0.6);
  backdrop-filter: blur(10px);
}
</style>
