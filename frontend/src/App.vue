<script setup>
import { RouterView } from 'vue-router';
import BarraNavegacion from './components/BarraNavegacion.vue';
import PiePagina from './components/PiePagina.vue';
import Chatbot from './components/Chatbot.vue';
import NotificacionToast from './components/NotificacionToast.vue'; // Global Toasts
import { useAuthStore } from '@/stores/auth';
import { ref, onMounted, onUnmounted } from 'vue';

const authStore = useAuthStore();
const showScrollToTop = ref(false);

const checkScroll = () => {
  showScrollToTop.value = window.scrollY > 400;
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
  window.addEventListener('scroll', checkScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll);
});
</script>

<template>
  <div id="app-wrapper">
    <BarraNavegacion />
    
    <main>
      <RouterView />
    </main>
    
    <PiePagina />
    <Chatbot />
    <NotificacionToast />
    
    <!-- Botón Scroll to Top -->
    <transition name="fade-slide">
      <button 
        v-if="showScrollToTop" 
        @click="scrollToTop" 
        class="scroll-to-top" 
        aria-label="Subir arriba"
      >
        <span class="material-icons">arrow_upward</span>
      </button>
    </transition>
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

/* Scroll to Top button */
.scroll-to-top {
  position: fixed;
  bottom: 80px; /* Sobre los Toasts */
  right: 24px;
  background: linear-gradient(135deg, var(--color-primary, #00A1FF) 0%, var(--color-primary-dark, #007ecc) 100%);
  color: white;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(0, 161, 255, 0.4);
  z-index: 998;
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.scroll-to-top:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 161, 255, 0.6);
}

.scroll-to-top:active {
  transform: translateY(0);
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.3s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>
