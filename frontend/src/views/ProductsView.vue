<template>
  <div class="products-container">
    <h1>Catàleg de Productes</h1>
    
    <div class="catalog-layout">
      <!-- Botón Filtros Móvil -->
      <button class="mobile-filters-btn" @click="toggleFilters">
        <span class="material-icons">filter_list</span>
        {{ filtersOpen ? 'Ocultar Filtres' : 'Mostrar Filtres' }}
      </button>

      <!-- Filtros Sidebar -->
      <aside class="filters-sidebar" :class="{ 'filters-sidebar--open': filtersOpen }">
        <div class="filter-group">
          <h3>Cerca</h3>
          <input type="text" v-model="filters.search" placeholder="Nom del producte..." class="filter-input">
        </div>

        <div class="filter-group">
          <h3>Categoria</h3>
          <select v-model="filters.category" class="filter-select">
            <option value="">Totes</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>

        <div class="filter-group">
          <h3>Preu</h3>
          <div class="price-range">
            <input type="number" v-model.number="filters.min_price" placeholder="Min" class="price-input">
            <span>-</span>
            <input type="number" v-model.number="filters.max_price" placeholder="Max" class="price-input">
          </div>
        </div>

        <div class="filter-group sustainability-filter">
          <h3>🌱 Sostenibilitat</h3>
          <label class="checkbox-label">
            <input type="checkbox" v-model="filters.sustainable_only" />
            <span>Només productes eco (Score ≥ 70)</span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" v-model="filters.refurbished_only" />
            <span>♻️ Només reacondicionats</span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" v-model="filters.local_only" />
            <span>🏠 Només proveïdors locals</span>
          </label>
        </div>

        <button @click="resetFilters" class="btn-reset">Netejar Filtres</button>
      </aside>

      <!-- Grid de Productos -->
      <main class="products-main">
        <div v-if="productStore.loading" class="loading">Carregant productes...</div>
        <div v-else-if="productStore.error" class="error">{{ productStore.error }}</div>
        
        <div v-else-if="productStore.products.length === 0" class="no-results">
            No s'han trobat productes amb aquests filtres.
        </div>

        <div v-else>
            <div class="products-grid">
              <ProductCard 
                v-for="product in productStore.products" 
                :key="product.id"
                :product="product"
              />
            </div>

            <!-- Paginación -->
            <div class="pagination-controls" v-if="productStore.pagination.last_page > 1">
                <button 
                    :disabled="productStore.pagination.current_page === 1" 
                    @click="changePage(productStore.pagination.current_page - 1)"
                    class="page-btn"
                >
                    &laquo; Anterior
                </button>
                
                <span class="page-info">
                    Pàgina {{ productStore.pagination.current_page }} de {{ productStore.pagination.last_page }}
                </span>

                <button 
                    :disabled="productStore.pagination.current_page === productStore.pagination.last_page" 
                    @click="changePage(productStore.pagination.current_page + 1)"
                    class="page-btn"
                >
                    Següent &raquo;
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
import ProductCard from '@/components/ProductCard.vue';

const productStore = useProductStore();
const { filters } = storeToRefs(productStore);

const filtersOpen = ref(false);

const toggleFilters = () => {
  filtersOpen.value = !filtersOpen.value;
};

// Categorías basadas en el modelo de Laravel
const categories = [
    'Processadors',
    'Targetes Gràfiques',
    'Plaques Base',
    'Memòria RAM',
    'Emmagatzematge',
    'Fonts Alimentació',
    'Caixes',
    'Refrigeració',
    'Altres'
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

const changePage = (page) => {
    if (page >= 1 && page <= productStore.pagination.last_page) {
        productStore.fetchProducts(page);
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
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
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
  height: 180px;
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

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 40px;
}

.page-btn {
    padding: 8px 16px;
    background: #00A1FF;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.page-btn:disabled {
    background: #3A4150;
    cursor: not-allowed;
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
    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>
