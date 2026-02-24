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

      <!-- Imagen Principal -->
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

      <!-- Imágenes Adicionales -->
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
        
        <!-- Galería de visualización -->
        <div class="gallery-grid" v-if="existingExtraImages.length > 0 || previewAdditionalImages.length > 0">
          <div class="image-preview" v-for="(img, idx) in existingExtraImages" :key="`exist-${idx}`">
            <img :src="getImageUrl(img)" :alt="`Existente ${idx}`" />
            <button type="button" class="btn-remove" @click="removeExistingImage(idx)" title="Borrar imagen del servidor">
              <span class="material-icons">close</span>
            </button>
          </div>
          <div class="image-preview is-new" v-for="(img, idx) in previewAdditionalImages" :key="`new-${idx}`">
            <img :src="img.url" :alt="`Nueva ${idx}`" />
            <button type="button" class="btn-remove" @click="removeNewImage(idx)" title="Borrar nueva imagen">
              <span class="material-icons">close</span>
            </button>
            <span class="badge-new">NUEVA</span>
          </div>
        </div>
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
  eco_score: null,
  is_refurbished: false,
  is_local_supplier: false,
  carbon_footprint: null
});

const mainFileInput = ref(null);
const extraFileInput = ref(null);
const dragoverMain = ref(false);
const dragoverExtra = ref(false);

const imageFile = ref(null);
const previewImage = ref(null);
const removeMainImageFlag = ref(false); // Para emitir que borramos la principal aunque no subamos otra

// Separamos existentes (strings BBDD) y nuevas (objetos File)
const existingExtraImages = ref([]);
const additionalImageFiles = ref([]);
const previewAdditionalImages = ref([]); // Arrays { file, url } para render y borrado

// Lógica de imágenes del proyecto
const getImageUrl = (imagePath) => {
  if (!imagePath) return '/img/placeholder.png';
  if (imagePath.startsWith('http') || imagePath.startsWith('data:') || imagePath.startsWith('blob:')) return imagePath;
  if (imagePath.startsWith('/storage')) {
    return `http://localhost:8000${imagePath}`; // O usa VITE_API_URL
  }
  return imagePath.startsWith('/') ? imagePath : `/${imagePath}`;
};

// Cargar datos si es edición
watch(() => props.product, (newVal) => {
  if (newVal) {
    Object.assign(form, newVal);
    isEdit.value = true;
    
    if (newVal.image) {
      previewImage.value = getImageUrl(newVal.image);
    } else {
      previewImage.value = null;
    }
    removeMainImageFlag.value = false;

    if (newVal.images && Array.isArray(newVal.images)) {
      existingExtraImages.value = [...newVal.images];
    } else {
      existingExtraImages.value = [];
    }
    
    // Reseteamos las temporales al editar otro
    additionalImageFiles.value = [];
    previewAdditionalImages.value.forEach(p => URL.revokeObjectURL(p.url));
    previewAdditionalImages.value = [];
    
  }
}, { immediate: true });

// Eventos de botones
const triggerMainInput = () => { mainFileInput.value.click(); };
const triggerExtraInput = () => { extraFileInput.value.click(); };

const processMainFile = (file) => {
  if (file) {
    imageFile.value = file;
    previewImage.value = URL.createObjectURL(file);
    removeMainImageFlag.value = false;
  }
};

const handleImageChange = (event) => {
  processMainFile(event.target.files[0]);
};

const handleMainDrop = (event) => {
  dragoverMain.value = false;
  processMainFile(event.dataTransfer.files[0]);
};

const removeMainImage = () => {
  imageFile.value = null;
  if (previewImage.value && previewImage.value.startsWith('blob:')) {
      URL.revokeObjectURL(previewImage.value);
  }
  previewImage.value = null;
  removeMainImageFlag.value = true;
  if(mainFileInput.value) mainFileInput.value.value = '';
};

// --- Múltiples Adicionales ---
const processExtraFiles = (filesArray) => {
  if (filesArray.length > 0) {
    filesArray.forEach(file => {
       additionalImageFiles.value.push(file);
       previewAdditionalImages.value.push({
           file: file,
           url: URL.createObjectURL(file)
       });
    });
  }
};

const handleAdditionalImagesChange = (event) => {
  processExtraFiles(Array.from(event.target.files));
  if(extraFileInput.value) extraFileInput.value.value = ''; // Reset
};

const handleExtraDrop = (event) => {
  dragoverExtra.value = false;
  processExtraFiles(Array.from(event.dataTransfer.files));
};

const removeExistingImage = (idx) => {
  existingExtraImages.value.splice(idx, 1);
};

const removeNewImage = (idx) => {
  const fileToRemove = previewAdditionalImages.value[idx];
  URL.revokeObjectURL(fileToRemove.url);
  additionalImageFiles.value.splice(idx, 1);
  previewAdditionalImages.value.splice(idx, 1);
};

// Cleanup on unmount
import { onUnmounted } from 'vue';
onUnmounted(() => {
  if (previewImage.value && previewImage.value.startsWith('blob:')) URL.revokeObjectURL(previewImage.value);
  previewAdditionalImages.value.forEach(p => URL.revokeObjectURL(p.url));
});

const handleSubmit = () => {
  emit('submit', { 
    ...form, 
    imageFile: imageFile.value,
    removeMainImage: removeMainImageFlag.value,
    additionalImages: additionalImageFiles.value,
    keepImages: existingExtraImages.value
  });
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

/* DRAG & DROP UI */
.drag-drop-zone {
  border: 2px dashed rgba(255, 255, 255, 0.2);
  border-radius: var(--radius-lg);
  padding: var(--spacing-md);
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  background: rgba(0, 0, 0, 0.1);
  position: relative;
  min-height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.drag-drop-zone:hover, .drag-drop-zone.drag-over {
  border-color: var(--color-primary);
  background: rgba(0, 161, 255, 0.05);
}

.drag-drop-zone.has-file {
  border-style: solid;
  border-color: rgba(255, 255, 255, 0.1);
  background: transparent;
}

.hidden-input {
  display: none;
}

.upload-prompt {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: var(--text-secondary);
  gap: 8px;
  pointer-events: none;
}

.prompt-icon {
  font-size: 38px;
  color: var(--color-primary);
  opacity: 0.8;
}

.gallery-grid {
  display: flex;
  flex-wrap: wrap;
  gap: var(--spacing-sm);
  margin-top: var(--spacing-md);
}

.image-preview {
  position: relative;
  width: 90px;
  height: 90px;
  border-radius: var(--radius-md);
  overflow: hidden;
  border: 2px solid rgba(255, 255, 255, 0.1);
  background: rgba(0, 0, 0, 0.3);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.image-preview.is-new {
  border-color: var(--color-primary);
}

.image-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.image-preview:hover img {
  transform: scale(1.05);
}

.btn-remove {
  position: absolute;
  top: 4px;
  right: 4px;
  background: rgba(255, 71, 87, 0.9);
  color: white;
  border: none;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  opacity: 0;
  transform: scale(0.8);
  transition: all 0.2s;
  box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.btn-remove .material-icons {
  font-size: 14px;
}

.image-preview:hover .btn-remove {
  opacity: 1;
  transform: scale(1);
}

.badge-new {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: var(--color-primary);
  color: #000;
  font-size: 0.6em;
  font-weight: bold;
  text-align: center;
  padding: 2px 0;
}

.help-text {
  font-size: 0.8em;
  color: var(--text-secondary);
  opacity: 0.7;
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
