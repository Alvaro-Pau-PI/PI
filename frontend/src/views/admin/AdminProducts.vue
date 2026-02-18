<template>
  <div class="admin-products container">
    <div class="header-actions">
      <h1>Gestión de Productos</h1>
      <button @click="openCreateModal" class="btn-primary">
        <span class="material-icons">add</span> Nuevo Producto
      </button>
    </div>

    <!-- Error/Success Messages -->
    <div v-if="error" class="alert alert-error">{{ error }}</div>
    <div v-if="success" class="alert alert-success">{{ success }}</div>

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
            <td>#{{ product.id }}</td>
            <td>
              <div class="product-name">
                <span class="name-text">{{ product.name }}</span>
                <span v-if="product.eco_score > 70" class="eco-badge">🌱</span>
              </div>
            </td>
            <td>{{ product.category }}</td>
            <td>{{ product.price }}€</td>
            <td :class="{'low-stock': product.stock < 5}">{{ product.stock }}</td>
            <td class="actions-cell">
              <button @click="editProduct(product)" class="btn-icon" title="Editar">
                <span class="material-icons">edit</span>
              </button>
              <button @click="confirmDelete(product)" class="btn-icon delete" title="Eliminar">
                <span class="material-icons">delete</span>
              </button>
            </td>
          </tr>
          <tr v-if="products.length === 0">
            <td colspan="6" class="text-center">No hay productos disponibles.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Formulario -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <h2>{{ editingProduct ? 'Editar Producto' : 'Nuevo Producto' }}</h2>
        <ProductForm 
          :product="editingProduct" 
          :loading="loading"
          @submit="handleSave"
          @cancel="closeModal"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import http from '@/services/http';
import ProductForm from './ProductForm.vue';

const products = ref([]);
const loading = ref(false);
const error = ref(null);
const success = ref(null);
const showModal = ref(false);
const editingProduct = ref(null);

const fetchProducts = async () => {
  loading.value = true;
  try {
    // Usamos el endpoint apiIndex que ya existe, asumiendo que lista todo paginado.
    // Para simplificar, pedimos la primera página o si hay un endpoint "all" mejor.
    // Usaremos apiIndex por defecto.
    const response = await http.get('/api/products?page=1'); 
    products.value = response.data.data;
  } catch (err) {
    error.value = "Error al cargar productos: " + err.message;
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  editingProduct.value = null;
  showModal.value = true;
  error.value = null;
  success.value = null;
};

const editProduct = (product) => {
  editingProduct.value = product;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingProduct.value = null;
};

const handleSave = async (formData) => {
  loading.value = true;
  error.value = null;
  
  try {
    if (editingProduct.value) {
      // Update
      const response = await http.put(`/api/products/${editingProduct.value.id}`, formData);
      success.value = "Producto actualizado correctamente.";
      // Actualizar en lista local
      const index = products.value.findIndex(p => p.id === editingProduct.value.id);
      if (index !== -1) products.value[index] = response.data;
    } else {
      // Create
      const response = await http.post('/api/products', formData);
      success.value = "Producto creado exitosamente.";
      products.value.unshift(response.data);
    }
    closeModal();
  } catch (err) {
    error.value = err.response?.data?.message || "Error al guardar producto.";
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
    success.value = "Producto eliminado.";
  } catch (err) {
    error.value = "No se pudo eliminar el producto.";
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
  padding: var(--spacing-md);
  text-align: left;
  border-bottom: 1px solid var(--border-color);
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
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: all 0.2s;
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
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: var(--bg-card);
  padding: var(--spacing-xl);
  border-radius: var(--radius-lg);
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  border: 1px solid var(--border-color);
  box-shadow: var(--shadow-xl);
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
</style>
