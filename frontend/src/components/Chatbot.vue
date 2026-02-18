<script setup>
import { onMounted } from 'vue';
import '@n8n/chat/style.css';
import { createChat } from '@n8n/chat';

const props = defineProps({
  webhookUrl: {
    type: String,
    // Usa variable de entorno o localhost por defecto
    default: import.meta.env.VITE_N8N_WEBHOOK_URL || 'http://localhost:5678/webhook/MARK_PLACEHOLDER/chat'
  }
});

onMounted(() => {
  createChat({
    webhookUrl: props.webhookUrl,
    showWelcomeScreen: false,
    initialMessages: [
      '¡Hola! 👋 Bienvenido a AlberoPerez Tech.',
      '¿En qué te puedo ayudar hoy?'
    ],
    i18n: {
      en: {
        title: 'Asistente AlberoPerez',
        subtitle: 'Tu experto en hardware 24/7',
        inputPlaceholder: 'Type a message...',
      },
      es: {
        title: 'Asistente AlberoPerez',
        subtitle: 'Tu experto en hardware 24/7',
        inputPlaceholder: 'Escribe aquí...',
      }
    }
  });
});
</script>

<template>
  <!-- El chat se renderiza automáticamente en el body, 
       no necesitamos template visible aquí -->
  <div id="n8n-chat-placeholder"></div>
</template>

<style>
/* Estilos globales para forzar el tema del Chatbot */
:root {
  --chat--color--primary: #FF6C00 !important;
  --chat--color--secondary: #242833 !important;
  --chat--color--dark: #1A1D24 !important;
  --chat--color--light: #EAEAEA !important;

  --chat--window--background-color: #1A1D24 !important;
  --chat--header--background-color: #242833 !important;
  --chat--header--color: #EAEAEA !important;

  --chat--message--bot--background-color: #242833 !important;
  --chat--message--bot--color: #EAEAEA !important;

  --chat--message--user--background-color: #FF6C00 !important;
  --chat--message--user--color: #FFFFFF !important;

  --chat--toggle--background-color: #FF6C00 !important;
  --chat--toggle--hover--background-color: #e65c00 !important;
  --chat--toggle--active--background-color: #e65c00 !important;
}

/* Ocultar marca de agua de n8n */
.chat-powered-by,
.n8n-chat-branding,
.n8n-chat-widget-footer,
.chat-footer a[href*="n8n.io"] {
  display: none !important;
  visibility: hidden !important;
  opacity: 0 !important;
  pointer-events: none !important;
  height: 0 !important;
  padding: 0 !important;
}

/* Asegurar que el chat esté por encima de todo */
.n8n-chat-widget {
  z-index: 9999 !important;
}
</style>
