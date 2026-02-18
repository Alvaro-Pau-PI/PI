<template>
  <div class="checkout-view">
    <div class="checkout-container">
      
      <!-- Breadcrumb -->
      <nav class="breadcrumb">
        <router-link to="/cart">🛒 Carret</router-link>
        <span class="sep">›</span>
        <span class="current">Finalitzar Compra</span>
      </nav>

      <h1 class="checkout-title">Finalitzar Compra</h1>

      <!-- Indicador de pasos -->
      <div class="steps-indicator">
        <div class="step" :class="{ active: currentStep >= 1, done: currentStep > 1 }">
          <div class="step-circle">
            <span v-if="currentStep > 1" class="material-icons">check</span>
            <span v-else>1</span>
          </div>
          <span class="step-label">Enviament</span>
        </div>
        <div class="step-line" :class="{ active: currentStep > 1 }"></div>
        <div class="step" :class="{ active: currentStep >= 2, done: currentStep > 2 }">
          <div class="step-circle">
            <span v-if="currentStep > 2" class="material-icons">check</span>
            <span v-else>2</span>
          </div>
          <span class="step-label">Pagament</span>
        </div>
        <div class="step-line" :class="{ active: currentStep > 2 }"></div>
        <div class="step" :class="{ active: currentStep >= 3 }">
          <div class="step-circle">3</div>
          <span class="step-label">Confirmació</span>
        </div>
      </div>

      <div class="checkout-layout">
        <!-- Columna principal: formularios -->
        <div class="checkout-main">

          <!-- PASO 1: Dirección de envío -->
          <div v-if="currentStep === 1" class="step-content glass-card animate-fade-in">
            <h2>📦 Adreça d'Enviament</h2>
            <form @submit.prevent="nextStep" class="shipping-form">
              <div class="form-group">
                <label for="shipping_name">Nom complet *</label>
                <input id="shipping_name" v-model="shippingData.name" type="text" required placeholder="Pau Albero" />
              </div>
              <div class="form-group">
                <label for="shipping_address">Adreça *</label>
                <input id="shipping_address" v-model="shippingData.address" type="text" required placeholder="C/ Principal, 12, 2n" />
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="shipping_city">Ciutat *</label>
                  <input id="shipping_city" v-model="shippingData.city" type="text" required placeholder="València" />
                </div>
                <div class="form-group">
                  <label for="shipping_postal">Codi Postal *</label>
                  <input id="shipping_postal" v-model="shippingData.postal" type="text" required maxlength="5" placeholder="46001" />
                </div>
              </div>
              <div class="form-group">
                <label for="shipping_phone">Telèfon *</label>
                <input id="shipping_phone" v-model="shippingData.phone" type="tel" required placeholder="+34 612 345 678" />
              </div>
              <div class="form-group">
                <label for="notes">Notes (opcional)</label>
                <textarea id="notes" v-model="shippingData.notes" rows="2" placeholder="Instruccions d'entrega..."></textarea>
              </div>
              <div class="step-actions">
                <router-link to="/cart" class="btn-secondary">← Tornar al carret</router-link>
                <button type="submit" class="btn-primary">Continuar al pagament →</button>
              </div>
            </form>
          </div>

          <!-- PASO 2: Datos de pago -->
          <div v-if="currentStep === 2" class="step-content glass-card animate-fade-in">
            <h2>💳 Mètode de Pagament</h2>
            
            <!-- Tarjeta de crédito visual -->
            <div class="credit-card-preview" :class="cardType">
              <div class="card-chip"></div>
              <div class="card-type-logo">{{ cardBrandIcon }}</div>
              <div class="card-number-display">
                <span v-for="(group, i) in displayCardNumber" :key="i">{{ group }}</span>
              </div>
              <div class="card-bottom">
                <div class="card-holder">
                  <small>TITULAR</small>
                  <span>{{ shippingData.name || 'NOM COMPLET' }}</span>
                </div>
                <div class="card-expiry-display">
                  <small>CADUCA</small>
                  <span>{{ cardData.expiry || 'MM/AA' }}</span>
                </div>
              </div>
            </div>

            <form @submit.prevent="nextStep" class="payment-form">
              <div class="form-group">
                <label for="card_number">Número de targeta *</label>
                <input id="card_number" 
                  v-model="cardData.number" 
                  type="text" 
                  required 
                  maxlength="19"
                  placeholder="4242 4242 4242 4242"
                  @input="formatCardNumber"
                  autocomplete="cc-number" />
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="card_expiry">Caducitat *</label>
                  <input id="card_expiry" 
                    v-model="cardData.expiry" 
                    type="text" 
                    required 
                    maxlength="5"
                    placeholder="MM/AA"
                    @input="formatExpiry" 
                    autocomplete="cc-exp" />
                </div>
                <div class="form-group">
                  <label for="card_cvv">CVV *</label>
                  <input id="card_cvv" 
                    v-model="cardData.cvv" 
                    type="password" 
                    required 
                    maxlength="4"
                    placeholder="•••"
                    autocomplete="cc-csc" />
                </div>
              </div>
              <div class="secure-badge">
                <span class="material-icons">lock</span>
                Pagament segur amb xifrat SSL de 256 bits
              </div>
              <div class="step-actions">
                <button type="button" @click="prevStep" class="btn-secondary">← Enrere</button>
                <button type="submit" class="btn-primary">Revisar comanda →</button>
              </div>
            </form>
          </div>

          <!-- PASO 3: Confirmación -->
          <div v-if="currentStep === 3" class="step-content glass-card animate-fade-in">
            <h2>✅ Resum de la Comanda</h2>
            
            <div class="confirmation-sections">
              <!-- Resumen de envío -->
              <div class="confirm-section">
                <h3>📦 Enviament</h3>
                <p>{{ shippingData.name }}</p>
                <p>{{ shippingData.address }}</p>
                <p>{{ shippingData.postal }} {{ shippingData.city }}</p>
                <p>📞 {{ shippingData.phone }}</p>
                <p v-if="shippingData.notes" class="notes-text">📝 {{ shippingData.notes }}</p>
              </div>

              <!-- Resumen de pago -->
              <div class="confirm-section">
                <h3>💳 Pagament</h3>
                <p>Targeta que acaba en •••• {{ cardLastFour }}</p>
                <p>{{ cardBrandIcon }} {{ cardBrandName }}</p>
              </div>

              <!-- Items del pedido -->
              <div class="confirm-section">
                <h3>🛍️ Productes</h3>
                <div v-for="item in cartStore.items" :key="item.id" class="confirm-item">
                  <img :src="item.image" :alt="item.name" class="confirm-item-img" />
                  <div class="confirm-item-info">
                    <span class="confirm-item-name">{{ item.name }}</span>
                    <span class="confirm-item-qty">x{{ item.quantity }}</span>
                  </div>
                  <span class="confirm-item-price">{{ (item.price * item.quantity).toFixed(2) }}€</span>
                </div>
              </div>
            </div>

            <div class="step-actions">
              <button @click="prevStep" class="btn-secondary">← Enrere</button>
              <button @click="submitOrder" class="btn-confirm" :disabled="processing">
                <span v-if="!processing">
                  <span class="material-icons">shopping_bag</span>
                  Confirmar i Pagar {{ cartStore.totalPrice }}€
                </span>
                <span v-else class="processing-text">
                  <span class="spinner"></span>
                  Processant pagament...
                </span>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar: Resumen del pedido -->
        <aside class="checkout-sidebar glass-card">
          <h3>Resum</h3>
          <div class="sidebar-items">
            <div v-for="item in cartStore.items" :key="item.id" class="sidebar-item">
              <img :src="item.image" :alt="item.name" />
              <div class="sidebar-item-info">
                <span class="sidebar-item-name">{{ item.name }}</span>
                <span class="sidebar-item-qty">x{{ item.quantity }}</span>
              </div>
              <span class="sidebar-item-price">{{ (item.price * item.quantity).toFixed(2) }}€</span>
            </div>
          </div>
          <div class="sidebar-divider"></div>
          <div class="sidebar-row">
            <span>Subtotal</span>
            <span>{{ cartStore.subtotal }}€</span>
          </div>
          <div class="sidebar-row">
            <span>IVA (21%)</span>
            <span>{{ cartStore.tax }}€</span>
          </div>
          <div class="sidebar-row shipping-row">
            <span>Enviament</span>
            <span class="free-shipping">Gratuït</span>
          </div>
          <div class="sidebar-divider"></div>
          <div class="sidebar-row total-row">
            <span>Total</span>
            <span>{{ cartStore.totalPrice }}€</span>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cart';
