<template>
  <div class="product-form-container">
    <form @submit.prevent="handleSubmit" class="product-form">
      <!-- Nombre del Producto -->
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

      <!-- Imagen Principal con Drag & Drop -->
      <div class="form-group">
        <label>Imagen Principal del Producto</label>
        <div 
          class="drag-drop-zone" 
          @dragover.prevent="dragoverMain = true" 
          @dragleave.prevent="dragoverMain = false" 
          @drop.prevent="handleMainDrop"
          :class="{ 'drag-over': dragoverMain, 'has-file': previewImage }"
          @click="triggerMainInput"
        >
          <input 
            type="file" 
            ref="mainFileInput"
            accept="image/*" 
            @change="handleImageChange" 
            class="hidden-input"
          />
          <!-- Previsualización de la imagen principal -->
          <div class="preview-container single" v-if="previewImage" @click.stop>
            <div class="image-preview">
              <img :src="previewImage" alt="Previsualización de Imagen Principal" />
              <button type="button" class="btn-remove" @click="removeMainImage">
                <span class="material-icons">close</span>
              </button>
            </div>
          </div>
          <div class="upload-prompt" v-else>
            <span class="material-icons prompt-icon">cloud_upload</span>
            <p>Arrastra tu imagen aquí o <strong>haz clic en explorar</strong></p>
            <span class="help-text">JPG, PNG o WEBP (Max. 2MB)</span>
          </div>
        </div>
      </div>

      <!-- Imágenes Adicionales para la Galería -->
      <div class="form-group">
        <label>Imágenes Secundarias Adicionales (Opcional)</label>
        <div 
          class="drag-drop-zone" 
          @dragover.prevent="dragoverExtra = true" 
          @dragleave.prevent="dragoverExtra = false" 
          @drop.prevent="handleExtraDrop"
          :class="{ 'drag-over': dragoverExtra }"
          @click="triggerExtraInput"
        >
          <input 
            type="file" 
            ref="extraFileInput"
            accept="image/*" 
            multiple
            @change="handleAdditionalImagesChange" 
            class="hidden-input"
          />
          <div class="upload-prompt">
            <span class="material-icons prompt-icon">add_photo_alternate</span>
            <p>Añade más fotos a la galería arrastrando o haciendo clic</p>
          </div>
        </div>
        
        <!-- Cuadrícula de previsualización de la galería -->
        <div class="gallery-grid" v-if="existingExtraImages.length > 0 || previewAdditionalImages.length > 0">
          <!-- Imágenes que ya existen en el servidor -->
          <div class="image-preview" v-for="(img, idx) in existingExtraImages" :key="`exist-${idx}`">
            <img :src="getImageUrl(img)" :alt="`Existente ${idx}`" />
            <button type="button" class="btn-remove" @click="removeExistingImage(idx)" title="Borrar imagen del servidor">
              <span class="material-icons">close</span>
            </button>
          </div>
          <!-- Nuevas imágenes cargadas por el usuario -->
          <div class="image-preview is-new" v-for="(img, idx) in previewAdditionalImages" :key="`new-${idx}`">
            <img :src="img.url" :alt="`Nueva ${idx}`" />
            <button type="button" class="btn-remove" @click="removeNewImage(idx)" title="Borrar nueva imagen">
              <span class="material-icons">close</span>
            </button>
            <span class="badge-new">NUEVA</span>
          </div>
        </div>
      </div>

      <!-- Categoría del Producto -->
      <div class="form-group">
        <label for="category">Categoría</label>
        <select id="category" v-model="form.category" required class="form-input category-select">
          <option value="" disabled>Selecciona una categoría</option>
          <option value="Tarjetas Gráficas">Tarjetas Gráficas</option>
          <option value="Procesadores">Procesadores</option>
          <option value="Memoria RAM">Memoria RAM</option>
          <option value="Placas Base">Placas Base</option>
          <option value="Almacenamiento">Almacenamiento (SSD/HDD)</option>
          <option value="Fuentes de Alimentación">Fuentes de Alimentación</option>
          <option value="Cajas / Chasis">Cajas / Chasis</option>
          <option value="Refrigeración">Refrigeración</option>
          <option value="Periféricos">Periféricos</option>
          <option value="Monitores">Monitores</option>
        </select>
      </div>

      <!-- Grid para Precio y Stock -->
      <div class="form-grid">
        <div class="form-group">
          <label for="price">Precio Original (€)</label>
          <input id="price" v-model.number="form.price" type="number" step="0.01" required class="form-input" @input="syncDiscount('price')" />
        </div>
        <div class="form-group">
          <label for="stock">Stock</label>
          <input id="stock" v-model.number="form.stock" type="number" required class="form-input" />
        </div>
      </div>

      <!-- Descripción del Producto -->
      <div class="form-group">
        <label for="description">Descripción</label>
        <textarea id="description" v-model="form.description" required class="form-input" rows="4"></textarea>
      </div>

      <!-- Secciones Expandibles: Oferta y Sostenibilidad -->
      <div class="expandable-sections">
        <!-- Sección de Oferta -->
        <div class="section-card offer-card" :class="{ 'is-active': form.is_offer_active }">
          <div class="section-header" @click="form.is_offer_active = !form.is_offer_active">
            <span class="material-icons">{{ form.is_offer_active ? 'local_fire_department' : 'sell' }}</span>
            <h3>Configuración de Oferta</h3>
            <div class="toggle-switch">
              <input type="checkbox" v-model="form.is_offer_active" />
              <span class="slider"></span>
            </div>
          </div>
          <div class="section-body" v-if="form.is_offer_active">
            <div class="offer-grid">
               <div class="form-group">
                  <label>Precio de Oferta (€)</label>
                  <input v-model.number="form.discount_price" type="number" step="0.01" class="form-input" @input="syncDiscount('discount_price')" />
               </div>
               <div class="form-group">
                  <label>Porcentaje Descuento (%)</label>
                  <input v-model.number="form.discount_percentage" type="number" min="0" max="99" class="form-input" @input="syncDiscount('percentage')" />
               </div>
               <div class="form-group">
                  <label>Fecha Inicio</label>
                  <input v-model="form.offer_start_date" type="datetime-local" class="form-input" />
               </div>
               <div class="form-group">
                  <label>Fecha Fin</label>
                  <input v-model="form.offer_end_date" type="datetime-local" class="form-input" />
               </div>
            </div>
          </div>
        </div>

        <!-- Sección de Sostenibilidad y Extras -->
        <div class="section-card eco-card">
          <div class="section-header">
            <span class="material-icons">eco</span>
            <h3>Sostenibilidad y Atributos</h3>
          </div>
          <div class="section-body">
            <div class="eco-grid">
               <div class="eco-range">
                  <label>Puntuación Ecológica ({{ form.eco_score }}/100)</label>
                  <input type="range" v-model.number="form.eco_score" min="0" max="100" class="range-input" />
               </div>
               <div class="checkbox-group">
                  <label class="checkbox-label">
                     <input type="checkbox" v-model="form.is_local_supplier" />
                     <span class="label-text">Proveedor Local</span>
                  </label>
                  <label class="checkbox-label">
                     <input type="checkbox" v-model="form.is_refurbished" />
                     <span class="label-text">Producto Reacondicionado</span>
                  </label>
               </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Botones de Acción -->
      <div class="form-actions">
        <button type="button" @click="$emit('cancel')" class="btn-secondary">Cancelar</button>
        <button type="submit" class="btn-primary" :disabled="loading">
          <span v-if="loading" class="spinner"></span>
          {{ product ? 'Actualizar Producto' : 'Crear Producto' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { getImageUrl } from '@/utils/images';

/**
 * Formulario de producto avanzado.
 * Gestiona ofertas con cálculo automático, sostenibilidad e imágenes múltiples.
 */

const props = defineProps({
  product: { type: Object, default: null },
  loading: { type: Boolean, default: false }
});

const emit = defineEmits(['submit', 'cancel']);

// Estado inicial del formulario con todos los campos necesarios
const form = reactive({
  name: '',
  description: '',
  price: 0,
  category: '',
  stock: 0,
  eco_score: 50,
  is_offer_active: false,
  discount_price: 0,
  discount_percentage: 0,
  offer_start_date: '',
  offer_end_date: '',
  is_refurbished: false,
  is_local_supplier: false
});

// Referencias para archivos y previsualizaciones
const mainFileInput = ref(null);
const extraFileInput = ref(null);
const imageFile = ref(null);
const previewImage = ref(null);
const previewAdditionalImages = ref([]);
const existingExtraImages = ref([]);
const keepImages = ref([]);
const shouldRemoveMainImage = ref(false);

const dragoverMain = ref(false);
const dragoverExtra = ref(false);

// Sincronización inteligente de descuentos
const syncDiscount = (source) => {
  if (!form.price || form.price <= 0) return;

  if (source === 'percentage') {
    // Si cambio el %, calculo el precio
    const calculated = form.price * (1 - form.discount_percentage / 100);
    form.discount_price = parseFloat(calculated.toFixed(2));
  } else if (source === 'discount_price' || source === 'price') {
    // Si cambio el precio o el original, calculo el %
    if (form.discount_price > 0 && form.discount_price < form.price) {
      const calculated = ((form.price - form.discount_price) / form.price) * 100;
      form.discount_percentage = Math.round(calculated);
    } else if (source === 'discount_price' && form.discount_price >= form.price) {
        form.discount_percentage = 0;
    }
  }
};

// Cargar datos al editar
watch(() => props.product, (newVal) => {
  if (newVal) {
    Object.assign(form, {
      name: newVal.name,
      description: newVal.description || '',
      price: newVal.price,
      category: newVal.category || '',
      stock: newVal.stock,
      eco_score: newVal.eco_score || 50,
      is_offer_active: !!newVal.is_offer_active,
      discount_price: newVal.discount_price || 0,
      discount_percentage: newVal.discount_percentage || 0,
      offer_start_date: formatDateTimeForInput(newVal.offer_start_date),
      offer_end_date: formatDateTimeForInput(newVal.offer_end_date),
      is_refurbished: !!newVal.is_refurbished,
      is_local_supplier: !!newVal.is_local_supplier
    });
    previewImage.value = newVal.image ? getImageUrl(newVal.image) : null;
    existingExtraImages.value = newVal.images ? (Array.isArray(newVal.images) ? newVal.images : JSON.parse(newVal.images)) : [];
    keepImages.value = [...existingExtraImages.value];
    shouldRemoveMainImage.value = false;
  } else {
    resetForm();
  }
}, { immediate: true });

function resetForm() {
  Object.assign(form, {
    name: '',
    description: '',
    price: 0,
    category: '',
    stock: 0,
    eco_score: 50,
    is_offer_active: false,
    discount_price: 0,
    discount_percentage: 0,
    offer_start_date: '',
    offer_end_date: '',
    is_refurbished: false,
    is_local_supplier: false
  });
  imageFile.value = null;
  previewImage.value = null;
  previewAdditionalImages.value = [];
  existingExtraImages.value = [];
  keepImages.value = [];
  shouldRemoveMainImage.value = false;
}

// Utilidad para formatear fechas de BBDD al input datetime-local
function formatDateTimeForInput(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return '';
    return d.toISOString().slice(0, 16);
}

// Gestión Imagen Principal
const triggerMainInput = () => mainFileInput.value.click();
const handleImageChange = (e) => {
  const file = e.target.files[0];
  if (file) processMainFile(file);
};
const handleMainDrop = (e) => {
  dragoverMain.value = false;
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) processMainFile(file);
};
const processMainFile = (file) => {
  imageFile.value = file;
  previewImage.value = URL.createObjectURL(file);
  shouldRemoveMainImage.value = false;
};
const removeMainImage = () => {
  imageFile.value = null;
  previewImage.value = null;
  shouldRemoveMainImage.value = true;
};

// Gestión Galería
const triggerExtraInput = () => extraFileInput.value.click();
const handleAdditionalImagesChange = (e) => {
  const files = Array.from(e.target.files);
  processExtraFiles(files);
};
const handleExtraDrop = (e) => {
  dragoverExtra.value = false;
  const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
  processExtraFiles(files);
};
const processExtraFiles = (files) => {
  files.forEach(file => {
    previewAdditionalImages.value.push({ file, url: URL.createObjectURL(file) });
  });
};
const removeNewImage = (idx) => previewAdditionalImages.value.splice(idx, 1);
const removeExistingImage = (idx) => {
  const removed = existingExtraImages.value.splice(idx, 1)[0];
  keepImages.value = keepImages.value.filter(img => img !== removed);
};

// Envío
const handleSubmit = () => {
  const data = {
    ...form,
    imageFile: imageFile.value,
    additionalImages: previewAdditionalImages.value.map(p => p.file),
    keepImages: keepImages.value,
    removeMainImage: shouldRemoveMainImage.value
  };
  emit('submit', data);
};

// La función getImageUrl ahora se importa de @/utils/images
</script>

<style scoped>
.product-form-container { color: #EAEAEA; }
.form-group { margin-bottom: var(--spacing-md); }
label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-secondary); }

