<template>
  <div class="admin-orders">
    <div class="admin-container">
      <div class="header-section">
        <h1>📦 Gestió de Comandes</h1>
        <p class="header-subtitle">Administra i realitza el seguiment de totes les comandes</p>
      </div>

      <!-- Filtros y búsqueda -->
      <div class="filters-bar glass-card">
        <div class="filter-group">
          <label>Estat:</label>
          <select v-model="selectedStatus" @change="fetchOrders">
            <option value="all">Tots</option>
            <option value="pending">Pendents</option>
            <option value="confirmed">Confirmades</option>
            <option value="shipped">Enviades</option>
            <option value="delivered">Entregades</option>
            <option value="cancelled">Cancel·lades</option>
          </select>
        </div>
        <div class="filter-group search-group">
          <span class="material-icons">search</span>
          <input v-model="searchQuery" @input="debouncedSearch" placeholder="Buscar per número de comanda..." />
        </div>
        <div class="stats-row">
          <div class="stat-chip pending">
            <span class="material-icons">schedule</span>
            {{ statusCounts.pending }} pendents
          </div>
          <div class="stat-chip shipped">
            <span class="material-icons">local_shipping</span>
            {{ statusCounts.shipped }} enviades
          </div>
          <div class="stat-chip delivered">
            <span class="material-icons">check_circle</span>
            {{ statusCounts.delivered }} entregades
          </div>
        </div>
      </div>

      <!-- Cargando -->
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Carregant comandes...</p>
      </div>

      <!-- Tabla de pedidos -->
      <div v-else class="table-container glass-card">
        <table class="orders-table" v-if="orders.length > 0">
          <thead>
            <tr>
              <th>Comanda</th>
              <th>Client</th>
              <th>Data</th>
              <th>Productes</th>
              <th>Total</th>
              <th>Estat</th>
              <th>Accions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in orders" :key="order.id" :class="{ 'row-pending': order.status === 'pending' }">
              <td>
                <span class="order-number">{{ order.order_number }}</span>
                <span class="order-payment">💳 •••• {{ order.card_last_four }}</span>
              </td>
              <td>
                <div class="client-info">
                  <span class="client-name">{{ order.user?.name || 'N/A' }}</span>
                  <span class="client-email">{{ order.user?.email || '' }}</span>
                </div>
              </td>
              <td class="date-cell">{{ formatDate(order.created_at) }}</td>
              <td>
                <div class="items-preview">
                  <span v-for="item in order.items?.slice(0, 2)" :key="item.id" class="item-tag">
                    {{ item.quantity }}x {{ truncate(item.product_name, 20) }}
                  </span>
                  <span v-if="order.items?.length > 2" class="more-items">+{{ order.items.length - 2 }} més</span>
                </div>
              </td>
              <td class="total-cell">{{ order.total }}€</td>
              <td>
                <span class="status-badge" :class="order.status">{{ statusLabel(order.status) }}</span>
              </td>
              <td class="actions-cell">
                <div class="action-buttons">
                  <!-- Botones de avance de estado -->
                  <button v-if="order.status === 'pending'" 
                    @click="changeStatus(order, 'confirmed')" 
                    class="action-btn confirm-btn" 
                    title="Confirmar comanda">
                    <span class="material-icons">thumb_up</span>
                    Confirmar
                  </button>
                  <button v-if="order.status === 'confirmed'" 
                    @click="changeStatus(order, 'shipped')" 
                    class="action-btn ship-btn" 
                    title="Marcar com enviada">
                    <span class="material-icons">local_shipping</span>
                    Enviar
                  </button>
                  <button v-if="order.status === 'shipped'" 
                    @click="changeStatus(order, 'delivered')" 
                    class="action-btn deliver-btn" 
                    title="Marcar com entregada">
                    <span class="material-icons">check_circle</span>
                    Entregat
                  </button>
                  <button v-if="order.status !== 'delivered' && order.status !== 'cancelled'" 
                    @click="changeStatus(order, 'cancelled')" 
                    class="action-btn cancel-btn" 
                    title="Cancel·lar comanda">
                    <span class="material-icons">cancel</span>
                  </button>
                  <span v-if="order.status === 'delivered'" class="delivered-text">✅ Completada</span>
                  <span v-if="order.status === 'cancelled'" class="cancelled-text">❌ Cancel·lada</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else class="no-results">
          <span class="material-icons">inbox</span>
          <p>No s'han trobat comandes amb els filtres seleccionats.</p>
        </div>

        <!-- Paginación -->
        <div v-if="totalPages > 1" class="pagination">
          <button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1" class="page-btn">
            <span class="material-icons">chevron_left</span>
          </button>
          <span class="page-info">Pàgina {{ currentPage }} de {{ totalPages }}</span>
          <button @click="changePage(currentPage + 1)" :disabled="currentPage >= totalPages" class="page-btn">
            <span class="material-icons">chevron_right</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import { useOrdersStore } from '@/stores/orders';
