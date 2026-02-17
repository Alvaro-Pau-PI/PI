<template>
  <div class="product-form-container">
    <form @submit.prevent="handleSubmit" class="product-form">
      <!-- Nombre -->
      <div class="form-group">
        <label for="name">Nombre del Producto</label>
        <input 
          id="name" 
          v-model="form.name" 
          type="text" 
          required 
          class="form-input"
          placeholder="Ej: NVIDIA RTX 4090"
        />
      </div>

      <!-- Categoría -->
      <div class="form-group">
        <label for="category">Categoría</label>
        <select id="category" v-model="form.category" required class="form-input">
          <option value="" disabled>Selecciona una categoría</option>
          <option value="Targetes Gràfiques">Targetes Gràfiques</option>
          <option value="Processadors">Processadors</option>
          <option value="Plaques Base">Plaques Base</option>
          <option value="Memòria RAM">Memòria RAM</option>
          <option value="Emmagatzematge">Emmagatzematge</option>
          <option value="Fonts d'Alimentació">Fonts d'Alimentació</option>
          <option value="Caixes">Caixes</option>
          <option value="Refrigeració">Refrigeració</option>
          <option value="Perifèrics">Perifèrics</option>
        </select>
      </div>

      <!-- Precio y Stock -->
      <div class="form-row">
        <div class="form-group">
          <label for="price">Precio (€)</label>
          <input 
            id="price" 
            v-model.number="form.price" 
            type="number" 
            step="0.01" 
            min="0" 
            required 
            class="form-input"
          />
        </div>
        <div class="form-group">
          <label for="stock">Stock</label>
          <input 
            id="stock" 
            v-model.number="form.stock" 
            type="number" 
            min="0" 
            required 
            class="form-input"
          />
        </div>
      </div>

      <!-- Descripción -->
      <div class="form-group">
        <label for="description">Descripción</label>
        <textarea 
          id="description" 
          v-model="form.description" 
          rows="4" 
          required 
          class="form-input"
        ></textarea>
      </div>

      <!-- Sección Sostenibilidad -->
      <div class="sustainability-section">
        <h3>Sostenibilidad</h3>
        <div class="form-row">
          <div class="form-group">
            <label for="eco_score">Eco Score (0-100)</label>
            <input 
              id="eco_score" 
              v-model.number="form.eco_score" 
              type="number" 
              min="0" 
              max="100" 
              class="form-input"
            />
          </div>
          <div class="form-group">
            <label for="carbon_footprint">Huella de Carbono (kg CO2)</label>
            <input 
              id="carbon_footprint" 
              v-model.number="form.carbon_footprint" 
              type="number" 
              step="0.01" 
              class="form-input"
            />
          </div>
        </div>
        <div class="checkbox-group">
          <label class="checkbox-label">
            <input type="checkbox" v-model="form.is_refurbished" />
            Producto Reacondicionado
          </label>
          <label class="checkbox-label">
            <input type="checkbox" v-model="form.is_local_supplier" />
            Proveedor Local
          </label>
        </div>
      </div>

      <!-- Botones -->
      <div class="form-actions">
        <button type="button" @click="$emit('cancel')" class="btn-cancel">Cancelar</button>
        <button type="submit" class="btn-submit" :disabled="loading">
          {{ isEdit ? 'Actualizar Producto' : 'Crear Producto' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';

const props = defineProps({
  product: {
    type: Object,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['submit', 'cancel']);

const isEdit = ref(!!props.product);

const form = reactive({
  name: '',
  description: '',
  price: 0,
  stock: 0,
  category: '',
  image: '/img/placeholder.png', // Default placeholder
  eco_score: null,
  is_refurbished: false,
  is_local_supplier: false,
  carbon_footprint: null
});

// Cargar datos si es edición
watch(() => props.product, (newVal) => {
  if (newVal) {
    Object.assign(form, newVal);
    isEdit.value = true;
  }
}, { immediate: true });

const handleSubmit = () => {
  emit('submit', { ...form });
};
</script>

<style scoped>
.product-form-container {
  background: var(--bg-card);
  padding: var(--spacing-lg);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border-color);
}

.form-group {
  margin-bottom: var(--spacing-md);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-md);
}

label {
  display: block;
  margin-bottom: var(--spacing-xs);
  color: var(--text-secondary);
  font-weight: 500;
}

.form-input {
  width: 100%;
  padding: var(--spacing-sm);
  background: var(--bg-input);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  color: var(--text-primary);
  font-family: inherit;
}

.form-input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 2px rgba(0, 243, 255, 0.1);
}

.sustainability-section {
  margin-top: var(--spacing-lg);
  padding-top: var(--spacing-md);
  border-top: 1px solid var(--border-color);
}

.checkbox-group {
  margin-top: var(--spacing-md);
  display: flex;
  gap: var(--spacing-lg);
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: var(--spacing-xs);
  cursor: pointer;
  color: var(--text-primary);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: var(--spacing-md);
  margin-top: var(--spacing-xl);
}

.btn-submit {
  background: var(--color-primary);
  color: #000;
  border: none;
  padding: var(--spacing-sm) var(--spacing-xl);
  border-radius: var(--radius-md);
  font-weight: bold;
  cursor: pointer;
  transition: opacity 0.2s;
}

.btn-cancel {
  background: transparent;
  color: var(--text-secondary);
  border: 1px solid var(--border-color);
  padding: var(--spacing-sm) var(--spacing-xl);
  border-radius: var(--radius-md);
  cursor: pointer;
}

.btn-submit:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
</style>
