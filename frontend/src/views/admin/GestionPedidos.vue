<template>
  <div class="admin-orders container">
    <div class="header-actions">
      <!-- Título de la sección de pedidos -->
      <h1>{{ $t('admin.orders_title') }}</h1>
      
      <div class="filters-wrap">
        <!-- Filtro por estado del pedido -->
        <div class="filter-group">
          <label>{{ $t('admin.role_label') }}:</label>
          <select v-model="statusFilter" @change="fetchOrders(1)" class="form-input mini">
            <option value="">Todos</option>
            <option value="pending">{{ $t('admin.status_pending') }}</option>
            <option value="confirmed">{{ $t('admin.status_confirmed') }}</option>
            <option value="shipped">{{ $t('admin.status_shipped') }}</option>
            <option value="delivered">{{ $t('admin.status_delivered') }}</option>
            <option value="cancelled">{{ $t('admin.status_cancelled') }}</option>
          </select>
        </div>

        <!-- Buscador por número de pedido o nombre de cliente -->
        <div class="search-bar">
          <input 
            v-model="search" 
            type="text" 
            :placeholder="$t('admin.search_orders_ph')" 
            @input="debounceSearch"
            class="form-input"
          />
          <span class="material-icons search-icon">search</span>
        </div>
      </div>
    </div>

    <!-- Tabla dináminca de pedidos -->
    <div class="table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID / Ref</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- Recorrido de los pedidos obtenidos de la API -->
          <tr v-for="order in orders" :key="order.id" :class="{'row-pending': order.status === 'pending'}">
            <td data-label="ID / Ref">
              <span class="order-number">#{{ order.id }}</span>
              <span class="order-payment">{{ order.payment_method }}</span>
            </td>
            <td data-label="Cliente">
              <div class="client-info">
                <span class="client-name">{{ order.user?.name || 'Invitado' }}</span>
                <span class="client-email">{{ order.email }}</span>
              </div>
            </td>
            <td data-label="Fecha">{{ formatDate(order.created_at) }}</td>
            <td data-label="Total" class="total-cell">{{ order.total }}€</td>
            <td data-label="Estado">
              <span class="status-badge" :class="order.status">
                {{ $t(`admin.status_${order.status}`) }}
              </span>
            </td>
            <td data-label="Acciones">
              <div class="action-buttons">
                <!-- Botón de confirmar pedido -->
                <button 
                  v-if="order.status === 'pending'" 
                  @click="updateStatus(order, 'confirmed')" 
                  class="action-btn confirm-btn"
                  title="Confirmar Pedido"
                >
                  <span class="material-icons">check_circle</span>
                </button>
                <!-- Botón de marcar como enviado -->
                <button 
                  v-if="order.status === 'confirmed'" 
                  @click="updateStatus(order, 'shipped')" 
                  class="action-btn ship-btn"
                  title="Marcar como Enviado"
                >
                  <span class="material-icons">local_shipping</span>
                </button>
                <!-- Botón de marcar como entregado -->
                <button 
                  v-if="order.status === 'shipped'" 
                  @click="updateStatus(order, 'delivered')" 
                  class="action-btn deliver-btn"
                  title="Marcar como Entregado"
                >
                  <span class="material-icons">task_alt</span>
                </button>
                <!-- Botón de cancelar pedido -->
                <button 
                  v-if="['pending', 'confirmed'].includes(order.status)" 
                  @click="confirmCancel(order)" 
                  class="action-btn cancel-btn"
                  title="Cancelar Pedido"
                >
                  <span class="material-icons">cancel</span>
                </button>
              </div>
            </td>
          </tr>
          <!-- Estado si no hay datos -->
          <tr v-if="orders.length === 0 && !loading">
            <td colspan="6" class="empty-state">No se encontraron pedidos.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación dinámica -->
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
import http from '@/services/http';
import { useToastStore } from '@/stores/toast';

/**
 * Gestión de Pedidos: Flujo de estados (pendiente -> confirmado -> enviado -> entregado).
 */

const toast = useToastStore();
const orders = ref([]);
const loading = ref(false);
const search = ref('');
const statusFilter = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
let debounceTimer = null;

// Consultar pedidos con filtros y paginación
const fetchOrders = async (page = 1) => {
  loading.value = true;
  try {
    const params = { page };
    if (search.value) params.search = search.value;
    if (statusFilter.value) params.status = statusFilter.value;

    const response = await http.get('/api/admin/orders', { params });
    orders.value = response.data.data;
    currentPage.value = response.data.current_page;
    totalPages.value = response.data.last_page;
  } catch (err) {
    toast.addToast("Error al cargar los pedidos.", "error");
  } finally {
    loading.value = false;
  }
};

// Buscador optimizado para evitar peticiones redundantes
const debounceSearch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchOrders(1);
  }, 500);
};

// Cambiar el estado de un pedido en el servidor
const updateStatus = async (order, newStatus) => {
  try {
    const response = await http.patch(`/api/admin/orders/${order.id}/status`, { status: newStatus });
    toast.addToast(`Pedido #${order.id} actualizado a ${newStatus}.`, "success");
    // Actualización local para reflejar el cambio sin recargar todo
    const index = orders.value.findIndex(o => o.id === order.id);
    if (index !== -1) orders.value[index].status = newStatus;
  } catch (err) {
    toast.addToast("Error al actualizar el estado del pedido.", "error");
  }
};

// Confirmar cancelación del pedido
const confirmCancel = (order) => {
  if (confirm(`¿Estás seguro de cancelar el pedido #${order.id}?`)) {
    updateStatus(order, 'cancelled');
  }
};

// Cambiar de página
const changePage = (page) => {
  fetchOrders(page);
};

// Formato de fecha para humanos (español)
const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });
};

onMounted(() => fetchOrders());
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

.filters-wrap {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 8px;
}

.filter-group label {
  color: var(--text-secondary);
  font-size: 0.9rem;
}

.form-input.mini {
  width: auto;
  padding: 8px 12px;
}

.search-bar {
  position: relative;
  min-width: 250px;
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

.row-pending {
  border-left: 3px solid #fbbf24;
}

.order-number {
  font-weight: 700;
  color: var(--color-primary);
  display: block;
}

.order-payment {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.client-info {
  display: flex;
  flex-direction: column;
}

.client-name {
  font-weight: 600;
  color: var(--text-primary);
}

.client-email {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.total-cell {
  font-weight: 700;
  color: #00D4AA;
}

.status-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.status-badge.pending { background: rgba(251, 191, 36, 0.15); color: #fbbf24; }
.status-badge.confirmed { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.status-badge.shipped { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
.status-badge.delivered { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.status-badge.cancelled { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

.action-buttons {
  display: flex;
  gap: 6px;
}

.action-btn {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: var(--text-secondary);
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
  display: flex;
  align-items: center;
}

.action-btn:hover {
  background: rgba(255, 255, 255, 0.1);
}

.confirm-btn:hover { color: #3b82f6; border-color: #3b82f6; }
.ship-btn:hover { color: #8b5cf6; border-color: #8b5cf6; }
.deliver-btn:hover { color: #10b981; border-color: #10b981; }
.cancel-btn:hover { color: #ef4444; border-color: #ef4444; }

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

@media (max-width: 900px) {
  .header-actions { flex-direction: column; align-items: flex-start; }
}

@media (max-width: 768px) {
  .admin-table, thead, tbody, tr, td { display: block; width: 100%; }
  thead { display: none; }
  tr {
    margin-bottom: 16px;
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
