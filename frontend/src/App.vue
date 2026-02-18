<script setup>
import { RouterView } from 'vue-router';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import Chatbot from './components/Chatbot.vue'; // Importar el Chatbot
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

// Eliminar onMounted duplicado
// onMounted(() => {
//   // La recuperación de sesión se maneja en el router/index.js
// });
</script>

<template>
  <div id="app-wrapper">
    <Navbar />
    
    <main>
      <RouterView />
    </main>
    
    <Footer />
    <Chatbot /> <!-- Añadir el componente Chatbot -->
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
