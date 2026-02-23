<template>
  <div class="profile-container">
    <div class="profile-dashboard-layout">
      <!-- Sidebar (Componente Reutilizable) -->
      <UserSidebar activeTab="favorites" />

      <!-- Área de Favoritos Principal -->
      <main class="profile-content">
        <section class="content-section glass-card">
          <header class="section-header">
            <h2 class="card-title"><span class="material-icons title-icon">favorite</span> Mis favoritos</h2>
            <p class="card-desc">Todos los productos que has guardado para más adelante están aquí.</p>
          </header>

          <div v-if="wishlistStore.items.length === 0" class="empty-wishlist">
            <div class="empty-icon-wrapper">
              <span class="material-icons empty-icon">favorite_border</span>
            </div>
            <p class="empty-text">Todavía no tienes ningún producto destacado en tu lista de favoritos.</p>
            <router-link to="/products" class="btn-primary">
              <span class="material-icons">explore</span>
              Descubrir productos
            </router-link>
          </div>

          <div v-else class="products-grid">
             <div v-for="item in wishlistStore.items" :key="item.id" class="product-card inner-glass">
                <div class="image-container">
                   <img :src="getImageUrl(item.image)" :alt="item.name" />
                   <button @click="wishlistStore.toggleWishlist(item)" class="remove-fav-btn" title="Quitar de favoritos">
                     <span class="material-icons">heart_broken</span>
                   </button>
                </div>
                <div class="card-content">
                   <span class="product-category">{{ item.category || 'Producto' }}</span>
                   <h3 class="product-name">{{ item.name }}</h3>
                   <div class="price-action-row">
                     <p class="price">{{ formatPrice(item.price) }}</p>
                     <!-- Se corrige el enrutamiento a la base correcta -->
                     <router-link :to="`/products/${item.slug || item.id}`" class="btn-secondary btn-sm">
                       Detalles <span class="material-icons info-icon">arrow_forward</span>
                     </router-link>
                   </div>
                </div>
             </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { useWishlistStore } from '@/stores/wishlist';
import UserSidebar from '@/components/UserSidebar.vue';

const wishlistStore = useWishlistStore();

const formatPrice = (price) => {
  const numPrice = parseFloat(price);
  return isNaN(numPrice) ? '0,00 €' : `${numPrice.toFixed(2).replace('.', ',')} €`;
};

const getImageUrl = (path) => {
  if (!path) return '/img/placeholder.png';
  if (path.startsWith('http')) return path;
  return '/' + path;
};
</script>

<style scoped>
/* ══════════════════════════════════════════════════════════════
   LAYOUT DASHBOARD (IDÉNTICO AL PERFIL)
   ══════════════════════════════════════════════════════════════ */
.profile-container {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  padding: 40px 20px 100px;
  min-height: calc(100vh - 80px);
}

.profile-dashboard-layout {
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 40px;
  align-items: start;
}

.glass-card {
  background: rgba(30, 34, 43, 0.65);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}



/* ══════════════════════════════════════════════════════════════
   CONTENIDO PRINCIPAL
   ══════════════════════════════════════════════════════════════ */
.profile-content {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.content-section {
  padding: 45px;
}

.section-header {
  margin-bottom: 35px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  padding-bottom: 20px;
}

.card-title {
  font-size: 1.6em;
  color: #fff;
  margin-bottom: 8px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
}

.title-icon {
  color: #FF3B7A;
  font-size: 1.2em;
  background: rgba(255, 51, 102, 0.1);
  padding: 8px;
  border-radius: 10px;
}

.card-desc {
  color: #94a3b8;
  font-size: 0.95em;
  line-height: 1.5;
}

/* ══════════════════════════════════════════════════════════════
   GRID DE PRODUCTOS FAVORITOS
   ══════════════════════════════════════════════════════════════ */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 25px;
}

.inner-glass {
  background: rgba(20, 24, 32, 0.7);
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.05);
  transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  position: relative;
  display: flex;
  flex-direction: column;
}

.inner-glass:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
  border-color: rgba(255, 255, 255, 0.1);
}

.image-container {
  height: 220px;
  position: relative;
  background: #ffffff;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  transition: transform 0.4s ease;
}

.inner-glass:hover .image-container img {
  transform: scale(1.08);
}

.remove-fav-btn {
  position: absolute;
  top: 12px;
  right: 12px;
  background: rgba(255, 59, 122, 0.15);
  backdrop-filter: blur(4px);
  border: 1px solid rgba(255, 59, 122, 0.3);
  color: #FF3B7A;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  z-index: 2;
}

.remove-fav-btn .material-icons {
  font-size: 18px;
}

.remove-fav-btn:hover {
  transform: scale(1.1);
  background: #FF3B7A;
  color: white;
}

.card-content {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex-grow: 1;
}

.product-category {
  font-size: 0.75em;
  color: #0ea5e9;
  text-transform: uppercase;
  font-weight: 700;
  letter-spacing: 1px;
}

.product-name {
  font-size: 1.05em;
  font-weight: 600;
  color: #fff;
  margin: 0;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.price-action-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  padding-top: 15px;
}

.price {
  font-weight: 800;
  color: #00E4FF;
  font-size: 1.25em;
  margin: 0;
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.85em;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 5px;
}

.btn-secondary:hover {
  background: #0ea5e9;
  box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
}

.info-icon {
  font-size: 16px;
}

/* ══════════════════════════════════════════════════════════════
   ESTADOS VACÍOS
   ══════════════════════════════════════════════════════════════ */
.empty-wishlist {
  text-align: center;
  padding: 60px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.empty-icon-wrapper {
  width: 100px;
  height: 100px;
  background: rgba(255, 59, 122, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 25px;
}

.empty-icon {
  font-size: 3.5rem;
  color: #FF3B7A;
}

.empty-text {
  color: #94A3B8;
  font-size: 1.1em;
  margin-bottom: 30px;
  max-width: 400px;
  line-height: 1.6;
}

.btn-primary {
  background: linear-gradient(135deg, #FF3B7A 0%, #FF1E56 100%);
  color: white;
  border: none;
  padding: 16px 30px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 1.05em;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: 1px;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  box-shadow: 0 4px 15px rgba(255, 59, 122, 0.3);
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(255, 59, 122, 0.5);
}

/* ══════════════════════════════════════════════════════════════
   RESPONSIVO
   ══════════════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .profile-dashboard-layout {
    grid-template-columns: 280px 1fr;
    gap: 30px;
  }
}

@media (max-width: 860px) {
  .profile-dashboard-layout {
    grid-template-columns: 1fr;
  }



  .content-section {
    padding: 30px;
  }
}

@media (max-width: 580px) {
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
  }

  .image-container {
    height: 140px;
    padding: 10px;
  }

  .card-content {
    padding: 12px;
  }

  .product-name {
    font-size: 0.9em;
  }

  .price-action-row {
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    padding-top: 10px;
    flex-direction: column; /* Apilar para asegurar que el Euro no baja de forma fea */
  }

  .price {
    font-size: 1.15em;
    white-space: nowrap; 
    text-align: center;
    width: 100%;
  }

  .btn-secondary {
    padding: 8px 12px;
    font-size: 0.8em;
    width: 100%; 
    justify-content: center;
  }
}
</style>
