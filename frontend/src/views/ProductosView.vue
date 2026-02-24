<template>
  <div class="products-container">
    <h1>{{ $t('products.title') }}</h1>
    
    <div class="catalog-layout">
      <!-- Botón Filtros Móvil -->
      <button class="mobile-filters-btn" @click="toggleFilters">
        <span class="material-icons">filter_list</span>
        {{ filtersOpen ? $t('products.hide_filters') : $t('products.show_filters') }}
      </button>

      <!-- Filtros Sidebar -->
      <aside class="filters-sidebar" :class="{ 'filters-sidebar--open': filtersOpen }">
        <div class="filter-group">
          <h3>{{ $t('products.search') }}</h3>
          <input type="text" v-model="filters.search" :placeholder="$t('products.search_ph')" class="filter-input">
        </div>

        <div class="filter-group">
          <h3>{{ $t('products.category') }}</h3>
          <select v-model="filters.category" class="filter-select">
            <option value="">{{ $t('products.all') }}</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>

        <div class="filter-group">
          <h3>{{ $t('products.price') }}</h3>
          <div class="price-range">
            <input type="number" v-model.number="filters.min_price" :placeholder="$t('products.min')" class="price-input">
            <span>-</span>
            <input type="number" v-model.number="filters.max_price" :placeholder="$t('products.max')" class="price-input">
          </div>
        </div>

        <div class="filter-group sustainability-filter">
          <h3>{{ $t('products.sustainability') }}</h3>
          <label class="checkbox-label">
            <input type="checkbox" v-model="filters.sustainable_only" />
            <span>{{ $t('products.eco') }}</span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" v-model="filters.refurbished_only" />
            <span>{{ $t('products.refurbished') }}</span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" v-model="filters.local_only" />
            <span>{{ $t('products.local') }}</span>
          </label>
        </div>

        <button @click="resetFilters" class="btn-reset">{{ $t('products.clear_filters') }}</button>
      </aside>

      <!-- Grid de Productos -->
      <main class="products-main">
        <!-- ⏳ Skeletons de carga -->
        <div v-if="productStore.loading" class="products-grid">
           <div v-for="i in 6" :key="'skeleton'+i" class="product-card skeleton-card">
              <div class="skeleton-img"></div>
              <div class="card-info skeleton-info">
                 <div class="skeleton-line title"></div>
                 <div class="skeleton-line badge"></div>
                 <div class="skeleton-line price"></div>
                 <div class="skeleton-btn"></div>
              </div>
           </div>
        </div>
        
        <div v-else-if="productStore.error" class="error">{{ productStore.error }}</div>
        
        <div v-else-if="productStore.products.length === 0" class="no-results">
            {{ $t('products.no_results') }}
        </div>

        <div v-else>
            <div class="products-grid">
              <TarjetaProducto 
                v-for="product in productStore.products" 
                :key="product.id"
                :product="product"
              />
            </div>

            <!-- Paginación: Mostrar estado -->
            <div class="pagination-overview" v-if="productStore.pagination.total > 0">
                <p class="items-count">
                  {{ $t('products.showing') }} <strong>{{ productStore.products.length }}</strong> {{ $t('products.of') }} <strong>{{ productStore.pagination.total }}</strong> {{ $t('products.products_lower') }}
                </p>
                <!-- Barra de progreso visual -->
                <div class="progress-bar">
                  <div class="progress-fill" :style="{ width: Math.min((productStore.products.length / productStore.pagination.total) * 100, 100) + '%' }"></div>
                </div>
            </div>

            <!-- Botón Cargar Más -->
            <div class="pagination-controls load-more-container" v-if="productStore.pagination.current_page < productStore.pagination.last_page">
                <button 
                    @click="loadMore"
                    class="btn-load-more"
                    :disabled="productStore.loading"
                >
                    <span v-if="productStore.loading" class="material-icons spin">refresh</span>
                    <span>{{ productStore.loading ? $t('products.loading') : $t('products.load_more') }}</span>
                </button>
            </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import { useProductStore } from '@/stores/products';
import { storeToRefs } from 'pinia';
import TarjetaProducto from '@/components/TarjetaProducto.vue';

const productStore = useProductStore();
const { filters } = storeToRefs(productStore);

const filtersOpen = ref(false);

const toggleFilters = () => {
  filtersOpen.value = !filtersOpen.value;
};

// Categorías basadas en el modelo de Laravel
const categories = [
    'Procesadores',
    'Tarjetas Gráficas',
    'Placas Base',
    'Memoria RAM',
    'Almacenamiento',
    'Fuentes Alimentación',
    'Cajas',
    'Refrigeración',
    'Otros'
];

// Debounce timer
let debounceTimer = null;

onMounted(() => {
  productStore.fetchProducts();
});

