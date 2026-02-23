<template>
  <div class="profile-container">
    <div class="profile-dashboard-layout">
      <!-- Sidebar (Componente Reutilizable) -->
      <UserSidebar activeTab="orders" />

      <!-- Área de Pedidos Principal -->
      <main class="profile-content">
        <section class="content-section glass-card">
          <header class="section-header">
            <h2 class="card-title"><span class="material-icons title-icon">receipt_long</span> Mis pedidos</h2>
            <p class="card-desc">Historial completo de todas las compras realizadas en la tienda.</p>
          </header>
      
      <!-- Estado de carga -->
      <div v-if="loading" class="loading-state">
        <div class="spinner-large"></div>
        <p>Cargando pedidos...</p>
      </div>

      <!-- Sin pedidos -->
      <div v-else-if="orders.length === 0" class="empty-state inner-glass">
        <span class="material-icons empty-icon">receipt_long</span>
        <h2>Todavía no tienes pedidos</h2>
        <p>Cuando hagas tu primera compra, aparecerá aquí.</p>
        <router-link to="/products" class="btn-primary">
          <span class="material-icons">shopping_bag</span>
          Explorar Productos
        </router-link>
      </div>

      <!-- Lista de pedidos -->
      <div v-else class="orders-list">
        <div v-for="order in orders" :key="order.id" class="order-card inner-glass" @click="toggleOrder(order.id)">
          
          <!-- Cabecera del pedido -->
          <div class="order-header">
            <div class="order-main-info">
              <span class="order-number">{{ order.order_number }}</span>
              <span class="order-date">{{ formatDate(order.created_at) }}</span>
            </div>
            <div class="order-header-right">
              <span class="order-total-badge">{{ order.total }}€</span>
              <span class="status-badge" :class="order.status">{{ statusLabel(order.status) }}</span>
              <span class="material-icons expand-icon" :class="{ rotated: expandedOrder === order.id }">expand_more</span>
            </div>
          </div>

          <!-- Timeline de tracking -->
          <div class="tracking-section" v-if="expandedOrder === order.id">
            <div class="tracking-timeline">
              <div class="track-step" v-for="step in trackingSteps" :key="step.key"
                :class="{ active: isStepActive(order.status, step.key), current: order.status === step.key }">
                <div class="track-dot">
                  <span class="material-icons" v-if="isStepActive(order.status, step.key) && order.status !== step.key">check</span>
                  <span class="material-icons" v-else-if="order.status === step.key">{{ step.icon }}</span>
                </div>
                <div class="track-info">
                  <span class="track-label">{{ step.label }}</span>
                  <span class="track-desc">{{ step.description }}</span>
                </div>
              </div>
            </div>

            <!-- Cancelado -->
            <div v-if="order.status === 'cancelled'" class="cancelled-notice">
              <span class="material-icons">cancel</span>
              <span>Este pedido ha sido cancelado.</span>
            </div>

            <!-- Items del pedido -->
            <div class="order-items-detail">
              <h4>Productos del pedido</h4>
              <div v-for="item in order.items" :key="item.id" class="order-item-row">
                <div class="order-item-left">
                  <span class="item-qty-badge">x{{ item.quantity }}</span>
                  <span class="item-name">{{ item.product_name }}</span>
                </div>
                <span class="item-price">{{ (item.price * item.quantity).toFixed(2) }}€</span>
              </div>
            </div>

            <!-- Resumen de precios -->
            <div class="order-price-summary">
              <div class="price-row">
                <span>Subtotal</span>
                <span>{{ order.subtotal }}€</span>
              </div>
              <div class="price-row">
                <span>IVA (21%)</span>
                <span>{{ order.tax }}€</span>
              </div>
              <div class="price-row total">
                <span>Total</span>
                <span>{{ order.total }}€</span>
              </div>
            </div>

            <!-- Info de envío -->
            <div class="shipping-info">
              <h4>📦 Dirección de envío</h4>
              <p>{{ order.shipping_name }}</p>
              <p>{{ order.shipping_address }}</p>
              <p>{{ order.shipping_postal }} {{ order.shipping_city }}</p>
              <p>📞 {{ order.shipping_phone }}</p>
            </div>

            <!-- Pago -->
            <div class="payment-info">
              <h4>💳 Pago</h4>
              <p>Tarjeta acabada en •••• {{ order.card_last_four }}</p>
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
import { ref, onMounted, computed } from 'vue';
import { useOrdersStore } from '@/stores/orders';
import UserSidebar from '@/components/UserSidebar.vue';

