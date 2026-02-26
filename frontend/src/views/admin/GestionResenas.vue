<template>
  <div class="admin-reviews container">
    <div class="header-actions">
      <!-- Título de la sección -->
      <h1>{{ $t('admin.reviews_title') }}</h1>
      <div class="search-bar">
        <!-- Buscador con sugerencias de i18n -->
        <input 
          v-model="search" 
          type="text" 
          :placeholder="$t('admin.search_reviews_ph')" 
          @input="debounceSearch"
          class="form-input"
        />
        <span class="material-icons search-icon">search</span>
      </div>
    </div>

    <!-- Tabla responsiva para moderación de reseñas -->
    <div class="table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ $t('admin.product_label') }}</th>
            <th>{{ $t('admin.user_label') }}</th>
            <th>{{ $t('admin.review_label') }}</th>
            <th>{{ $t('admin.rating_label') }}</th>
            <th>{{ $t('admin.date_label') }}</th>
            <th>{{ $t('admin.actions_label') }}</th>
          </tr>
        </thead>
        <tbody>
          <!-- Listado dinámico de reseñas -->
          <tr v-for="review in reviews" :key="review.id">
            <td data-label="ID">#{{ review.id }}</td>
            <td :data-label="$t('admin.product_label')">
              <div class="item-link" @click="goToProduct(review.product_id)">
                {{ review.product?.name || 'Desconocido' }}
              </div>
            </td>
            <td :data-label="$t('admin.user_label')">{{ review.user?.name || 'Anónimo' }}</td>
            <td :data-label="$t('admin.review_label')">
              <div class="review-text-cell" :title="review.text">
                {{ truncateText(review.text, 50) }}
              </div>
            </td>
            <td :data-label="$t('admin.rating_label')">
              <!-- Visualización de estrellas (resumen) -->
              <div class="rating-stars" :title="review.rating + ' / 5'">
                <span class="material-icons star-filled">star</span>
                <span class="rating-value">{{ review.rating }}</span>
              </div>
            </td>
            <td :data-label="$t('admin.date_label')">{{ formatDate(review.created_at) }}</td>
            <td :data-label="$t('admin.actions_label')" class="actions-cell">
              <!-- Botón para eliminar reseña -->
              <button @click="confirmDelete(review)" class="btn-icon delete" :title="$t('admin.actions_label')">
                <span class="material-icons">delete</span>
              </button>
            </td>
          </tr>
          <!-- Estado vacío si no hay reseñas -->
          <tr v-if="reviews.length === 0 && !loading">
            <td colspan="7" class="empty-state">{{ $t('admin.empty_reviews') }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación de reseñas -->
    <div class="pagination" v-if="totalPages > 1">
      <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="btn-page">
        <span class="material-icons">chevron_left</span>
      </button>
      <span class="page-info">{{ currentPage }} de {{ totalPages }}</span>
      <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="btn-page">
        <span class="material-icons">chevron_right</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import http from '@/services/http';
import { useToastStore } from '@/stores/toast';

/**
 * Gestión de Reseñas: Permite moderar los comentarios de los usuarios sobre productos.
 */

const router = useRouter();
const toast = useToastStore();
const reviews = ref([]);
const loading = ref(false);
const search = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
let debounceTimer = null;

// Obtener reseñas con paginación
const fetchReviews = async (page = 1) => {
  loading.value = true;
  try {
    const response = await http.get('/api/admin/reviews', {
      params: { 
        page,
        search: search.value
      }
    });
    reviews.value = response.data.data;
    currentPage.value = response.data.current_page;
    totalPages.value = response.data.last_page;
  } catch (err) {
    toast.addToast("Error al cargar las reseñas.", "error");
  } finally {
    loading.value = false;
  }
};

// Buscador con retardo para evitar sobrecarga de red
const debounceSearch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchReviews(1);
  }, 500);
};

// Eliminar reseña tras confirmación
const confirmDelete = async (review) => {
  if (!confirm(`¿Estás seguro de eliminar esta reseña?`)) return;

  try {
    await http.delete(`/api/reviews/${review.id}`);
    toast.addToast("Reseña eliminada correctamente.", "success");
    fetchReviews(currentPage.value);
  } catch (err) {
    toast.addToast("Error al eliminar la reseña.", "error");
  }
};

// Navegar a la página del producto para comprobar contexto
const goToProduct = (id) => {
  router.push(`/products/${id}`);
};

// Cambiar de página
const changePage = (page) => {
  fetchReviews(page);
};

// Formatear texto largo con puntos suspensivos
const truncateText = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

// Formato de fecha español
const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

onMounted(() => fetchReviews());
</script>

<style scoped>
.container {
  padding: var(--spacing-xl);
  max-width: var(--max-width-container);
  margin: 0 auto;
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-lg);
  gap: var(--spacing-md);
}

.search-bar {
  position: relative;
  width: 100%;
  max-width: 400px;
}

.search-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-secondary);
  pointer-events: none;
}

.admin-table {
  width: 100%;
  border-collapse: collapse;
  background: var(--bg-card);
  border-radius: var(--radius-md);
  overflow: hidden;
}

.admin-table th, 
.admin-table td {
  padding: var(--spacing-md) var(--spacing-lg);
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.admin-table th {
  background: var(--bg-elevated);
  color: var(--text-secondary);
  font-size: 0.85rem;
  text-transform: uppercase;
}

.item-link {
  color: var(--color-primary);
  cursor: pointer;
  font-weight: 600;
  max-width: 150px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.item-link:hover {
  text-decoration: underline;
}

.rating-stars {
  display: flex;
  align-items: center;
  gap: 4px;
}

.star-filled {
  color: #fbbf24;
  font-size: 1.1rem;
}

.rating-value {
  font-weight: bold;
  font-size: 0.9rem;
}

.review-text-cell {
  max-width: 250px;
  color: var(--text-secondary);
  font-size: 0.9rem;
  font-style: italic;
}

.actions-cell {
  display: flex;
  gap: 8px;
}

.btn-icon {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: var(--text-secondary);
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
}

.btn-icon.delete:hover {
  color: #ff4757;
  border-color: #ff4757;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-top: var(--spacing-xl);
}

.btn-page {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  color: white;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.btn-page:disabled { opacity: 0.3; cursor: not-allowed; }

@media (max-width: 768px) {
  .admin-table, thead, tbody, tr, td {
    display: block;
    width: 100%;
  }
  thead { display: none; }
  tr {
    margin-bottom: 20px;
    background: var(--bg-card);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 10px;
  }
  td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }
  td::before {
    content: attr(data-label);
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.8rem;
    text-transform: uppercase;
  }
}
</style>