.form-input {
  width: 100%;
  padding: 12px;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: var(--radius-md);
  color: white;
  outline: none;
}
.form-input:focus { border-color: var(--color-primary); }

/* Arreglo para que el select se vea bien y sea legible */
.category-select {
    cursor: pointer;
    appearance: auto; /* Usar flecha nativa para mejor compatibilidad */
}
.category-select option {
    background-color: #1a1e26;
    color: white;
}

.form-grid, .offer-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-md);
}

.drag-drop-zone {
  border: 2px dashed rgba(255,255,255,0.1);
  border-radius: var(--radius-lg);
  padding: 20px;
  text-align: center;
  background: rgba(255,255,255,0.02);
  cursor: pointer;
}
.drag-drop-zone.drag-over { border-color: var(--color-primary); }
.hidden-input { display: none; }

.image-preview {
  position: relative;
  width: 100px;
  height: 100px;
  border-radius: 8px;
  overflow: hidden;
  margin: 0 auto;
}
.image-preview img { width: 100%; height: 100%; object-fit: cover; }

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
  gap: 10px;
  margin-top: 10px;
}

.btn-remove {
  position: absolute;
  top: 2px; right: 2px;
  background: #ff4757;
  color: white;
  border: none;
  border-radius: 50%;
  width: 20px; height: 20px;
  cursor: pointer;
}

.section-card {
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 12px;
  margin-bottom: 15px;
}
.section-header {
  padding: 12px 16px;
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
}
.section-body { padding: 15px; border-top: 1px solid rgba(255,255,255,0.05); }

/* Sostenibilidad Grid */
.eco-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.checkbox-group {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    color: var(--text-primary);
}
.checkbox-label input {
    width: 18px;
    height: 18px;
    accent-color: var(--color-primary);
}

.range-input { width: 100%; accent-color: #2ed573; }

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 20px;
}

.btn-primary {
  background: var(--color-primary);
  color: black;
  padding: 10px 25px;
  border-radius: 8px;
  font-weight: bold;
  border: none;
  cursor: pointer;
}
.btn-secondary {
  background: rgba(255,255,255,0.05);
  color: white;
  padding: 10px 25px;
  border-radius: 8px;
  border: 1px solid rgba(255,255,255,0.1);
  cursor: pointer;
}

@media (max-width: 600px) {
  .form-grid, .offer-grid { grid-template-columns: 1fr; }
}
</style>
