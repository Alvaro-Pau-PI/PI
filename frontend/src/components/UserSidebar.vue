<template>
  <aside class="profile-sidebar glass-card" :class="{ 'menu-open': isMenuOpen }">
    <div class="avatar-section" @click="toggleMenu">
      <div class="avatar-circle">
        {{ userInitial }}
      </div>
      <div class="user-info-mobile">
        <h2 class="sidebar-name">{{ authStore.user?.name || 'Usuario' }}</h2>
        <p class="sidebar-email">{{ authStore.user?.email || 'correo@ejemplo.com' }}</p>
        <RoleBadge class="sidebar-badge" />
      </div>
      <!-- Botón de toggle solo para el móvil -->
      <button class="menu-toggle-btn" @click.stop="toggleMenu">
        <span class="material-icons">{{ isMenuOpen ? 'expand_less' : 'expand_more' }}</span>
      </button>
    </div>
    
    <div class="sidebar-divider desktop-divider"></div>
    
    <nav class="sidebar-menu" :class="{ 'is-open': isMenuOpen }">
      <!-- Tab: Perfil -->
      <router-link to="/profile" class="sidebar-menu-item" :class="{ active: activeTab === 'profile' }">
        <span class="material-icons">manage_accounts</span>
        Datos de la Cuenta
      </router-link>
      
      <!-- Tab: Pedidos -->
      <router-link to="/orders" class="sidebar-menu-item" :class="{ active: activeTab === 'orders' }">
        <span class="material-icons">receipt_long</span>
        Mis pedidos
      </router-link>
      
      <!-- Tab: Favoritos -->
      <router-link to="/favorites" class="sidebar-menu-item" :class="{ active: activeTab === 'favorites' }">
        <span class="material-icons">favorite</span>
        Favoritos
      </router-link>
      
      <div class="sidebar-divider" v-if="isAdmin"></div>

      <!-- Tab Especial: Admin -->
      <router-link v-if="isAdmin" to="/admin" class="sidebar-menu-item admin-link">
        <span class="material-icons">admin_panel_settings</span>
        Panel de Administración
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import RoleBadge from '@/modules/roles/components/RoleBadge.vue';

const isMenuOpen = ref(false);

const toggleMenu = () => {
  // Solo aplicamos el toggle real si estamos en resolución móvil
  if (window.innerWidth <= 860) {
    isMenuOpen.value = !isMenuOpen.value;
  }
};

const props = defineProps({
  activeTab: {
    type: String,
    required: true,
    validator: (value) => ['profile', 'orders', 'favorites'].includes(value)
  }
});

const authStore = useAuthStore();

const userInitial = computed(() => {
  return authStore.user?.name ? authStore.user.name.charAt(0).toUpperCase() : 'U';
});

const isAdmin = computed(() => {
  return authStore.user?.role === 'admin';
});
</script>

<style scoped>
.glass-card {
  background: rgba(30, 34, 43, 0.65);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.profile-sidebar {
  padding: 40px 0;
  display: flex;
  flex-direction: column;
  position: sticky; /* Mantener sticky por si se hace scroll content */
  top: 100px;
}

.avatar-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 0 30px;
  margin-bottom: 25px;
}

.avatar-circle {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0ea5e9, #6366f1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.8em;
  font-weight: 800;
  color: white;
  margin-bottom: 15px;
  box-shadow: 0 10px 25px rgba(14, 165, 233, 0.4);
  text-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.sidebar-name {
  font-size: 1.4em;
  font-weight: 700;
  color: #fff;
  margin-bottom: 5px;
  text-align: center;
}

.sidebar-email {
  color: #94A3B8;
  font-size: 0.9em;
  margin-bottom: 15px;
  text-align: center;
}

.sidebar-divider {
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
  margin: 10px 0;
}

.sidebar-menu {
  display: flex;
  flex-direction: column;
  gap: 5px;
  padding: 15px 20px;
}

.sidebar-menu-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 20px;
  color: #E2E8F0;
  text-decoration: none;
  font-weight: 500;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.sidebar-menu-item:hover {
  background: rgba(255,255,255,0.05);
  color: #0ea5e9;
}

.sidebar-menu-item.active {
  background: rgba(14, 165, 233, 0.1);
  color: #00E4FF;
  box-shadow: inset 3px 0 0 #00E4FF;
}

.sidebar-menu-item .material-icons {
  font-size: 1.3em;
  opacity: 0.8;
}
.sidebar-menu-item.active .material-icons {
  opacity: 1;
}

.sidebar-menu-item.admin-link {
  background: rgba(139, 92, 246, 0.1);
  color: #A78BFA;
  border: 1px solid rgba(139, 92, 246, 0.2);
}

.sidebar-menu-item.admin-link:hover {
  background: rgba(139, 92, 246, 0.2);
  color: #fff;
  border-color: rgba(139, 92, 246, 0.5);
  box-shadow: 0 4px 15px rgba(139, 92, 246, 0.2);
}

.menu-toggle-btn {
  display: none;
}

@media (max-width: 860px) {
  .profile-sidebar {
    position: relative;
    top: 0;
    padding: 20px 15px;
    z-index: 10;
  }

  .avatar-section {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    text-align: left;
    padding: 0 10px;
    gap: 15px;
    margin-bottom: 0px;
    cursor: pointer;
  }
  
  .avatar-circle {
    width: 60px;
    height: 60px;
    margin-bottom: 0;
    font-size: 1.5em;
    flex-shrink: 0;
  }
  
  .user-info-mobile {
    flex-grow: 1;
  }

  .sidebar-name {
    text-align: left;
    font-size: 1.1em;
  }

  .sidebar-email {
    text-align: left;
    margin-bottom: 5px;
    font-size: 0.8em;
  }

  .desktop-divider {
    display: none;
  }

  .menu-toggle-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s;
  }

  .sidebar-menu {
    max-height: 0;
    overflow: hidden;
    padding: 0 20px;
    opacity: 0;
    margin: 0;
    gap: 0;
    pointer-events: none; /* Evitar clicks fantasma si queda margen */
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  .sidebar-menu.is-open {
    max-height: 500px; /* Ajuste para desplegar menú entero */
    padding: 20px;
    opacity: 1;
    margin-top: 15px;
    gap: 5px;
    pointer-events: auto;
    border-top: 1px solid rgba(255,255,255,0.08);
  }
}
</style>
