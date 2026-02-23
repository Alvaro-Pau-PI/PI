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
          <label class="switch-label">
            <span class="switch">
              <input type="checkbox" v-model="form.is_refurbished" />
              <span class="slider"></span>
            </span>
            <span>Producto Reacondicionado</span>
          </label>
          <label class="switch-label">
            <span class="switch">
              <input type="checkbox" v-model="form.is_local_supplier" />
              <span class="slider"></span>
            </span>
            <span>Proveedor Local</span>
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
  background: transparent;
  padding: var(--spacing-sm);
  border-radius: var(--radius-lg);
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
  padding: 12px 16px;
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: var(--radius-md);
  color: var(--text-primary);
  font-family: inherit;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 4px rgba(0, 161, 255, 0.15);
  background: rgba(0, 0, 0, 0.4);
}

.sustainability-section {
  margin-top: var(--spacing-lg);
  padding-top: var(--spacing-md);
  border-top: 1px solid var(--border-color);
}

.checkbox-group {
  margin-top: var(--spacing-lg);
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
}

.switch-label {
  display: flex;
  align-items: center;
  gap: var(--spacing-md);
  cursor: pointer;
  color: var(--text-primary);
  font-weight: 500;
}

/* Toggle Switch puro CSS */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.1);
  transition: .3s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .3s;
  border-radius: 50%;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

input:checked + .slider {
  background-color: var(--color-primary);
}

input:focus-visible + .slider {
  box-shadow: 0 0 0 3px rgba(0, 161, 255, 0.3);
}

input:checked + .slider:before {
  transform: translateX(20px);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: var(--spacing-md);
  margin-top: var(--spacing-xl);
}

.btn-submit {
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: var(--radius-md);
  font-weight: 600;
  font-family: var(--font-headings);
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3);
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 161, 255, 0.5);
}

.btn-cancel {
  background: transparent;
  color: var(--text-secondary);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 12px 24px;
  border-radius: var(--radius-md);
  font-family: var(--font-headings);
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-cancel:hover {
  background: rgba(255, 255, 255, 0.05);
  color: var(--text-primary);
}

.btn-submit:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
</style>
