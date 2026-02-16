<template>
  <header>
    <div class="cabecera">
      <div class="logo-box">
        <router-link to="/" title="Ir al inicio AlberoPerez Tech">
          <img src="/img/LOGO AlberoPerezTech.png" alt="Logo AlberoPerez Tech" class="logo" />
        </router-link>
      </div>

      <nav class="nav-box" aria-label="Navegación principal">
        <router-link to="/" class="nav-link">Inici</router-link>
        <router-link to="/products" class="nav-link">Productes</router-link>
        <router-link to="/contact" class="nav-link">Contacte</router-link>
        <router-link v-if="canManage" to="/profile" class="nav-link admin-link">Gestió</router-link>
      </nav>

      <div class="iconos-box">
        <template v-if="authStore.user">
          <span class="user-name">{{ authStore.user.name }}</span>
          <RoleBadge />
          <router-link to="/profile" title="El meu perfil" class="icon-user" aria-label="Ir a mi perfil">
            <span class="material-icons" style="color: #00A1FF;" aria-hidden="true">person</span>
          </router-link>
          <button @click="logout" title="Tancar sessió" aria-label="Cerrar sesión" style="background:none; border:none; color:#EAEAEA; cursor:pointer;">
            <span class="material-icons" aria-hidden="true">logout</span>
          </button>
        </template>
        <template v-else>
          <router-link to="/login" title="Iniciar sessió" class="icon-user" aria-label="Iniciar sesión">
            <span class="material-icons" aria-hidden="true">person_outline</span>
          </router-link>
        </template>
        
        <router-link to="/cart" title="Carrito de compras" class="icon-cart" aria-label="Ver carrito de compras">
          <span class="material-icons" aria-hidden="true">shopping_cart</span>
        </router-link>
      </div>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { computed } from 'vue';
import RoleBadge from '@/modules/roles/components/RoleBadge.vue';
import { useRole } from '@/modules/roles/composables/useRole';

const authStore = useAuthStore();
const router = useRouter();
const { isAdmin } = useRole();

const canManage = computed(() => isAdmin());

const logout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>

<style scoped>
.cabecera {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  background-color: #1A1D24;
  border-bottom: 1px solid #3A4150;
}
.nav-link {
  color: #9BA3B0;
  margin: 0 10px;
  text-decoration: none;
  font-weight: 500;
}
.nav-link:hover, .router-link-active {
  color: #00A1FF;
}
.admin-link {
  color: #e74c3c !important;
}
.iconos-box {
  display: flex;
  align-items: center;
  gap: 12px;
}
.user-name {
  font-size: 0.9em;
  color: #9BA3B0;
}
</style>
