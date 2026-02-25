<template>
  <div class="admin-users container">
    <div class="header-actions">
      <!-- Título de la sección -->
      <h1>{{ $t('admin.users_title') }}</h1>
      <div class="search-bar">
        <!-- Input de búsqueda con debounce -->
        <input 
          v-model="search" 
          type="text" 
          :placeholder="$t('admin.search_users_ph')" 
          @input="debounceSearch"
          class="form-input"
        />
        <span class="material-icons search-icon">search</span>
      </div>
    </div>

    <!-- Tabla de Usuarios con soporte responsivo -->
    <div class="table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ $t('admin.user_label') }}</th>
            <th>Email</th>
            <th>{{ $t('admin.role_label') }}</th>
            <th>{{ $t('admin.reg_date_label') }}</th>
            <th>{{ $t('admin.actions_label') }}</th>
          </tr>
        </thead>
        <tbody>
          <!-- Listado de usuarios -->
          <tr v-for="user in users" :key="user.id">
            <td data-label="ID">#{{ user.id }}</td>
            <td :data-label="$t('admin.user_label')">
              <div class="user-info">
                <div class="user-avatar">{{ user.name.charAt(0).toUpperCase() }}</div>
                <span>{{ user.name }}</span>
              </div>
            </td>
            <td data-label="Email">{{ user.email }}</td>
            <td :data-label="$t('admin.role_label')">
              <!-- Selector de rol dinámico -->
              <select 
                v-model="user.role" 
                @change="updateRole(user)" 
                class="role-select"
                :class="user.role"
              >
                <option value="user">{{ $t('admin.role_user') }}</option>
                <option value="admin">{{ $t('admin.role_admin') }}</option>
              </select>
            </td>
            <td :data-label="$t('admin.reg_date_label')">{{ formatDate(user.created_at) }}</td>
            <td :data-label="$t('admin.actions_label')" class="actions-cell">
              <!-- Botón de eliminar -->
              <button @click="confirmDelete(user)" class="btn-icon delete" :title="$t('admin.actions_label')">
                <span class="material-icons">delete</span>
              </button>
            </td>
          </tr>
          <!-- Estado vacío o cargando -->
          <tr v-if="users.length === 0 && !loading">
            <td colspan="6" class="empty-state">{{ $t('admin.empty_users') }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación de usuarios -->
    <div class="pagination" v-if="totalPages > 1">
      <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="btn-page">
        <span class="material-icons">chevron_left</span>
      </button>
      <span class="page-info">{{ currentPage }} de {{ totalPages }}</span>
      <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="btn-page">
        <span class="material-icons">chevron_right</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import http from '@/services/http';
import { useToastStore } from '@/stores/toast';

/**
 * Gestión de Usuarios: Permite listar, cambiar roles y eliminar usuarios.
 */

const toast = useToastStore();
const users = ref([]);
const loading = ref(false);
const search = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
let debounceTimer = null;

// Obtener usuarios con paginación y búsqueda
const fetchUsers = async (page = 1) => {
  loading.value = true;
  try {
    const response = await http.get('/api/admin/users', {
      params: { 
        page,
        search: search.value
      }
    });
    users.value = response.data.data;
    currentPage.value = response.data.current_page;
    totalPages.value = response.data.last_page;
  } catch (err) {
    toast.addToast("Error al cargar los usuarios.", "error");
  } finally {
    loading.value = false;
  }
};

// Implementación de retardo para la búsqueda
const debounceSearch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchUsers(1);
  }, 500);
};

// Actualizar el rol de un usuario (admin/user)
const updateRole = async (user) => {
  try {
    await http.patch(`/api/admin/users/${user.id}/role`, { role: user.role });
    toast.addToast(`Rol de ${user.name} actualizado.`, "success");
  } catch (err) {
    toast.addToast(err.response?.data?.message || "Error al actualizar el rol.", "error");
    // Revertir cambio local si falla
    fetchUsers(currentPage.value);
  }
};

// Confirmar y procesar eliminación de un usuario
const confirmDelete = async (user) => {
  if (!confirm(`¿Estás seguro de eliminar a "${user.name}"?`)) return;

  try {
    await http.delete(`/api/admin/users/${user.id}`);
    toast.addToast("Usuario eliminado correctamente.", "success");
    fetchUsers(currentPage.value);
  } catch (err) {
    toast.addToast(err.response?.data?.message || "Error al eliminar usuario.", "error");
  }
};

// Navegar entre páginas
const changePage = (page) => {
  fetchUsers(page);
};

// Utilidad para formatear fechas
const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

onMounted(() => fetchUsers());
</script>

<style scoped>
.container {
  padding: var(--spacing-xl);
  max-width: var(--max-width-container);
  margin: 0 auto;
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-lg);
  gap: var(--spacing-md);
}

.search-bar {
  position: relative;
  width: 100%;
  max-width: 350px;
}

.search-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-secondary);
  pointer-events: none;
}

.admin-table {
  width: 100%;
  border-collapse: collapse;
  background: var(--bg-card);
  border-radius: var(--radius-md);
  overflow: hidden;
}

.admin-table th, 
.admin-table td {
  padding: var(--spacing-md) var(--spacing-lg);
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.admin-table th {
  background: var(--bg-elevated);
  color: var(--text-secondary);
  font-size: 0.85rem;
  text-transform: uppercase;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 32px;
  height: 32px;
  background: var(--color-primary);
  color: #000;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.9rem;
}

.role-select {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: white;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 0.85rem;
  outline: none;
}

.role-select.admin {
  color: #fbbf24;
  border-color: rgba(251, 191, 36, 0.3);
}

.actions-cell {
  display: flex;
  gap: 8px;
}

.btn-icon {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: var(--text-secondary);
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
}

.btn-icon.delete:hover {
  color: #ff4757;
  border-color: #ff4757;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-top: var(--spacing-xl);
}

.btn-page {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  color: white;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.btn-page:disabled { opacity: 0.3; cursor: not-allowed; }

@media (max-width: 768px) {
  .admin-table, thead, tbody, tr, td {
    display: block;
    width: 100%;
  }
  thead { display: none; }
  tr {
    margin-bottom: 16px;
    background: var(--bg-card);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 10px;
  }
  td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }
  td::before {
    content: attr(data-label);
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.8rem;
    text-transform: uppercase;
  }
}
</style>
