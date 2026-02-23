<template>
  <div class="toast-container">
    <transition-group name="toast-list" tag="div">
      <div 
        v-for="toast in toastStore.toasts" 
        :key="toast.id" 
        class="toast"
        :class="`toast-${toast.type}`"
      >
        <span class="material-icons toast-icon" v-if="toast.type === 'success'">check_circle</span>
        <span class="material-icons toast-icon" v-else-if="toast.type === 'error'">error</span>
        <span class="material-icons toast-icon" v-else-if="toast.type === 'warning'">warning</span>
        <span class="material-icons toast-icon" v-else>info</span>
        
        <p class="toast-message">{{ toast.message }}</p>
        
        <button class="toast-close" @click="toastStore.removeToast(toast.id)">
            <span class="material-icons" style="font-size: 16px;">close</span>
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useToastStore } from '@/stores/toast';

const toastStore = useToastStore();
</script>

<style scoped>
.toast-container {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 12px;
  pointer-events: none; /* Allows clicks to pass through empty space */
}

.toast {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 20px;
  background: rgba(30, 35, 48, 0.85);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: var(--radius-lg);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  min-width: 300px;
  max-width: 400px;
  pointer-events: auto; /* Re-enable clicks on the toast itself */
  color: var(--text-primary);
  font-family: var(--font-primary);
}

.toast-message {
  margin: 0;
  flex-grow: 1;
  font-size: var(--font-size-sm);
  font-weight: var(--font-weight-medium);
}

.toast-icon {
  font-size: 20px;
}

.toast-success .toast-icon { color: var(--color-success); }
.toast-error .toast-icon { color: var(--color-error); }
.toast-warning .toast-icon { color: var(--color-warning); }
.toast-info .toast-icon { color: var(--color-primary); }

.toast-success { border-left: 4px solid var(--color-success); }
.toast-error { border-left: 4px solid var(--color-error); }
.toast-warning { border-left: 4px solid var(--color-warning); }
.toast-info { border-left: 4px solid var(--color-primary); }

.toast-close {
  background: transparent;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4px;
  border-radius: var(--radius-sm);
  transition: all 0.2s;
}

.toast-close:hover {
  background: rgba(255, 255, 255, 0.1);
  color: var(--text-primary);
}

/* Animations */
.toast-list-enter-active,
.toast-list-leave-active {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.toast-list-enter-from {
  opacity: 0;
  transform: translateX(50px) scale(0.9);
}

.toast-list-leave-to {
  opacity: 0;
  transform: translateX(50px) scale(0.9);
}
</style>