import Swal from 'sweetalert2';

const ordersStore = useOrdersStore();
const loading = ref(true);
const orders = ref([]);
const selectedStatus = ref('all');
const searchQuery = ref('');
const currentPage = ref(1);
const totalPages = ref(1);

const statusCounts = reactive({
  pending: 0,
  shipped: 0,
  delivered: 0
});

let searchTimeout = null;

const statusLabel = (status) => {
  const labels = {
    pending: 'Pendent',
    confirmed: 'Confirmada',
    shipped: 'Enviada',
    delivered: 'Entregada',
    cancelled: 'Cancel·lada'
  };
  return labels[status] || status;
};

const truncate = (str, len) => {
  if (!str) return '';
  return str.length > len ? str.slice(0, len) + '...' : str;
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ca-ES', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const fetchOrders = async () => {
  loading.value = true;
  try {
    const params = { page: currentPage.value };
    if (selectedStatus.value !== 'all') params.status = selectedStatus.value;
    if (searchQuery.value) params.search = searchQuery.value;

    const response = await ordersStore.fetchAdminOrders(params);
    orders.value = response.data;
    totalPages.value = response.last_page || 1;

    // Contar estados para los chips
    updateStatusCounts();
  } catch (err) {
    console.error('Error al cargar comandes:', err);
  } finally {
    loading.value = false;
  }
};

const updateStatusCounts = () => {
  statusCounts.pending = orders.value.filter(o => o.status === 'pending').length;
  statusCounts.shipped = orders.value.filter(o => o.status === 'shipped').length;
  statusCounts.delivered = orders.value.filter(o => o.status === 'delivered').length;
};

const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    currentPage.value = 1;
    fetchOrders();
  }, 400);
};

const changeStatus = async (order, newStatus) => {
  const statusNames = {
    confirmed: 'confirmar',
    shipped: 'marcar com enviada',
    delivered: 'marcar com entregada',
    cancelled: 'cancel·lar'
  };

  const result = await Swal.fire({
    title: `${statusNames[newStatus]}?`,
    text: `Vas a ${statusNames[newStatus]} la comanda ${order.order_number}.`,
    icon: newStatus === 'cancelled' ? 'warning' : 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, continuar',
    cancelButtonText: 'No, tornar',
    background: '#1a1f2e',
    color: '#ffffff',
    confirmButtonColor: newStatus === 'cancelled' ? '#ef4444' : '#00A1FF'
  });

  if (!result.isConfirmed) return;

  try {
    await ordersStore.updateOrderStatus(order.id, newStatus);
    // Actualizar en la lista local
    const idx = orders.value.findIndex(o => o.id === order.id);
    if (idx !== -1) orders.value[idx].status = newStatus;
    updateStatusCounts();

    Swal.fire({
      icon: 'success',
      title: 'Estat actualitzat',
      text: `Comanda ${order.order_number}: ${statusLabel(newStatus)}`,
      timer: 2000,
      showConfirmButton: false,
      background: '#1a1f2e',
      color: '#ffffff'
    });
  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No s\'ha pogut actualitzar l\'estat.',
      background: '#1a1f2e',
      color: '#ffffff'
    });
  }
};

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    fetchOrders();
  }
};

onMounted(() => {
  fetchOrders();
});
</script>

<style scoped>
/* ══════════════════════════════════════════════════════════════
   CONTENEDOR
   ══════════════════════════════════════════════════════════════ */
