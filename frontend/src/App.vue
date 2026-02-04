<script setup>
import { onMounted } from 'vue';
import { RouterView } from 'vue-router';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

onMounted(() => {
  // Intentar recuperar sesión al cargar
  authStore.fetchUser();
});
</script>

<template>
  <div id="app-wrapper">
    <Navbar />
    
    <main>
      <RouterView />
    </main>
    
    <Footer />
  </div>
</template>

<style>
/* Estilos globales básicos si no están en styles.css */
#app-wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
main {
  flex: 1;
}
</style>
