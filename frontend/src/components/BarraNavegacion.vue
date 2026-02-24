<template>
  <header>
    <div class="cabecera">
      <div class="logo-box">
        <router-link to="/" title="Ir al inicio AlberoPerez Tech">
          <img src="/img/LOGO AlberoPerezTech.png" alt="Logo AlberoPerez Tech" class="logo" />
        </router-link>
      </div>

      <button class="mobile-menu-btn" @click="toggleMobileMenu" aria-label="Abrir menú" :class="{'open': mobileMenuOpen}">
        <span class="material-icons">{{ mobileMenuOpen ? 'close' : 'menu' }}</span>
      </button>

      <!-- Overlay para menú móvil -->
      <transition name="fade">
        <div v-if="mobileMenuOpen" class="mobile-menu-overlay" @click="closeMobileMenu"></div>
      </transition>

      <nav class="nav-box" :class="{ 'nav-box--mobile-open': mobileMenuOpen }" aria-label="Navegación principal">
        <router-link to="/" class="nav-link" @click="closeMobileMenu">Inicio</router-link>
        <router-link to="/products" class="nav-link" @click="closeMobileMenu">Productos</router-link>
        <router-link to="/contact" class="nav-link" @click="closeMobileMenu">Contacto</router-link>
      </nav>

      <div class="iconos-box">
        <!-- CARRITO -->
        <router-link to="/cart" title="Carrito" class="icon-cart-wrapper" aria-label="Ver carrito">
          <span class="material-icons cart-icon" aria-hidden="true">shopping_cart</span>
          <span v-if="cartStore.totalItems > 0" class="cart-badge">{{ cartStore.totalItems }}</span>
        </router-link>

        <!-- USUARIO (Dropdown) -->
        <div v-if="authStore.user" class="user-dropdown-container" @click="toggleDropdown" v-click-outside="closeDropdown">
          <div class="user-trigger">
            <span class="user-name">{{ authStore.user.name }}</span>
            <span class="material-icons user-icon" style="color: #00A1FF;">person</span>
            <span class="material-icons arrow-icon">{{ dropdownOpen ? 'expand_less' : 'expand_more' }}</span>
          </div>
          
          <transition name="dropdown-fade">
            <ul v-if="dropdownOpen" class="dropdown-menu">
              <li>
                <router-link to="/profile" class="dropdown-item">
                  <span class="material-icons">manage_accounts</span> Mi perfil
                </router-link>
              </li>
              <li>
                <router-link to="/orders" class="dropdown-item">
                  <span class="material-icons">receipt_long</span> Mis pedidos
                </router-link>
              </li>
              <li>
                <router-link to="/favorites" class="dropdown-item">
                  <span class="material-icons">favorite</span> Favoritos
                </router-link>
              </li>
              <li v-if="canManage" class="admin-option">
                <router-link to="/admin" class="dropdown-item">
                  <span class="material-icons">admin_panel_settings</span> Panel de Gestión
                </router-link>
              </li>
              <li class="divider"></li>
              <li>
                <button @click="logout" class="dropdown-item logout-btn">
                  <span class="material-icons">logout</span> Cerrar sesión
                </button>
              </li>
            </ul>
          </transition>
        </div>

        <template v-else>
          <router-link to="/login" class="login-btn-nav" title="Iniciar sesión" aria-label="Iniciar sesión">
            <span class="material-icons">account_circle</span>
            <span>Acceder</span>
          </router-link>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';
import { useRouter } from 'vue-router';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useRole } from '@/modules/roles/composables/useRole';

const authStore = useAuthStore();
const cartStore = useCartStore();
const router = useRouter();
const { isAdmin } = useRole();

const canManage = computed(() => isAdmin());
const dropdownOpen = ref(false);
const mobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const closeMobileMenu = () => {
  mobileMenuOpen.value = false;
};

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

const closeDropdown = () => {
  dropdownOpen.value = false;
};

const logout = async () => {
  await authStore.logout();
  closeDropdown();
  router.push('/login');
};

// Directiva personalizada simple para clic fuera (click outside)
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = function(event) {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event);
      }
    };
    document.body.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.body.removeEventListener('click', el.clickOutsideEvent);
  }
};
</script>