const ordersStore = useOrdersStore();
const loading = ref(true);
const expandedOrder = ref(null);

const orders = computed(() => ordersStore.orders);

// Pasos del tracking
const trackingSteps = [
  { key: 'pending', label: 'Pendiente', description: 'Pedido recibido, pendiente de confirmación', icon: 'schedule' },
  { key: 'confirmed', label: 'Confirmado', description: 'Pago verificado, preparando envío', icon: 'thumb_up' },
  { key: 'shipped', label: 'Enviado', description: 'Tu paquete está en camino', icon: 'local_shipping' },
  { key: 'delivered', label: 'Entregado', description: 'Pedido entregado correctamente', icon: 'check_circle' }
];

const statusOrder = { pending: 0, confirmed: 1, shipped: 2, delivered: 3, cancelled: -1 };

const isStepActive = (orderStatus, stepKey) => {
  if (orderStatus === 'cancelled') return false;
  return statusOrder[orderStatus] >= statusOrder[stepKey];
};

const statusLabel = (status) => {
  const labels = {
    pending: 'Pendiente',
    confirmed: 'Confirmado',
    shipped: 'Enviado',
    delivered: 'Entregado',
    cancelled: 'Cancelado'
  };
  return labels[status] || status;
};

const toggleOrder = (orderId) => {
  expandedOrder.value = expandedOrder.value === orderId ? null : orderId;
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

onMounted(async () => {
  await ordersStore.fetchOrders();
  loading.value = false;
});
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
  color: #00A1FF;
  font-size: 1.2em;
  background: rgba(0, 161, 255, 0.1);
  padding: 8px;
  border-radius: 10px;
}

.card-desc {
  color: #94a3b8;
  font-size: 0.95em;
  line-height: 1.5;
}

/* ══════════════════════════════════════════════════════════════
   ESTADOS VACÍO Y CARGA
   ══════════════════════════════════════════════════════════════ */
.loading-state {
  text-align: center;
  padding: 60px;
  color: #8896AB;
}
.spinner-large {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(255,255,255,0.1);
  border-top-color: #00A1FF;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 16px;
}
@keyframes spin { to { transform: rotate(360deg); } }

.empty-state {
  text-align: center;
  padding: 60px 30px;
}
.empty-icon {
  font-size: 4rem;
  color: #475569;
  margin-bottom: 16px;
  display: block;
}
.empty-state h2 {
  color: #CBD5E1;
  margin-bottom: 8px;
}
.empty-state p {
  color: #8896AB;
  margin-bottom: 24px;
}
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 28px;
  background: linear-gradient(135deg, #00A1FF, #0077CC);
  color: #fff;
  border: none;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.3s;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 161, 255, 0.35);
}

/* ══════════════════════════════════════════════════════════════
   TARJETA DE PEDIDO
   ══════════════════════════════════════════════════════════════ */
.inner-glass {
  background: rgba(20, 24, 32, 0.7);
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.05);
  transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  padding: 25px 30px;
}

.orders-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.order-card {
  cursor: pointer;
  transition: all 0.3s;
}
.order-card:hover {
  border-color: rgba(0, 161, 255, 0.3);
  box-shadow: 0 8px 30px rgba(0,0,0,0.3);
}

/* Cabecera */
.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}
.order-main-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.order-number {
  font-weight: 700;
  font-size: 1.1rem;
  color: #00A1FF;
}
.order-date {
  font-size: 0.85rem;
  color: #8896AB;
}
.order-header-right {
  display: flex;
  align-items: center;
  gap: 12px;
}
.order-total-badge {
  font-weight: 800;
  font-size: 1.15rem;
  color: #fff;
}