// Watcher para filtros con debounce
watch(filters, () => {
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        productStore.pagination.current_page = 1; // Reset a página 1 al filtrar
        productStore.fetchProducts(1);
    }, 500); // 500ms debounce
}, { deep: true });

const loadMore = () => {
    const next = productStore.pagination.current_page + 1;
    if (next <= productStore.pagination.last_page) {
        productStore.fetchProducts(next, true);
    }
};

const resetFilters = () => {
    productStore.resetFilters();
};

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

.catalog-layout {
    display: flex;
    gap: 30px;
}

.filters-sidebar {
    width: 250px;
    flex-shrink: 0;
    background: #1A1D24;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #3A4150;
    height: fit-content;
    /* Sticky Sidebar */
    position: sticky;
    top: 90px;
    max-height: calc(100vh - 110px);
    overflow-y: auto;
}

/* Scrollbar personalizado para el sidebar */
.filters-sidebar::-webkit-scrollbar {
  width: 6px;
}
.filters-sidebar::-webkit-scrollbar-track {
  background: transparent;
}
.filters-sidebar::-webkit-scrollbar-thumb {
  background-color: #3A4150;
  border-radius: 10px;
}

.products-main {
    flex-grow: 1;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-group h3 {
    font-size: 1.1em;
    margin-bottom: 10px;
    color: #00A1FF;
}

.filter-input, .filter-select, .price-input {
    width: 100%;
    padding: 10px;
    background: #121418;
    border: 1px solid #3A4150;
    border-radius: 6px;
    color: white;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

.filter-input:focus, .filter-select:focus, .price-input:focus {
    outline: none;
    border-color: #00A1FF;
    box-shadow: 0 0 12px rgba(0, 161, 255, 0.4);
    background: #1A1D24;
}

.price-range {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Filtros de sostenibilidad */
.sustainability-filter {
    background: rgba(16, 185, 129, 0.05);
    padding: 12px;
    border-radius: 8px;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.sustainability-filter h3 {
    color: #10b981;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #10b981;
}

.checkbox-label span {
    font-size: 0.9em;
    color: #EAEAEA;
}

.checkbox-label:hover span {
    color: #10b981;
}

.btn-reset {
    width: 100%;
    padding: 10px;
    background: #3A4150;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-reset:hover {
    background: #4A5160;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 25px;
}

.product-card {
  background: #242833;
  border-radius: 12px;
  border: 1px solid #3A4150;
  overflow: hidden;
  transition: transform 0.2s;
  display: flex;
  flex-direction: column;
}
.product-card:hover {
  transform: translateY(-5px);
  border-color: #00A1FF;
}

.card-image {
  height: 200px;
  padding: 15px;
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
  padding: 15px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.card-info h3 {
  font-size: 1em;
  margin-bottom: 5px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.category-tag {
    font-size: 0.8em;
    color: #888;
    margin-bottom: 10px;
}

.price {
  font-size: 1.3em;
  color: #00A1FF;
  font-weight: bold;
  margin-top: auto;
  margin-bottom: 10px;
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
  font-size: 0.9em;
}

.btn-details:hover {
    background: rgba(0, 161, 255, 0.1);
}

/* --- PAGINACIÓN MODERNA --- */
.pagination-overview {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  margin-top: 50px;
  margin-bottom: 25px;
}

.items-count {
  font-size: 1.05rem;
  color: #9BA3B0;
  margin: 0;
}

.items-count strong {
  color: #EAEAEA;
  font-weight: 600;
}

.progress-bar {
  width: 100%;
  max-width: 300px;
  height: 6px;
  background: #242833;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #00A1FF, #00E4FF);
  border-radius: 10px;
  transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.btn-load-more {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 14px 40px;
  background: transparent;
  color: #00A1FF;
  border: 2px solid #00A1FF;
  border-radius: 30px;
  font-size: 1.05rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-load-more:hover:not(:disabled) {
  background: rgba(0, 161, 255, 0.1);
  box-shadow: 0 4px 15px rgba(0, 161, 255, 0.2);
}

.load-more-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  margin-top: 20px;
  margin-bottom: 40px;
}

.spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  100% { transform: rotate(360deg); }
}

.mobile-filters-btn {
    display: none;
    width: 100%;
    padding: 12px;
    background: #1A1D24;
    color: #00A1FF;
    border: 1px solid #3A4150;
    border-radius: 8px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 20px;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

/* Responsivo */
@media (max-width: 1024px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .catalog-layout {
        flex-direction: column;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .mobile-filters-btn {
        display: flex;
    }

    .filters-sidebar {
        width: 100%;
        display: none;
    }
    
    .filters-sidebar--open {
        display: block;
    }
}

@media (max-width: 480px) {
    .products-container {
      padding: 20px 10px;
    }
    
    .products-grid {
        /* Fuerza siempre mínimo 2 columnas en móviles, distribuyendo equitativamente */
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    h1 {
      font-size: 1.8rem;
      margin-bottom: 20px;
    }
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
