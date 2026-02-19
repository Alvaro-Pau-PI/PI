<script setup>
import { onMounted } from 'vue';
import '@n8n/chat/style.css';
import { createChat } from '@n8n/chat';

const props = defineProps({
  webhookUrl: {
    type: String,
    // Usa variable de entorno o localhost por defecto
    default: () => {
      const baseUrl = import.meta.env.VITE_N8N_BASE_URL || 'http://localhost:5678';
      return `${baseUrl}/webhook/5627f35d-f3ea-4298-ab19-9c535ccec08d/chat`;
    }
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
:root, body {
  /* Strategy: Define ALL potential variable variations to ensure override */
  
  /* Primary Colors */
  --chat--color-primary: #FF6C00 !important;
  --chat--color-secondary: #FF6C00 !important; 
  --chat--color-dark: #1A1D24 !important;
  --chat--color-light: #FFFFFF !important;

  /* Header */
  --chat--header-background: #FF6C00 !important;
  --chat--header-color: #FFFFFF !important;
  --chat--header--background: #FF6C00 !important;
  --chat--header--color: #FFFFFF !important;

  /* Window */
  --chat--window-background: #242833 !important;
  --chat--window--background: #242833 !important;

  /* Bot Messages (Black/White) */
  --chat--message-bot-background: #000000 !important;
  --chat--message-bot-color: #FFFFFF !important;
  --chat--message--bot--background: #000000 !important;
  --chat--message--bot--color: #FFFFFF !important;

  /* User Messages (Orange/White) */
  --chat--message-user-background: #FF6C00 !important;
  --chat--message-user-color: #FFFFFF !important;
  --chat--message--user--background: #FF6C00 !important;
  --chat--message--user--color: #FFFFFF !important;

  /* Toggle Button */
  --chat--toggle-background: #FF6C00 !important;
  --chat--toggle-hover-background: #e65c00 !important;
  --chat--toggle--background: #FF6C00 !important;
  --chat--toggle--hover--background: #e65c00 !important;
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

/* OVERRIDES DIRECTOS PARA FORZAR ESTILOS */
/* Fondo del chat completo */
.chat-layout,
.chat-window,
.chat-body,
.conversation-view,
.n8n-chat-window,
[class*="chat-window"],
[class*="chat-layout"] {
  background-color: #242833 !important;
  background: #242833 !important;
}

/* Subtítulo de la cabecera en negro */
.chat-header p,
.chat-header span,
div[class*="subtitle"] {
  color: #000000 !important;
}

/* Mensajes del Usuario (Texto Blanco, Fondo Naranja) */
.chat-message-user,
.chat-message-user p, 
.chat-message-user span,
.chat-message-user div,
.chat-message-user * {
  background-color: transparent !important; /* Para que herede del padre si es necesario */
  color: #FFFFFF !important;
  font-weight: 500 !important;
}
.chat-message-user {
  background-color: #FF6C00 !important; /* Solo el contenedor principal naranja */
}

/* Y si n8n usa otra clase para el texto dentro del globo naranja: */
div[class*="message-user"] *,
div[class*="ChatMessageUser"] * {
  color: #FFFFFF !important;
}

/* Mensajes del Bot (Fondo Negro, Texto Blanco) */
.chat-message-bot {
  background-color: #000000 !important;
  color: #FFFFFF !important;
}
.chat-message-bot p, 
.chat-message-bot span,
.chat-message-bot div,
.chat-message-bot * {
  color: #FFFFFF !important;
}

/* Input Area */
.chat-input-container,
.chat-footer {
  background-color: #1A1D24 !important;
}
.chat-input {
  color: #FFFFFF !important;
  background-color: #1A1D24 !important;
}
.chat-input::placeholder {
  color: #888888 !important;
}
</style>