import { useOrdersStore } from '@/stores/orders';
import Swal from 'sweetalert2';

const router = useRouter();
const cartStore = useCartStore();
const ordersStore = useOrdersStore();

const currentStep = ref(1);
const processing = ref(false);

// Datos de envío
const shippingData = ref({
  name: '',
  address: '',
  city: '',
  postal: '',
  phone: '',
  notes: ''
});

// Datos de la tarjeta
const cardData = ref({
  number: '',
  expiry: '',
  cvv: ''
});

// Redirigir si carrito vacío
if (cartStore.items.length === 0) {
  router.replace('/cart');
}

// Computados de la tarjeta
const cardLastFour = computed(() => {
  const nums = cardData.value.number.replace(/\s/g, '');
  return nums.slice(-4) || '0000';
});

const cardType = computed(() => {
  const num = cardData.value.number.replace(/\s/g, '');
  if (num.startsWith('4')) return 'visa';
  if (num.startsWith('5') || num.startsWith('2')) return 'mastercard';
  if (num.startsWith('3')) return 'amex';
  return 'default';
});

const cardBrandIcon = computed(() => {
  const brands = { visa: '𝗩𝗜𝗦𝗔', mastercard: '⬤⬤', amex: 'AMEX', default: '' };
  return brands[cardType.value];
});

