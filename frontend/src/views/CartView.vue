<template>
  <div class="cart-view">
    <div class="container animate-fade-in">
      <h1 class="page-title">El meu carret</h1>

      <div v-if="cartStore.items.length === 0" class="empty-cart">
        <span class="material-icons empty-icon">shopping_cart_checkout</span>
        <p>El teu carret està buit.</p>
        <router-link to="/products" class="btn btn-primary">Explorar Productes</router-link>
      </div>

      <div v-else class="cart-layout">
        <div class="cart-items">
          <div v-for="item in cartStore.items" :key="item.id" class="cart-item glass-card">
            <div class="item-image">
              <img :src="item.image" :alt="item.name" />
            </div>
            <div class="item-details">
              <h3>{{ item.name }}</h3>
              <p class="item-price">{{ item.price }}€</p>
            </div>
            <div class="item-actions">
              <div class="quantity-control">
                <button @click="decreaseQuantity(item)" class="qty-btn">-</button>
                <span class="qty-val">{{ item.quantity }}</span>
                <button @click="increaseQuantity(item)" class="qty-btn">+</button>
              </div>
              <button @click="cartStore.removeItem(item.id)" class="remove-btn" title="Eliminar">
                <span class="material-icons">delete_outline</span>
              </button>
            </div>
          </div>
        </div>

        <div class="cart-summary glass-card">
          <h2>Resum de la comanda</h2>
          <div class="summary-row">
            <span>Subtotal</span>
            <span>{{ cartStore.subtotal }}€</span>
          </div>
          <div class="summary-row">
            <span>IVA (21%)</span>
            <span>{{ cartStore.tax }}€</span>
          </div>
          <div class="summary-divider"></div>
          <div class="summary-row total">
            <span>Total</span>
            <span>{{ cartStore.totalPrice }}€</span>
          </div>
          
          <button @click="processCheckout" class="btn btn-primary btn-block checkout-btn" :disabled="processing">
            <span v-if="!processing">Tramitar Pedido</span>
            <span v-else>Processant...</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cart';
import { useOrdersStore } from '@/stores/orders';
import { useRouter } from 'vue-router';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const cartStore = useCartStore();
const ordersStore = useOrdersStore();
const router = useRouter();
const processing = ref(false);

const increaseQuantity = (item) => {
  cartStore.updateQuantity(item.id, item.quantity + 1);
};

const decreaseQuantity = (item) => {
  if (item.quantity > 1) {
    cartStore.updateQuantity(item.id, item.quantity - 1);
  } else {
    cartStore.removeItem(item.id);
  }
};

const processCheckout = async () => {
  processing.value = true;
  
  // Simular proceso de pago
  setTimeout(async () => {
    const orderId = ordersStore.createOrder(cartStore.items, cartStore.totalPrice);
    cartStore.clearCart();
    processing.value = false;
    
    await Swal.fire({
      icon: 'success',
      title: 'Comanda realitzada!',
      text: `El teu número de comanda és: ${orderId}`,
      confirmButtonText: 'Veure les meves comandes',
      background: '#1a1a1a',
      color: '#ffffff',
      confirmButtonColor: '#00A1FF'
    });
    
    router.push('/profile'); // Redirigir a perfil/pedidos
  }, 1500);
};
</script>

<style scoped>
.cart-view {
  padding: 4rem 1rem;
  background-color: var(--color-background);
  min-height: 80vh;
}

.cart-layout {
  display: grid;
  grid-template-columns: 1fr 350px;
  gap: 2rem;
  margin-top: 2rem;
}

@media (max-width: 900px) {
  .cart-layout {
    grid-template-columns: 1fr;
  }
}

.cart-item {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  padding: 1.5rem;
  margin-bottom: 1rem;
  background: var(--color-surface);
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.05);
}

.item-image img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
}

.item-details {
  flex-grow: 1;
}

.item-details h3 {
  margin: 0 0 0.5rem 0;
  color: var(--color-text);
  font-size: 1.1rem;
}

.item-price {
  color: var(--color-primary);
  font-weight: bold;
}

.item-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.quantity-control {
  display: flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  overflow: hidden;
}

.qty-btn {
  background: none;
  border: none;
  color: var(--color-text);
  padding: 0.5rem 1rem;
  cursor: pointer;
  transition: background 0.3s;
}

.qty-btn:hover {
  background: rgba(255, 255, 255, 0.1);
}

.qty-val {
  padding: 0 0.5rem;
  min-width: 30px;
  text-align: center;
}

.remove-btn {
  background: none;
  border: none;
  color: #ff4d4d;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 50%;
  transition: background 0.3s;
}

.remove-btn:hover {
  background: rgba(255, 77, 77, 0.1);
}

.cart-summary {
  padding: 2rem;
  height: fit-content;
  background: var(--color-surface);
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.05);
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.summary-divider {
  height: 1px;
  background: rgba(255, 255, 255, 0.1);
  margin: 1.5rem 0;
}

.summary-row.total {
  font-size: 1.25rem;
  font-weight: bold;
  color: var(--color-primary);
}

.checkout-btn {
  width: 100%;
  margin-top: 1.5rem;
  padding: 1rem;
}

.empty-cart {
  text-align: center;
  padding: 4rem;
  color: rgba(255, 255, 255, 0.6);
}

.empty-icon {
  font-size: 5rem;
  margin-bottom: 1rem;
  display: block;
}

.glass-card {
  /* Efecto glassmorphism */
  background: rgba(30, 30, 30, 0.6);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
}
</style>
