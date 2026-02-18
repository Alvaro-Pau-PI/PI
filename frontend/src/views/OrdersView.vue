<template>
  <div class="orders-view">
    <div class="container animate-fade-in">
      <h1 class="page-title">Historial de Comandes</h1>
      
      <div v-if="ordersStore.orders.length === 0" class="empty-orders">
        <p>Encara no has realitzat cap comanda.</p>
        <router-link to="/products" class="btn btn-primary">Anar a comprar</router-link>
      </div>

      <div v-else class="orders-list">
        <div v-for="order in ordersStore.orders" :key="order.id" class="order-card glass-card">
          <div class="order-header">
            <div>
              <span class="order-id">#{{ order.id }}</span>
              <span class="order-date">{{ formatDate(order.date) }}</span>
            </div>
            <span class="order-status" :class="order.status.toLowerCase()">{{ order.status }}</span>
          </div>
          
          <div class="order-items">
            <div v-for="item in order.items" :key="item.id" class="order-item-preview">
               <span>{{ item.quantity }}x {{ item.name }}</span>
               <span>{{ item.price }}€</span>
            </div>
          </div>
          
          <div class="order-footer">
            <span class="order-total">Total: {{ order.total }}€</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useOrdersStore } from '@/stores/orders';

const ordersStore = useOrdersStore();

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('ca-ES', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>

<style scoped>
.orders-view {
  padding: 2rem 0;
}

.orders-list {
  display: grid;
  gap: 1.5rem;
  max-width: 800px;
  margin: 0 auto;
}

.order-card {
  padding: 1.5rem;
  border-radius: 12px;
  background: var(--color-surface);
  border: 1px solid rgba(255, 255, 255, 0.05);
  transition: transform 0.2s;
}

.order-card:hover {
  transform: translateY(-2px);
}

.order-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.order-id {
  font-weight: bold;
  color: var(--color-primary);
  margin-right: 1rem;
}

.order-date {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.9rem;
}

.order-status {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.order-status.completado {
  background: rgba(46, 204, 113, 0.2);
  color: #2ecc71;
}

.order-items {
  margin-bottom: 1rem;
}

.order-item-preview {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.25rem;
  color: rgba(255, 255, 255, 0.8);
}

.order-total {
  font-size: 1.1rem;
  font-weight: bold;
}

.empty-orders {
  text-align: center;
  padding: 3rem;
  color: rgba(255, 255, 255, 0.6);
}

.glass-card {
  background: rgba(30, 30, 30, 0.6);
  backdrop-filter: blur(10px);
}
</style>
