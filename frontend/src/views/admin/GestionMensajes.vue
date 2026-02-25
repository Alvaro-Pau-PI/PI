<template>
  <div class="admin-contacts container">
    <div class="header-actions">
      <!-- Título con i18n -->
      <h1>{{ $t('admin.contacts_title') }}</h1>
      <div class="search-bar">
        <input 
          v-model="search" 
          type="text" 
          :placeholder="$t('admin.search_contacts_ph')" 
          @input="debounceSearch"
          class="form-input"
        />
        <span class="material-icons search-icon">search</span>
      </div>
    </div>

    <!-- Tabla de mensajes responsiva -->
    <div class="table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>{{ $t('admin.user_label') }}</th>
            <th>Email</th>
            <th>{{ $t('contact.subject') }}</th>
            <th>{{ $t('admin.date_label') }}</th>
            <th>{{ $t('admin.actions_label') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="msg in contacts" :key="msg.id" class="message-row" @click="openMessage(msg)">
            <td data-label="ID">#{{ msg.id }}</td>
            <td :data-label="$t('admin.user_label')">{{ msg.name }}</td>
            <td data-label="Email">{{ msg.email }}</td>
            <td :data-label="$t('contact.subject')">{{ truncateText(msg.subject, 30) }}</td>
            <td :data-label="$t('admin.date_label')">{{ formatDate(msg.created_at) }}</td>
            <td :data-label="$t('admin.actions_label')" class="actions-cell">
              <button @click.stop="confirmDelete(msg)" class="btn-icon delete" :title="$t('admin.delete_contact_confirm')">
                <span class="material-icons">delete</span>
              </button>
            </td>
          </tr>
          <!-- Estado vacío -->
          <tr v-if="contacts.length === 0 && !loading">
            <td colspan="6" class="empty-state">{{ $t('admin.empty_contacts') }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div class="pagination" v-if="totalPages > 1">
      <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="btn-page">
        <span class="material-icons">chevron_left</span>
      </button>
      <span class="page-info">{{ currentPage }} de {{ totalPages }}</span>
      <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="btn-page">
        <span class="material-icons">chevron_right</span>
      </button>
    </div>

    <!-- Modal para leer el mensaje completo -->
    <div v-if="selectedMsg" class="modal-overlay" @click="selectedMsg = null">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h2>{{ selectedMsg.subject }}</h2>
          <button class="close-btn" @click="selectedMsg = null">
            <span class="material-icons">close</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="msg-meta">
            <p><strong>De:</strong> {{ selectedMsg.name }} ({{ selectedMsg.email }})</p>
            <p><strong>Fecha:</strong> {{ formatDate(selectedMsg.created_at) }}</p>
          </div>
          <div class="msg-text">
            {{ selectedMsg.message }}
          </div>
        </div>
        <div class="modal-footer">
          <a :href="'mailto:' + selectedMsg.email" class="btn-primary">Responder</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import http from '@/services/http';
import { useToastStore } from '@/stores/toast';

/**
 * Vista de administración para gestionar los mensajes de contacto.
 * Incluye búsqueda, paginación y visualización de detalles en modal.
 */

const toast = useToastStore();
const contacts = ref([]);
const loading = ref(false);
const search = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
const selectedMsg = ref(null);
let debounceTimer = null;

// Cargar mensajes desde el backend Laravel
const fetchContacts = async (page = 1) => {
  loading.value = true;
  try {
    const response = await http.get('/api/admin/contacts', {
      params: { 
        page,
        search: search.value
      }
    });
    contacts.value = response.data.data;
    currentPage.value = response.data.current_page;
    totalPages.value = response.data.last_page;
  } catch (err) {
    toast.addToast("Error al cargar los mensajes de contacto.", "error");
  } finally {
    loading.value = false;
  }
};

// Buscador con retardo (debounce)
const debounceSearch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchContacts(1);
  }, 500);
};

// Confirmar y eliminar mensaje
const confirmDelete = async (msg) => {
  if (!confirm(`¿Estás seguro de eliminar el mensaje de ${msg.name}?`)) return;

  try {
    await http.delete(`/api/admin/contacts/${msg.id}`);
    toast.addToast("Mensaje eliminado correctamente.", "success");
    fetchContacts(currentPage.value);
  } catch (err) {
    toast.addToast("Error al eliminar el mensaje.", "error");
  }
};

// Abrir detalle del mensaje
const openMessage = (msg) => {
  selectedMsg.value = msg;
};

// Cambiar de página
const changePage = (page) => {
  fetchContacts(page);
};

// Utilidades de formato
const truncateText = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

onMounted(() => fetchContacts());
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
  max-width: 400px;
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

.message-row {
  cursor: pointer;
  transition: background 0.2s;
}

.message-row:hover {
  background: rgba(255, 255, 255, 0.03);
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
  font-size: 0.9rem;
  text-transform: uppercase;
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
  padding: 8px;
  border-radius: var(--radius-sm);
  transition: all 0.2s;
  display: flex;
  align-items: center;
}

.btn-icon.delete:hover {
  color: #ff4757;
  border-color: #ff4757;
}

/* Modales */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  backdrop-filter: blur(5px);
}

.modal-content {
  background: var(--bg-card);
  width: 90%;
  max-width: 600px;
  border-radius: var(--radius-lg);
  border: 1px solid rgba(255, 255, 255, 0.1);
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
}

.modal-header {
  padding: var(--spacing-lg);
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h2 {
  font-size: 1.25rem;
  margin: 0;
  color: var(--color-primary);
}

.close-btn {
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
}

.modal-body {
  padding: var(--spacing-lg);
}

.msg-meta {
  margin-bottom: var(--spacing-md);
  padding-bottom: var(--spacing-md);
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.msg-meta p { margin: 4px 0; }

.msg-text {
  line-height: 1.6;
  white-space: pre-wrap;
  color: var(--text-primary);
}

.modal-footer {
  padding: var(--spacing-lg);
  background: var(--bg-elevated);
  text-align: right;
}

.btn-primary {
  background: var(--color-primary);
  color: #000;
  padding: 10px 20px;
  border-radius: var(--radius-sm);
  text-decoration: none;
  font-weight: 600;
  display: inline-block;
}

/* Paginación */
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
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.btn-page:disabled { opacity: 0.3; cursor: not-allowed; }

/* RESPONSIVE */
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
  td:last-child { border-bottom: none; }
  td::before {
    content: attr(data-label);
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.8rem;
    text-transform: uppercase;
  }
}
</style>