<style scoped>
/* Cabecera Principal - Sticky & Glassmorphism */
header {
  position: sticky;
  top: 0;
  z-index: 900;
  background: rgba(26, 29, 36, 0.85); /* Semi-transparente */
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.cabecera {
  max-width: var(--max-width-container, 1400px);
  margin: 0 auto;
  display: grid;
  grid-template-columns: 200px 1fr auto;
  align-items: center;
  padding: 10px 20px;
  gap: 20px;
}

/* Icono Carrito con Badge */
.icon-cart-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  color: var(--color-text);
  text-decoration: none;
  transition: transform 0.2s;
}

.icon-cart-wrapper:hover {
  transform: scale(1.1);
  color: var(--color-primary);
}

.cart-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: #e74c3c;
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
  height: 18px;
  width: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Dropdown Usuario */
.user-dropdown-container {
  position: relative;
  cursor: pointer;
  user-select: none;
}

.user-trigger {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem;
  border-radius: 8px;
  transition: background 0.3s;
}

.user-trigger:hover {
  background: rgba(255, 255, 255, 0.05);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  width: 240px;
  background: #1e1e1e; /* Fondo oscuro sólido para legibilidad */
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  padding: 0.5rem;
  z-index: 1000;
  list-style: none;
  margin-top: 0.5rem;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  color: var(--color-text);
  text-decoration: none;
  border-radius: 8px;
  transition: background 0.2s, color 0.2s;
}

.dropdown-item:hover {
  background: rgba(0, 161, 255, 0.1);
  color: var(--color-primary);
}

.dropdown-item .material-icons {
  font-size: 1.2rem;
  opacity: 0.8;
}

.divider {
  height: 1px;
  background: rgba(255, 255, 255, 0.1);
  margin: 0.5rem 0;
}

.logout-btn {
  width: 100%;
  background: none;
  border: none;
  text-align: left;
  cursor: pointer;
  color: #ff4d4d;
}

.logout-btn:hover {
  background: rgba(255, 77, 77, 0.1);
  color: #ff4d4d;
}

.admin-option .dropdown-item {
  color: #ffd700;
}

/* Animación Fade para Overlay */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Transiciones Dropdown */
.dropdown-fade-enter-active,
.dropdown-fade-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}

.dropdown-fade-enter-from,
.dropdown-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* General BarraNavegacion ajustes */
/* Botón Login Nav */
.login-btn-nav {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 50px;
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.login-btn-nav:hover {
  background: var(--color-primary);
  border-color: var(--color-primary);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 161, 255, 0.3);
}

.login-btn-nav .material-icons {
  font-size: 1.2rem;
}

/* Mobile Menú Button */
.mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  color: var(--text-primary);
  cursor: pointer;
  padding: 5px;
  grid-area: nav;
  justify-self: end;
}

.mobile-menu-btn .material-icons {
  font-size: 2rem;
}

/* Estilos móviles Off-canvas */
@media (max-width: 768px) {
  .cabecera {
    grid-template-columns: auto 1fr auto;
    padding: 12px 16px;
  }

  /* Overlay de fondo */
  .mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 950;
  }

  .mobile-menu-btn {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    z-index: 1000;
    position: relative;
    grid-area: auto; /* reset */
  }
  
  .iconos-box {
    justify-self: end;
    gap: 15px;
  }
  
  /* Menú lateral (Off-canvas) */
  .nav-box {
    display: flex;
    position: fixed;
    top: 0;
    right: -300px;
    width: 260px;
    height: 100vh;
    background: #1A1D24; /* Fondo oscuro sólido */
    border-left: 1px solid rgba(255, 255, 255, 0.1);
    flex-direction: column;
    padding: 80px 20px 20px; /* Space for the close button */
    box-shadow: -10px 0 30px rgba(0,0,0,0.5);
    z-index: 999;
    transition: right 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    align-items: flex-start;
  }
  
  .nav-box--mobile-open {
    right: 0;
  }
  
  .nav-link {
    width: 100%;
    text-align: left;
    padding: 16px;
    font-size: 1.2rem;
    border-radius: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: background 0.2s, padding-left 0.2s;
  }
  
  .nav-link:hover {
    background: rgba(0, 161, 255, 0.1);
    padding-left: 22px;
  }
}
</style>
