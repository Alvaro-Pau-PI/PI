<template>
  <div class="admin-products container">
    <div class="header-actions">
      <h1>Gestión de Productos</h1>
      <div class="action-buttons" style="display: flex; gap: 10px;">
        <button @click="triggerFileInput" class="btn-primary" style="background-color: #2ed573; color: white;">
          <span class="material-icons">upload_file</span> Importar Excel
        </button>
        <button @click="exportExcel" class="btn-primary" style="background-color: #f39c12; color: white;">
          <span class="material-icons">download</span> Exportar Excel
        </button>
        <button @click="openCreateModal" class="btn-primary">
          <span class="material-icons">add</span> Nuevo Producto
        </button>
        <input type="file" ref="fileInput" @change="handleFileUpload" accept=".xlsx, .xls, .csv" style="display: none;" />
      </div>
    </div>

    <!-- Los Toast asumen la gestión de errores/éxitos ahora -->

    <!-- Tabla de Productos -->
    <div class="table-container">
      <table class="products-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id">
            <td data-label="ID">#{{ product.id }}</td>
            <td data-label="Nombre">
              <div class="product-name">
                <span class="name-text">{{ product.name }}</span>
                <span v-if="product.eco_score > 70" class="eco-badge">🌱</span>
              </div>
            </td>
            <td data-label="Categoría">{{ product.category }}</td>
            <td data-label="Precio">{{ product.price }}€</td>
            <td data-label="Stock" :class="{'low-stock': product.stock < 5}">{{ product.stock }}</td>
            <td data-label="Acciones" class="actions-cell">
              <button @click="editProduct(product)" class="btn-icon" title="Editar">
                <span class="material-icons">edit</span>
              </button>
              <button @click="confirmDelete(product)" class="btn-icon delete" title="Eliminar">
                <span class="material-icons">delete</span>
              </button>
            </td>
          </tr>
          <tr v-if="products.length === 0">
            <td colspan="6" class="empty-state">
              <div class="empty-state-content">
                <span class="material-icons empty-icon">inventory_2</span>
                <h3>No hay productos disponibles</h3>
                <p>Empieza añadiendo tu primer producto o importando un Excel.</p>
                <button @click="openCreateModal" class="btn-primary mt-3">
                  <span class="material-icons">add</span> Nuevo Producto
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Formulario -->
    <transition name="modal-fade">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <h2>{{ editingProduct ? 'Editar Producto' : 'Nuevo Producto' }}</h2>
        <FormularioProducto 
          :product="editingProduct" 
          :loading="loading"
          @submit="handleSave"
          @cancel="closeModal"
        />
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import http from '@/services/http';
import FormularioProducto from './FormularioProducto.vue';
import { useToastStore } from '@/stores/toast';

const toast = useToastStore();

const products = ref([]);
const loading = ref(false);
const showModal = ref(false);
const editingProduct = ref(null);
const fileInput = ref(null);

const triggerFileInput = () => {
  if (fileInput.value) {
    fileInput.value.click();
  }
};

const exportExcel = async () => {
  try {
    const response = await http.get('/api/products/export', { responseType: 'blob' });
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'productos.csv');
    document.body.appendChild(link);
    link.click();
    toast.addToast("Excel exportado con éxito", "success");
    document.body.removeChild(link);
  } catch (err) {
    toast.addToast("Error al exportar los productos.", "error");
    console.error(err);
  }
};

const handleFileUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('file', file);

  loading.value = true;

  try {
    const response = await http.post('/api/products/import', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    toast.addToast(response.data.message || "Excel importado correctamente.", "success");
    fetchProducts();
  } catch (err) {
    toast.addToast(err.response?.data?.message || "Error al importar el archivo Excel.", "error");
    console.error(err);
  } finally {
    loading.value = false;
    event.target.value = '';
  }
};

