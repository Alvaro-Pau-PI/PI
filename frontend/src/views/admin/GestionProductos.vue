<template>
  <div class="admin-products container">
    <div class="header-actions">
      <h1>{{ $t('admin.products_title') }}</h1>
      <div class="action-buttons">
        <!-- Botón para importar productos desde Excel -->
        <button @click="triggerFileInput" class="btn-primary">
          <span class="material-icons">upload_file</span> Importar
        </button>
        <!-- Botón para exportar productos a Excel -->
        <button @click="exportExcel" class="btn-primary">
          <span class="material-icons">download</span> Exportar
        </button>
        <!-- Botón para crear un nuevo producto individual -->
        <button @click="openCreateModal" class="btn-primary btn-new">
          <span class="material-icons">add</span> Nuevo
        </button>
        <input type="file" ref="fileInput" @change="handleFileUpload" accept=".xlsx, .xls, .csv" style="display: none;" />
      </div>
    </div>

    <!-- Tabla de Productos Responsiva -->
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
                <!-- Distintivo de sostenibilidad -->
                <span v-if="product.eco_score > 70" class="eco-badge">🌱</span>
                <!-- Indicador de oferta activa -->
                <span v-if="product.is_offer_active" class="offer-badge" title="Oferta Activa">🔥 -{{ product.discount_percentage }}%</span>
              </div>
            </td>
            <td data-label="Categoría">{{ product.category }}</td>
            <td data-label="Precio">
              <div v-if="product.is_offer_active" class="price-admin-wrapper">
                <span class="old-price">{{ product.price }}€</span>
                <span class="new-price">{{ product.discount_price }}€</span>
              </div>
              <span v-else>{{ product.price }}€</span>
            </td>
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
          <!-- Estado si no hay productos -->
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

    <!-- Modal para el Formulario de Producto -->
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

/**
 * Gestión de Productos: Lista, edita, elimina e importa/exporta productos.
 */

const toast = useToastStore();

const products = ref([]);
const loading = ref(false);
const showModal = ref(false);
const editingProduct = ref(null);
const fileInput = ref(null);

// Activar el selector de archivos oculto
const triggerFileInput = () => {
  if (fileInput.value) {
    fileInput.value.click();
  }
};

// Exportar productos a formato Excel/CSV
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

// Manejar la subida de un nuevo archivo Excel para importar
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

// Obtener la lista de productos del servidor
const fetchProducts = async () => {
  loading.value = true;
  try {
    // Obtenemos una lista ámplia para la gestión de administración
    const response = await http.get('/api/products?per_page=100'); 
    products.value = response.data.data;
  } catch (err) {
    toast.addToast("Error al cargar productos: " + err.message, "error");
  } finally {
    loading.value = false;
  }
};

// Abrir modal de creación
const openCreateModal = () => {
  editingProduct.value = null;
  showModal.value = true;
};

// Abrir modal de edición con los datos del producto
const editProduct = (product) => {
  editingProduct.value = product;
  showModal.value = true;
};

// Cerrar el modal y limpiar el estado
const closeModal = () => {
  showModal.value = false;
  editingProduct.value = null;
};

// Lógica para guardar (crear o actualizar) un producto usando FormData
const handleSave = async (formDataEvent) => {
  loading.value = true;
  
  try {
    const formData = new FormData();
    // Procesar todos los campos menos los de archivos o control interno
    for (const key in formDataEvent) {
      if (!['imageFile', 'image', 'additionalImages', 'images', 'keepImages', 'removeMainImage'].includes(key)) {
        const val = formDataEvent[key];
        if (val !== null && val !== undefined) {
          // Laravel espera 1/0 para booleanos en FormData
          if (typeof val === 'boolean') {
             formData.append(key, val ? '1' : '0');
          } else {
             formData.append(key, val);
          }
        }
      }
    }
    
    // Bandera para eliminar la foto principal si el usuario lo marcó
    if (formDataEvent.removeMainImage) {
      formData.append('remove_main_image', '1');
    }

    // Adjuntar nueva imagen principal si existe
    if (formDataEvent.imageFile) {
      formData.append('image', formDataEvent.imageFile);
    }

    // Adjuntar nuevas imágenes adicionales para la galería
    if (formDataEvent.additionalImages && formDataEvent.additionalImages.length > 0) {
      formDataEvent.additionalImages.forEach(file => {
        formData.append('images[]', file);
      });
    }

    // Mantener imágenes existentes en el servidor
    if (formDataEvent.keepImages && formDataEvent.keepImages.length > 0) {
      formDataEvent.keepImages.forEach(imgUrl => {
         const relativePath = imgUrl.replace(/^https?:\/\/[^\/]+/, '');
         formData.append('keep_images[]', relativePath);
      });
    }

    if (editingProduct.value) {
      // Usamos POST con _method=PUT para que Laravel acepte archivos en una actualización
      formData.append('_method', 'PUT'); 
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

// Eliminar un producto físicamente
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

.product-name {
  display: flex;
  align-items: center;
  gap: 8px;
}

.eco-badge {
    filter: drop-shadow(0 0 5px rgba(0, 255, 0, 0.3));
}

.offer-badge {
  background: linear-gradient(135deg, #ff4757, #ff6b81);
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
  padding: 2px 6px;
  border-radius: 4px;
}

.price-admin-wrapper {
  display: flex;
  flex-direction: column;
}

.old-price {
  text-decoration: line-through;
  color: #9ca3af;
  font-size: 0.85em;
}

.new-price {
  color: #ff4757;
  font-weight: bold;
}

  .actions-cell {
    display: flex;
    gap: var(--spacing-sm);
  }

  .btn-icon {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
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
  
  .action-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  
  .btn-primary {
    background: #00a1ff; /* Azul brillante como en la imagen */
    color: #000;
    border: none;
    padding: 10px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    min-width: 150px;
    transition: all 0.2s ease;
  }
  
  .btn-primary:hover {
    filter: brightness(1.1);
    transform: translateX(4px);
  }
  
  .btn-primary .material-icons {
    color: #000;
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

.empty-state {
  text-align: center;
  padding: var(--spacing-3xl) var(--spacing-xl);
}

.empty-icon {
  font-size: 64px;
  color: var(--border-color);
  margin-bottom: var(--spacing-sm);
}

@media (max-width: 860px) {
  .header-actions {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
}

@media (max-width: 768px) {
  .products-table,
  .products-table thead,
  .products-table tbody,
  .products-table tr,
  .products-table td {
    display: block;
    width: 100%;
  }
  .products-table thead { display: none; }
  tr {
    margin-bottom: 20px;
    background: var(--bg-card);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 10px;
  }
  td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 10px;
    border-bottom: 1px solid rgba(255,255,255,0.04);
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
