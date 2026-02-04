<template>
  <span v-if="roleLabel" :class="['role-badge', roleClass]">
    {{ roleLabel }}
  </span>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const roleId = computed(() => authStore.user?.role_id);

const roleLabel = computed(() => {
    switch (roleId.value) {
        case 1: return 'Admin';
        case 2: return 'Venedor';
        case 3: return 'Editor';
        case 4: return 'Usuari';
        default: return null;
    }
});

const roleClass = computed(() => {
    switch (roleId.value) {
        case 1: return 'badge-admin';
        case 2: return 'badge-vendor';
        case 3: return 'badge-editor';
        default: return 'badge-user';
    }
});
</script>

<style scoped>
.role-badge {
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.8em;
    font-weight: bold;
    text-transform: uppercase;
    margin-left: 10px;
}
.badge-admin { background: #e74c3c; color: white; }
.badge-vendor { background: #f39c12; color: white; }
.badge-editor { background: #3498db; color: white; }
.badge-user { background: #95a5a6; color: white; }
</style>