const fetchProducts = async () => {
  loading.value = true;
  try {
    // Pedimos 100 productos por página para el panel de admin
    const response = await http.get('/api/products?page=1&per_page=100'); 
    products.value = response.data.data;
  } catch (err) {
    toast.addToast("Error al cargar productos: " + err.message, "error");
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  editingProduct.value = null;
  showModal.value = true;
};

const editProduct = (product) => {
  editingProduct.value = product;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingProduct.value = null;
};

const handleSave = async (formDataEvent) => {
  loading.value = true;
  
  try {
    const formData = new FormData();
    // Añadir todos los campos del producto (excepto arrays extra / control)
    for (const key in formDataEvent) {
      if (!['imageFile', 'image', 'additionalImages', 'images', 'keepImages', 'removeMainImage'].includes(key)) {
        const val = formDataEvent[key];
        if (val !== null && val !== undefined) {
          // Convertir booleanos a 1/0 para la request
          if (typeof val === 'boolean') {
             formData.append(key, val ? '1' : '0');
          } else {
             formData.append(key, val);
          }
        }
      }
    }
    
    // Bandera para borrar foto principal
    if (formDataEvent.removeMainImage) {
      formData.append('remove_main_image', '1');
    }

    // Si hay una nueva imagen subida
    if (formDataEvent.imageFile) {
      formData.append('image', formDataEvent.imageFile);
    }

    // Si hay nuevas imágenes adicionales
    if (formDataEvent.additionalImages && formDataEvent.additionalImages.length > 0) {
      formDataEvent.additionalImages.forEach(file => {
        formData.append('images[]', file);
      });
    }

    // Array de imágenes que el usuario NO ha borrado del servidor
    if (formDataEvent.keepImages && formDataEvent.keepImages.length > 0) {
      formDataEvent.keepImages.forEach(imgUrl => {
         // Eliminamos rutas completas para guardar solo el path relativo si VITE inyecta servidor local
         const relativePath = imgUrl.replace(/^https?:\/\/[^\/]+/, '');
         formData.append('keep_images[]', relativePath);
      });
    }

    if (editingProduct.value) {
      formData.append('_method', 'PUT'); // Trick for Laravel PUT with FormData
      const response = await http.post(`/api/products/${editingProduct.value.id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      toast.addToast("Producto actualizado correctamente.", "success");
      const index = products.value.findIndex(p => p.id === editingProduct.value.id);
      if (index !== -1) products.value[index] = response.data;
    } else {
      const response = await http.post('/api/products', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      toast.addToast("Producto creado exitosamente.", "success");
      products.value.unshift(response.data);
    }
    closeModal();
  } catch (err) {
    toast.addToast(err.response?.data?.message || "Error al guardar producto.", "error");
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const confirmDelete = async (product) => {
  if (!confirm(`¿Estás seguro de eliminar "${product.name}"?`)) return;

  try {
    await http.delete(`/api/products/${product.id}`);
    products.value = products.value.filter(p => p.id !== product.id);
    toast.addToast("Producto eliminado.", "success");
  } catch (err) {
    toast.addToast("No se pudo eliminar el producto.", "error");
  }
};

onMounted(() => {
  fetchProducts();
});
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
}

.products-table {
  width: 100%;
  border-collapse: collapse;
  background: var(--bg-card);
  border-radius: var(--radius-md);
  overflow: hidden;
}

.products-table th, 
.products-table td {
  padding: var(--spacing-md) var(--spacing-lg);
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.products-table tbody tr {
  transition: all 0.2s ease;
}

.products-table tbody tr:hover {
  background: rgba(255, 255, 255, 0.02);
}

.products-table th {
  background: var(--bg-elevated);
  font-weight: 600;
  color: var(--text-secondary);
}

.actions-cell {
  display: flex;
  gap: var(--spacing-sm);
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
  justify-content: center;
}

.btn-icon:hover {
  background: var(--bg-elevated);
  color: var(--color-primary);
}

.btn-icon.delete:hover {
  color: #ff4757;
}

.btn-primary {
  background: var(--color-primary);
  color: #000;
  border: none;
  padding: var(--spacing-sm) var(--spacing-lg);
  display: flex;
  align-items: center;
  gap: 8px;
  border-radius: var(--radius-md);
  font-weight: bold;
  cursor: pointer;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(4px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: var(--bg-card);
  padding: var(--spacing-xl);
  border-radius: var(--radius-xl);
  width: 90%;
  max-width: 700px;
  max-height: 90vh;
  overflow-y: auto;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Transición para el modal */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-fade-enter-active .modal-content,
.modal-fade-leave-active .modal-content {
  transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.modal-fade-enter-from .modal-content,
.modal-fade-leave-to .modal-content {
  transform: scale(0.95) translateY(20px);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: var(--spacing-3xl) var(--spacing-xl);
}

.empty-state-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--spacing-sm);
  color: var(--text-secondary);
}

.empty-icon {
  font-size: 64px;
  color: var(--border-color);
  margin-bottom: var(--spacing-sm);
}

.empty-state-content h3 {
  color: var(--text-primary);
  margin: 0;
}

.mt-3 {
  margin-top: var(--spacing-lg);
}

.modal-content h2 {
  margin-bottom: var(--spacing-lg);
  color: var(--color-primary);
}

.alert {
  padding: var(--spacing-md);
  border-radius: var(--radius-md);
  margin-bottom: var(--spacing-md);
}

.alert-error {
  background: rgba(255, 71, 87, 0.1);
  border: 1px solid #ff4757;
  color: #ff4757;
}

.alert-success {
  background: rgba(46, 213, 115, 0.1);
  border: 1px solid #2ed573;
  color: #2ed573;
}

/* ══════════════════════════════════════════════════════════════
   RESPONSIVO: TABLAS COMO TARJETAS MÓVILES
   ══════════════════════════════════════════════════════════════ */
@media (max-width: 860px) {
  .header-actions {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .action-buttons {
    flex-wrap: wrap;
    width: 100%;
  }

  .action-buttons button {
    flex: 1;
    justify-content: center;
    font-size: 0.9em;
    padding: 10px;
  }
}

@media (max-width: 768px) {
  .table-container {
    background: transparent;
    border: none;
    box-shadow: none;
  }

  .products-table,
  .products-table tbody,
  .products-table tr,
  .products-table td {
    display: block;
    width: 100%;
  }

  .products-table thead {
    display: none; /* Ocultar cabeceras en móvil */
  }

  .products-table tr {
    margin-bottom: 20px;
    background: var(--bg-card);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 10px;
  }

  .products-table td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 10px;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    text-align: right; /* Alinear valor a la derecha */
  }

  .products-table td:last-child {
    border-bottom: none;
  }

  /* Etiqueta inyectada vía dataset CSS */
  .products-table td::before {
    content: attr(data-label);
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    margin-right: 15px;
    text-align: left;
  }

  .actions-cell {
    justify-content: flex-end;
  }
}
</style>