const cardBrandName = computed(() => {
  const brands = { visa: 'Visa', mastercard: 'Mastercard', amex: 'American Express', default: 'Targeta' };
  return brands[cardType.value];
});

const displayCardNumber = computed(() => {
  const num = cardData.value.number.replace(/\s/g, '').padEnd(16, '•');
  return [num.slice(0, 4), num.slice(4, 8), num.slice(8, 12), num.slice(12, 16)];
});

// Formateo automático del número de tarjeta
const formatCardNumber = () => {
  let val = cardData.value.number.replace(/\D/g, '').slice(0, 16);
  cardData.value.number = val.replace(/(\d{4})(?=\d)/g, '$1 ');
};

// Formateo automático de la caducidad
const formatExpiry = () => {
  let val = cardData.value.expiry.replace(/\D/g, '').slice(0, 4);
  if (val.length >= 2) {
    val = val.slice(0, 2) + '/' + val.slice(2);
  }
  cardData.value.expiry = val;
};

const nextStep = () => {
  if (currentStep.value < 3) currentStep.value++;
};

const prevStep = () => {
  if (currentStep.value > 1) currentStep.value--;
};

// Enviar pedido al backend
const submitOrder = async () => {
  processing.value = true;

  try {
    const orderPayload = {
      items: cartStore.items.map(item => ({
        product_id: item.id,
        quantity: item.quantity
      })),
      shipping_name: shippingData.value.name,
      shipping_address: shippingData.value.address,
      shipping_city: shippingData.value.city,
      shipping_postal: shippingData.value.postal,
      shipping_phone: shippingData.value.phone,
      card_last_four: cardLastFour.value,
      payment_method: 'card',
      notes: shippingData.value.notes || null
    };

    const order = await ordersStore.createOrder(orderPayload);
    cartStore.clearCart();

    await Swal.fire({
      icon: 'success',
      title: '🎉 Comanda realitzada!',
      html: `
        <p style="margin:8px 0">El teu número de comanda és:</p>
        <p style="font-size:1.3em;font-weight:bold;color:#00D4AA">${order.order_number}</p>
        <p style="margin-top:8px;font-size:0.9em;color:#aaa">Rebras una confirmació amb els detalls.</p>
      `,
      confirmButtonText: 'Veure les meues comandes',
      background: '#1a1f2e',
      color: '#ffffff',
      confirmButtonColor: '#00A1FF'
    });

    router.push('/orders');
  } catch (err) {
    await Swal.fire({
      icon: 'error',
      title: 'Error al processar',
      text: ordersStore.error || 'No s\'ha pogut completar la comanda.',
      background: '#1a1f2e',
      color: '#ffffff',
      confirmButtonColor: '#ff4757'
    });
  } finally {
    processing.value = false;
  }
};
</script>