.admin-orders {
  min-height: 80vh;
  padding: 30px 20px 60px;
}
.admin-container {
  max-width: 1300px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 25px;
}
.header-section h1 {
  font-size: 1.8rem;
  background: linear-gradient(to right, #fff, #94a3b8);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 800;
}
.header-subtitle {
  color: #8896AB;
  margin-top: 4px;
}

/* ══════════════════════════════════════════════════════════════
   FILTROS
   ══════════════════════════════════════════════════════════════ */
.glass-card {
  background: rgba(30, 34, 43, 0.65);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 16px;
  padding: 20px 25px;
}

.filters-bar {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 16px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 8px;
}
.filter-group label {
  color: #8896AB;
  font-size: 0.9rem;
  font-weight: 500;
}
.filter-group select,
.filter-group input {
  padding: 10px 14px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 10px;
  color: #EAEAEA;
  font-size: 0.9rem;
  outline: none;
}
.filter-group select:focus,
.filter-group input:focus {
  border-color: #00A1FF;
}
.filter-group select option {
  background: #1a1f2e;
}

.search-group {
  flex: 1;
  min-width: 200px;
  position: relative;
}
.search-group .material-icons {
  position: absolute;
  left: 12px;
  color: #8896AB;
  font-size: 1.2rem;
}
.search-group input {
  padding-left: 40px;
  width: 100%;
  box-sizing: border-box;
}

.stats-row {
  display: flex;
  gap: 10px;
  width: 100%;
  margin-top: 4px;
}
.stat-chip {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}
.stat-chip .material-icons { font-size: 1rem; }
.stat-chip.pending { background: rgba(251, 191, 36, 0.1); color: #fbbf24; }
.stat-chip.shipped { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
.stat-chip.delivered { background: rgba(16, 185, 129, 0.1); color: #10b981; }

/* ══════════════════════════════════════════════════════════════
   TABLA
   ══════════════════════════════════════════════════════════════ */
.table-container { padding: 0; overflow-x: auto; }

.orders-table {
  width: 100%;
  border-collapse: collapse;
}
.orders-table th {
  padding: 14px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 0.85rem;
  color: #8896AB;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  white-space: nowrap;
}
.orders-table td {
  padding: 16px;
  border-bottom: 1px solid rgba(255,255,255,0.04);
  vertical-align: middle;
}
.orders-table tbody tr {
  transition: background 0.2s;
}
.orders-table tbody tr:hover {
  background: rgba(255,255,255,0.03);
}
.row-pending {
  border-left: 3px solid #fbbf24;
}

.order-number {
  display: block;
  font-weight: 700;
  color: #00A1FF;
  font-size: 0.9rem;
}
.order-payment {
  display: block;
  font-size: 0.75rem;
  color: #8896AB;
  margin-top: 2px;
}

.client-info {
  display: flex;
  flex-direction: column;
}
.client-name {
  font-weight: 600;
  color: #EAEAEA;
  font-size: 0.9rem;
}
.client-email {
  font-size: 0.75rem;
  color: #8896AB;
}

.date-cell { color: #CBD5E1; font-size: 0.85rem; white-space: nowrap; }
.total-cell { font-weight: 700; color: #00D4AA; font-size: 1.05rem; }

.items-preview {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.item-tag {
  font-size: 0.8rem;
  color: #CBD5E1;
  background: rgba(255,255,255,0.04);
  padding: 3px 8px;
  border-radius: 6px;
  display: inline-block;
}
.more-items {
  font-size: 0.75rem;
  color: #8896AB;
  font-style: italic;
}

/* Badges */
.status-badge {
  padding: 5px 14px;
  border-radius: 20px;
  font-size: 0.78rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  white-space: nowrap;
}
.status-badge.pending { background: rgba(251, 191, 36, 0.15); color: #fbbf24; }
.status-badge.confirmed { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.status-badge.shipped { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
.status-badge.delivered { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.status-badge.cancelled { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

/* Acciones */
.actions-cell { white-space: nowrap; }
.action-buttons {
  display: flex;
  align-items: center;
  gap: 6px;
}
.action-btn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 6px 12px;
  border: none;
  border-radius: 8px;
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}
.action-btn .material-icons { font-size: 1rem; }

.confirm-btn { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.confirm-btn:hover { background: rgba(59, 130, 246, 0.3); }
.ship-btn { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
.ship-btn:hover { background: rgba(139, 92, 246, 0.3); }
.deliver-btn { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.deliver-btn:hover { background: rgba(16, 185, 129, 0.3); }
.cancel-btn { background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 6px 8px; }
.cancel-btn:hover { background: rgba(239, 68, 68, 0.25); }

.delivered-text { color: #10b981; font-size: 0.85rem; font-weight: 500; }
.cancelled-text { color: #ef4444; font-size: 0.85rem; font-weight: 500; }

/* Paginación */
.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 16px;
}
.page-btn {
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.12);
  color: #CBD5E1;
  padding: 8px;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
}
.page-btn:hover:not(:disabled) { background: rgba(255,255,255,0.12); }
.page-btn:disabled { opacity: 0.3; cursor: not-allowed; }
.page-info { color: #8896AB; font-size: 0.9rem; }

/* Sin resultados */
.no-results {
  text-align: center;
  padding: 60px 20px;
  color: #8896AB;
}
.no-results .material-icons {
  font-size: 3rem;
  display: block;
  margin-bottom: 12px;
  color: #475569;
}

/* Cargando */
.loading-state {
  text-align: center;
  padding: 60px;
  color: #8896AB;
}
.spinner {
  width: 36px;
  height: 36px;
  border: 4px solid rgba(255,255,255,0.1);
  border-top-color: #00A1FF;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 12px;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Responsive */
@media (max-width: 900px) {
  .orders-table { font-size: 0.85rem; }
  .stats-row { flex-wrap: wrap; }
}
</style>