/* Badges de estado */
.status-badge {
  padding: 5px 14px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.status-badge.pending { background: rgba(251, 191, 36, 0.15); color: #fbbf24; }
.status-badge.confirmed { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.status-badge.shipped { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
.status-badge.delivered { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.status-badge.cancelled { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

.expand-icon {
  transition: transform 0.3s;
  color: #8896AB;
  font-size: 1.5rem;
}
.expand-icon.rotated { transform: rotate(180deg); }

/* ══════════════════════════════════════════════════════════════
   TRACKING TIMELINE
   ══════════════════════════════════════════════════════════════ */
.tracking-section {
  margin-top: 25px;
  padding-top: 20px;
  border-top: 1px solid rgba(255,255,255,0.08);
  animation: fadeIn 0.3s ease-out;
}

.tracking-timeline {
  display: flex;
  justify-content: space-between;
  position: relative;
  margin-bottom: 30px;
  padding: 0 10px;
}

/* Línea base detrás de los pasos */
.tracking-timeline::before {
  content: '';
  position: absolute;
  top: 20px;
  left: 30px;
  right: 30px;
  height: 3px;
  background: rgba(255,255,255,0.08);
  z-index: 0;
}

.track-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  position: relative;
  z-index: 1;
  flex: 1;
  opacity: 0.35;
  transition: all 0.3s;
}
.track-step.active { opacity: 1; }
.track-step.current { opacity: 1; }

.track-dot {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: rgba(255,255,255,0.08);
  border: 2px solid rgba(255,255,255,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
}
.track-step.active .track-dot {
  background: rgba(0, 212, 170, 0.15);
  border-color: #00D4AA;
}
.track-step.current .track-dot {
  background: linear-gradient(135deg, #00A1FF, #00D4AA);
  border-color: transparent;
  box-shadow: 0 0 20px rgba(0, 161, 255, 0.4);
}
.track-dot .material-icons {
  font-size: 1.1rem;
  color: #00D4AA;
}
.track-step.current .track-dot .material-icons {
  color: #fff;
}

.track-info {
  text-align: center;
}
.track-label {
  display: block;
  font-weight: 600;
  font-size: 0.85rem;
  color: #CBD5E1;
}
.track-desc {
  display: block;
  font-size: 0.72rem;
  color: #8896AB;
  margin-top: 2px;
  max-width: 120px;
}

.cancelled-notice {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 18px;
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.2);
  border-radius: 12px;
  color: #ef4444;
  font-weight: 500;
  margin-bottom: 20px;
}

/* ══════════════════════════════════════════════════════════════
   ITEMS DEL PEDIDO EXPANDIDO
   ══════════════════════════════════════════════════════════════ */
.order-items-detail {
  margin-bottom: 20px;
}
.order-items-detail h4,
.shipping-info h4,
.payment-info h4 {
  font-size: 0.95rem;
  color: #00D4AA;
  margin-bottom: 12px;
}

.order-item-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid rgba(255,255,255,0.04);
}
.order-item-row:last-child { border-bottom: none; }
.order-item-left {
  display: flex;
  align-items: center;
  gap: 10px;
}
.item-qty-badge {
  background: rgba(0, 161, 255, 0.15);
  color: #00A1FF;
  padding: 2px 8px;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 600;
}
.item-name { color: #EAEAEA; }
.item-price { color: #00D4AA; font-weight: 600; }

/* Resumen de precios */
.order-price-summary {
  padding: 16px 20px;
  background: rgba(255,255,255,0.03);
  border-radius: 12px;
  margin-bottom: 20px;
}
.price-row {
  display: flex;
  justify-content: space-between;
  padding: 4px 0;
  color: #CBD5E1;
  font-size: 0.9rem;
}
.price-row.total {
  font-size: 1.15rem;
  font-weight: 800;
  color: #fff;
  padding-top: 10px;
  margin-top: 8px;
  border-top: 1px solid rgba(255,255,255,0.08);
}

/* Info envío y pago */
.shipping-info, .payment-info {
  padding: 16px 20px;
  background: rgba(255,255,255,0.03);
  border-radius: 12px;
  margin-bottom: 12px;
}
.shipping-info p, .payment-info p {
  margin: 3px 0;
  color: #CBD5E1;
  font-size: 0.9rem;
}

/* ══════════════════════════════════════════════════════════════
   ANIMACIONES Y RESPONSIVE
   ══════════════════════════════════════════════════════════════ */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

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

@media (max-width: 768px) {
  .order-header { flex-direction: column; align-items: flex-start; }
  .order-header-right { width: 100%; justify-content: flex-start; }
  .tracking-timeline { flex-direction: column; gap: 12px; }
  .tracking-timeline::before { display: none; }
  .track-step { flex-direction: row; gap: 12px; }
  .track-info { text-align: left; }
  .track-desc { max-width: 100%; }
}
</style>