<style scoped>
/* ══════════════════════════════════════════════════════════════
   CONTENEDOR PRINCIPAL
   ══════════════════════════════════════════════════════════════ */
.checkout-view {
  min-height: 80vh;
  padding: 30px 20px 60px;
}

.checkout-container {
  max-width: 1200px;
  margin: 0 auto;
}

/* ══════════════════════════════════════════════════════════════
   BREADCRUMB
   ══════════════════════════════════════════════════════════════ */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
  font-size: 0.9em;
}
.breadcrumb a { color: #8896AB; text-decoration: none; }
.breadcrumb a:hover { color: #00A1FF; }
.breadcrumb .sep { color: #475569; }
.breadcrumb .current { color: #CBD5E1; }

.checkout-title {
  font-size: 2rem;
  margin-bottom: 25px;
  background: linear-gradient(to right, #fff, #94a3b8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 800;
}

/* ══════════════════════════════════════════════════════════════
   INDICADOR DE PASOS
   ══════════════════════════════════════════════════════════════ */
.steps-indicator {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0;
  margin-bottom: 40px;
}

.step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  opacity: 0.4;
  transition: all 0.3s;
}
.step.active { opacity: 1; }

.step-circle {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: rgba(255,255,255,0.1);
  border: 2px solid rgba(255,255,255,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1rem;
  color: #aaa;
  transition: all 0.3s;
}
.step.active .step-circle {
  background: linear-gradient(135deg, #00A1FF, #00D4AA);
  border-color: transparent;
  color: #fff;
}
.step.done .step-circle {
  background: #00D4AA;
  border-color: transparent;
  color: #fff;
}

.step-label {
  font-size: 0.85rem;
  color: #8896AB;
  font-weight: 500;
}
.step.active .step-label { color: #fff; }

.step-line {
  width: 80px;
  height: 3px;
  background: rgba(255,255,255,0.1);
  border-radius: 2px;
  margin: 0 12px;
  margin-bottom: 28px;
  transition: background 0.3s;
}
.step-line.active { background: linear-gradient(90deg, #00D4AA, #00A1FF); }

/* ══════════════════════════════════════════════════════════════
   LAYOUT CHECKOUT
   ══════════════════════════════════════════════════════════════ */
.checkout-layout {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 30px;
  align-items: start;
}

.glass-card {
  background: rgba(30, 34, 43, 0.65);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 20px;
  padding: 35px;
}

.step-content h2 {
  font-size: 1.4rem;
  margin-bottom: 25px;
  color: #fff;
}

/* ══════════════════════════════════════════════════════════════
   FORMULARIOS
   ══════════════════════════════════════════════════════════════ */
.form-group {
  margin-bottom: 18px;
}
.form-group label {
  display: block;
  font-size: 0.85rem;
  color: #8896AB;
  margin-bottom: 6px;
  font-weight: 500;
}
.form-group input,
.form-group textarea {
  width: 100%;
  padding: 12px 16px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 12px;
  color: #EAEAEA;
  font-size: 1rem;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  box-sizing: border-box;
}
.form-group input:focus,
.form-group textarea:focus {
  border-color: #00A1FF;
  box-shadow: 0 0 0 3px rgba(0, 161, 255, 0.15);
}
.form-group input::placeholder,
.form-group textarea::placeholder {
  color: rgba(255,255,255,0.25);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

/* ══════════════════════════════════════════════════════════════
   TARJETA DE CRÉDITO VISUAL
   ══════════════════════════════════════════════════════════════ */
.credit-card-preview {
  width: 100%;
  max-width: 400px;
  aspect-ratio: 1.586;
  border-radius: 20px;
  padding: 30px;
  margin: 0 auto 30px;
  position: relative;
  overflow: hidden;
  color: #fff;
  font-family: 'Courier New', monospace;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  box-shadow: 0 20px 60px rgba(0,0,0,0.4);
  /* Por defecto: gradiente genérico */
  background: linear-gradient(135deg, #434343 0%, #1a1a2e 100%);
  transition: background 0.5s;
}
.credit-card-preview.visa {
  background: linear-gradient(135deg, #1a237e 0%, #283593 40%, #1565c0 100%);
}
.credit-card-preview.mastercard {
  background: linear-gradient(135deg, #e53935 0%, #ff6f00 50%, #ffab00 100%);
}
.credit-card-preview.amex {
  background: linear-gradient(135deg, #00695c 0%, #00897b 50%, #26a69a 100%);
}

.card-chip {
  width: 50px;
  height: 38px;
  background: linear-gradient(135deg, #e0c96f, #c5a740);
  border-radius: 8px;
  position: relative;
}
.card-chip::after {
  content: '';
  position: absolute;
  inset: 4px;
  border: 1px solid rgba(0,0,0,0.2);
  border-radius: 4px;
}

.card-type-logo {
  position: absolute;
  top: 25px;
  right: 30px;
  font-size: 1.4rem;
  font-weight: 800;
  letter-spacing: 2px;
  opacity: 0.9;
}

.card-number-display {
  display: flex;
  gap: 18px;
  font-size: 1.4rem;
  letter-spacing: 3px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.card-bottom {
  display: flex;
  justify-content: space-between;
}
.card-holder, .card-expiry-display {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.card-holder small, .card-expiry-display small {
  font-size: 0.65rem;
  opacity: 0.7;
  letter-spacing: 1px;
}
.card-holder span, .card-expiry-display span {
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.secure-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background: rgba(0, 212, 170, 0.1);
  border: 1px solid rgba(0, 212, 170, 0.2);
  border-radius: 10px;
  font-size: 0.85rem;
  color: #00D4AA;
  margin-top: 5px;
  margin-bottom: 20px;
}
.secure-badge .material-icons { font-size: 1.2rem; }

/* ══════════════════════════════════════════════════════════════
   CONFIRMACIÓN
   ══════════════════════════════════════════════════════════════ */
.confirmation-sections {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 25px;
}

.confirm-section {
  padding: 20px;
  background: rgba(255,255,255,0.04);
  border-radius: 14px;
  border: 1px solid rgba(255,255,255,0.06);
}
.confirm-section h3 {
  font-size: 1rem;
  margin-bottom: 10px;
  color: #00D4AA;
}
.confirm-section p {
  margin: 4px 0;
  color: #CBD5E1;
  font-size: 0.95rem;
}
.notes-text { font-style: italic; opacity: 0.7; }

.confirm-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid rgba(255,255,255,0.05);
}
.confirm-item:last-child { border-bottom: none; }
.confirm-item-img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 8px;
  background: #fff;
}
.confirm-item-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}
.confirm-item-name { color: #EAEAEA; font-weight: 500; }
.confirm-item-qty { color: #8896AB; font-size: 0.85rem; }
.confirm-item-price { color: #00D4AA; font-weight: 700; }

/* ══════════════════════════════════════════════════════════════
   BOTONES
   ══════════════════════════════════════════════════════════════ */
.step-actions {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  margin-top: 25px;
}

.btn-primary {
  padding: 14px 28px;
  background: linear-gradient(135deg, #00A1FF, #0077CC);
  color: #fff;
  border: none;
  border-radius: 12px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 161, 255, 0.35);
}

.btn-secondary {
  padding: 14px 28px;
  background: rgba(255,255,255,0.06);
  color: #CBD5E1;
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 12px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
}
.btn-secondary:hover { background: rgba(255,255,255,0.1); }

.btn-confirm {
  padding: 16px 32px;
  background: linear-gradient(135deg, #00D4AA, #00A187);
  color: #fff;
  border: none;
  border-radius: 14px;
  font-weight: 800;
  font-size: 1.1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s;
}
.btn-confirm:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(0, 212, 170, 0.35);
}
.btn-confirm:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.processing-text {
  display: flex;
  align-items: center;
  gap: 10px;
}

.spinner {
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255,255,255,0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ══════════════════════════════════════════════════════════════
   SIDEBAR RESUMEN
   ══════════════════════════════════════════════════════════════ */
.checkout-sidebar {
  position: sticky;
  top: 100px;
}
.checkout-sidebar h3 {
  font-size: 1.2rem;
  margin-bottom: 20px;
  color: #fff;
}

.sidebar-items {
  max-height: 250px;
  overflow-y: auto;
  margin-bottom: 15px;
}

.sidebar-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
  border-bottom: 1px solid rgba(255,255,255,0.05);
}
.sidebar-item:last-child { border-bottom: none; }
.sidebar-item img {
  width: 45px;
  height: 45px;
  object-fit: cover;
  border-radius: 8px;
  background: #fff;
}
.sidebar-item-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}
.sidebar-item-name { font-size: 0.85rem; color: #EAEAEA; }
.sidebar-item-qty { font-size: 0.75rem; color: #8896AB; }
.sidebar-item-price { font-size: 0.9rem; color: #00D4AA; font-weight: 600; }

.sidebar-divider {
  height: 1px;
  background: rgba(255,255,255,0.08);
  margin: 12px 0;
}

.sidebar-row {
  display: flex;
  justify-content: space-between;
  padding: 6px 0;
  color: #CBD5E1;
  font-size: 0.95rem;
}
.shipping-row .free-shipping { color: #00D4AA; font-weight: 600; }
.total-row {
  font-size: 1.3rem;
  font-weight: 800;
  color: #fff;
  padding-top: 10px;
}

/* ══════════════════════════════════════════════════════════════
   ANIMACIONES
   ══════════════════════════════════════════════════════════════ */
.animate-fade-in {
  animation: fadeIn 0.4s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ══════════════════════════════════════════════════════════════
   RESPONSIVE
   ══════════════════════════════════════════════════════════════ */
@media (max-width: 900px) {
  .checkout-layout {
    grid-template-columns: 1fr;
  }
  .checkout-sidebar {
    position: static;
    order: -1;
  }
  .step-line { width: 40px; }
  .credit-card-preview {
    max-width: 100%;
    padding: 20px;
  }
  .card-number-display { font-size: 1.1rem; gap: 12px; }
}

@media (max-width: 600px) {
  .steps-indicator { flex-wrap: wrap; gap: 8px; }
  .step-line { display: none; }
  .form-row { grid-template-columns: 1fr; }
  .step-actions { flex-direction: column; }
  .credit-card-preview {
    padding: 18px;
    border-radius: 14px;
  }
  .card-number-display { font-size: 0.95rem; gap: 8px; letter-spacing: 1px; }
}
</style>
