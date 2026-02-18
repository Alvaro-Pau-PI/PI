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
        <router-link to="/sostenibilidad" class="nav-link eco-link">🌱 Sostenibilitat</router-link>
        <router-link v-if="canManage" to="/admin/products" class="nav-link admin-link">Gestió</router-link>
      </nav>

      <div class="iconos-box">
        <template v-if="authStore.user">
          <span class="user-name">{{ authStore.user.name }}</span>
          <router-link to="/profile" title="El meu perfil" class="icon-user" aria-label="Ir a mi perfil">
            <span class="material-icons" style="color: #00A1FF;" aria-hidden="true">person</span>
          </router-link>
          <button @click="logout" title="Tancar sessió" aria-label="Cerrar sesión" class="icon-logout" style="background:none; border:none; cursor:pointer;">
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
/* Los estilos ahora se gestionan desde assets/css/4-layout/_header.css */
</style>
