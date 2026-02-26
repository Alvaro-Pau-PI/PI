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

      <!-- Imagen Principal con Drag & Drop Profesional -->
      <div class="form-group">
        <label class="form-label">
          <span class="material-icons label-icon">image</span>
          Imagen Principal del Producto
          <span class="required">*</span>
        </label>
        <div 
          class="upload-zone professional" 
          @dragover.prevent="dragoverMain = true" 
          @dragleave.prevent="dragoverMain = false" 
          @drop.prevent="handleMainDrop"
          :class="{ 
            'drag-over': dragoverMain, 
            'has-file': previewImage,
            'loading': uploadingMain
          }"
          @click="triggerMainInput"
        >
          <input 
            type="file" 
            ref="mainFileInput"
            accept="image/*" 
            @change="handleImageChange" 
            class="hidden-input"
          />
          
          <!-- Previsualización profesional para edición -->
          <div class="preview-container professional edit-mode" v-if="previewImage">
            <div class="image-wrapper">
              <img :src="previewImage" alt="Previsualización de Imagen Principal" />
              <div class="image-overlay edit-overlay">
                <button type="button" class="btn-icon edit-btn" @click.stop="triggerMainInput" title="Cambiar imagen">
                  <span class="material-icons">edit</span>
                </button>
                <button type="button" class="btn-icon delete-btn" @click="removeMainImage" title="Eliminar imagen">
                  <span class="material-icons">delete</span>
                </button>
              </div>
            </div>
          </div>
          
          <!-- Estado de carga -->
          <div class="upload-loading" v-else-if="uploadingMain">
            <div class="loading-spinner"></div>
            <p>Subiendo imagen...</p>
            <div class="progress-bar">
              <div class="progress-fill" :style="{ width: uploadProgress + '%' }"></div>
            </div>
          </div>
          
          <!-- Zona de subida (solo si no hay imagen) -->
          <div class="upload-prompt professional centered" v-if="!previewImage">
            <div class="upload-icon">
              <span class="material-icons">cloud_upload</span>
            </div>
            <div class="upload-text">
              <h4>Arrastra tu imagen aquí</h4>
              <p>o <strong>haz clic para explorar</strong></p>
              <span class="upload-hint">JPG, PNG o WEBP • Máx. 2MB • Mín. 500x500px</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Galería de Imágenes Profesional -->
      <div class="form-group">
        <label class="form-label">
          <span class="material-icons label-icon">collections</span>
          Galería de Imágenes Adicionales
          <span class="optional">(Opcional)</span>
        </label>
        <div 
          class="upload-zone gallery-zone" 
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
          <div class="upload-prompt gallery centered">
            <div class="upload-icon">
              <span class="material-icons">add_photo_alternate</span>
            </div>
            <div class="upload-text">
              <h4>Añade imágenes a la galería</h4>
              <p>Arrastra múltiples imágenes o <strong>haz clic para seleccionar</strong></p>
              <span class="upload-hint">Máx. 5 imágenes adicionales • JPG, PNG, WEBP</span>
            </div>
          </div>
        </div>
        
        <!-- Galería de previsualización profesional -->
        <div class="gallery-preview" v-if="hasGalleryImages">
          <div class="gallery-header">
            <h4>
              <span class="material-icons">collections</span>
              Imágenes de la Galería ({{ totalGalleryImages }})
            </h4>
            <div class="gallery-actions">
              <button type="button" class="btn-clear-all" @click="clearGallery" v-if="hasGalleryImages">
                <span class="material-icons">clear_all</span>
                Limpiar todo
              </button>
            </div>
          </div>
          
          <div class="gallery-grid professional">
            <!-- Imágenes existentes -->
            <div class="gallery-item existing" v-for="(img, idx) in existingExtraImages" :key="`exist-${idx}`">
              <div class="gallery-image-wrapper">
                <img :src="getImageUrl(img)" :alt="`Imagen ${idx + 1}`" />
                <div class="gallery-image-overlay">
                  <button type="button" class="btn-view" @click="viewImage(img)" title="Ver imagen completa">
                    <span class="material-icons">visibility</span>
                  </button>
                  <button type="button" class="btn-remove" @click="removeExistingImage(idx)" title="Eliminar imagen">
                    <span class="material-icons">delete</span>
                  </button>
                </div>
              </div>
              <div class="gallery-item-info">
                <span class="item-status">Guardada</span>
                <span class="item-index">#{{ idx + 1 }}</span>
              </div>
            </div>
            
            <!-- Nuevas imágenes -->
            <div class="gallery-item new" v-for="(img, idx) in previewAdditionalImages" :key="`new-${idx}`">
              <div class="gallery-image-wrapper">
                <img :src="img.url" :alt="`Nueva imagen ${idx + 1}`" />
                <div class="gallery-image-overlay">
                  <button type="button" class="btn-view" @click="viewImage(img.url)" title="Ver imagen completa">
                    <span class="material-icons">visibility</span>
                  </button>
                  <button type="button" class="btn-remove" @click="removeNewImage(idx)" title="Eliminar imagen">
                    <span class="material-icons">delete</span>
                  </button>
                </div>
                <div class="new-badge">NUEVA</div>
              </div>
              <div class="gallery-item-info">
                <span class="item-status">Pendiente</span>
                <span class="item-index">#{{ existingExtraImages.length + idx + 1 }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Categoría del Producto -->
      <div class="form-group">
        <label for="category">Categoría</label>
        <select id="category" v-model="form.category" required class="form-input category-select">
          <option value="" disabled>Selecciona una categoria</option>
          <option value="Processadors">Processadors</option>
          <option value="Targetes Gràfiques">Targetes Gràfiques</option>
          <option value="Plaques Base">Plaques Base</option>
          <option value="Memòria RAM">Memòria RAM</option>
          <option value="Emmagatzematge">Emmagatzematge</option>
          <option value="Fonts Alimentació">Fonts Alimentació</option>
          <option value="Caixes">Caixes</option>
          <option value="Refrigeració">Refrigeració</option>
          <option value="Perifèrics">Perifèrics</option>
          <option value="Monitors">Monitors</option>
          <option value="Altres">Altres</option>
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
import { ref, reactive, watch, computed, nextTick } from 'vue';
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

// Estados para la UI profesional
const dragoverMain = ref(false);
const dragoverExtra = ref(false);
const uploadingMain = ref(false);
const uploadProgress = ref(0);
const mainImageName = ref('');
const mainImageSize = ref('');

// Computed properties para la galería
const hasGalleryImages = computed(() => 
  existingExtraImages.value.length > 0 || previewAdditionalImages.value.length > 0
);

const totalGalleryImages = computed(() => 
  existingExtraImages.value.length + previewAdditionalImages.value.length
);

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
    // Resetear formulario primero
    resetForm();
    
    // Asignar valores con reactividad forzada
    form.name = newVal.name || '';
    form.description = newVal.description || '';
    form.price = parseFloat(newVal.price) || 0;
    form.category = newVal.category || '';
    form.stock = parseInt(newVal.stock) || 0;
    form.eco_score = parseInt(newVal.eco_score) || 50;
    form.is_offer_active = !!newVal.is_offer_active;
    form.discount_price = parseFloat(newVal.discount_price) || 0;
    form.discount_percentage = parseFloat(newVal.discount_percentage) || 0;
    form.offer_start_date = formatDateTimeForInput(newVal.offer_start_date);
    form.offer_end_date = formatDateTimeForInput(newVal.offer_end_date);
    form.is_refurbished = !!newVal.is_refurbished;
    form.is_local_supplier = !!newVal.is_local_supplier;
    
    // Forzar actualización de la categoría con nextTick y timeout
    nextTick(() => {
      setTimeout(() => {
        form.category = newVal.category || '';
      }, 100);
    });
    
    previewImage.value = newVal.image ? getImageUrl(newVal.image) : null;
    existingExtraImages.value = newVal.images ? (Array.isArray(newVal.images) ? newVal.images : JSON.parse(newVal.images)) : [];
    keepImages.value = [...existingExtraImages.value];
    shouldRemoveMainImage.value = false;
    
    // Actualizar información de la imagen principal si existe
    if (newVal.image) {
      mainImageName.value = 'Imagen actual';
      mainImageSize.value = 'Cargada';
    }
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
  
  // Resetear estados profesionales
  uploadingMain.value = false;
  uploadProgress.value = 0;
  mainImageName.value = '';
  mainImageSize.value = '';
}

// Utilidad para formatear fechas de BBDD al input datetime-local
function formatDateTimeForInput(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return '';
    return d.toISOString().slice(0, 16);
}

// Gestión Imagen Principal Profesional
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
  // Validación profesional
  if (!file.type.startsWith('image/')) {
    alert('Por favor, selecciona un archivo de imagen válido (JPG, PNG, WEBP)');
    return;
  }
  
  if (file.size > 2 * 1024 * 1024) { // 2MB
    alert('La imagen no puede superar los 2MB');
    return;
  }
  
  // Simular carga con progreso
  uploadingMain.value = true;
  uploadProgress.value = 0;
  
  const progressInterval = setInterval(() => {
    uploadProgress.value += Math.random() * 30;
    if (uploadProgress.value >= 90) {
      clearInterval(progressInterval);
      uploadProgress.value = 90;
      
      // Completar carga
      setTimeout(() => {
        uploadProgress.value = 100;
        imageFile.value = file;
        previewImage.value = URL.createObjectURL(file);
        mainImageName.value = file.name;
        mainImageSize.value = formatFileSize(file.size);
        shouldRemoveMainImage.value = false;
        uploadingMain.value = false;
        
        setTimeout(() => {
          uploadProgress.value = 0;
        }, 500);
      }, 200);
    }
  }, 100);
};

const removeMainImage = () => {
  imageFile.value = null;
  previewImage.value = null;
  shouldRemoveMainImage.value = true;
  mainImageName.value = '';
  mainImageSize.value = '';
};

// Utilidades profesionales
const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Gestión Galería Profesional
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
  // Validar límite de imágenes
  const totalImages = existingExtraImages.value.length + previewAdditionalImages.value.length + files.length;
  if (totalImages > 5) {
    alert('Máximo 5 imágenes adicionales permitidas');
    return;
  }
  
  files.forEach(file => {
    if (file.size > 2 * 1024 * 1024) {
      alert(`El archivo ${file.name} supera los 2MB`);
      return;
    }
    previewAdditionalImages.value.push({ file, url: URL.createObjectURL(file) });
  });
};

const removeNewImage = (idx) => {
  const removed = previewAdditionalImages.value.splice(idx, 1)[0];
  URL.revokeObjectURL(removed.url); // Liberar memoria
};

const removeExistingImage = (idx) => {
  const removed = existingExtraImages.value.splice(idx, 1)[0];
  keepImages.value = keepImages.value.filter(img => img !== removed);
};

const clearGallery = () => {
  if (confirm('¿Estás seguro de eliminar todas las imágenes de la galería?')) {
    // Liberar memoria de las URLs
    previewAdditionalImages.value.forEach(img => URL.revokeObjectURL(img.url));
    previewAdditionalImages.value = [];
    existingExtraImages.value = [];
    keepImages.value = [];
  }
};

const viewImage = (imageSrc) => {
  // Abrir imagen en una nueva ventana o modal
  window.open(getImageUrl(imageSrc), '_blank');
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

/* ESTILOS PROFESIONALES PARA IMÁGENES */

/* Labels mejorados */
.form-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  margin-bottom: 8px;
  color: white;
}

.label-icon {
  font-size: 18px;
  color: var(--color-primary);
}

.required {
  color: #ff4757;
  font-weight: bold;
}

.optional {
  color: #7f8c8d;
  font-size: 0.9em;
}

/* Upload Zone Profesional Mejorada */
.upload-zone.professional {
  border: 2px dashed rgba(255,255,255,0.2);
  border-radius: 16px;
  padding: 32px 24px;
  text-align: center;
  background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  backdrop-filter: blur(10px);
}

.upload-zone.professional::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, transparent 30%, rgba(0, 161, 255, 0.03) 50%, transparent 70%);
  transform: translateX(-100%);
  transition: transform 0.6s ease;
}

.upload-zone.professional:hover::before {
  transform: translateX(100%);
}

.upload-zone.professional:hover {
  border-color: var(--color-primary);
  background: linear-gradient(135deg, rgba(0, 161, 255, 0.1) 0%, rgba(0, 212, 170, 0.05) 100%);
  transform: translateY(-4px) scale(1.02);
  box-shadow: 
    0 12px 40px rgba(0, 161, 255, 0.15),
    0 4px 20px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.upload-zone.professional.drag-over {
  border-color: var(--color-primary);
  background: linear-gradient(135deg, rgba(0, 161, 255, 0.2) 0%, rgba(0, 212, 170, 0.1) 100%);
  transform: scale(1.05);
  box-shadow: 
    0 16px 50px rgba(0, 161, 255, 0.25),
    0 8px 30px rgba(0, 0, 0, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.upload-zone.professional.loading {
  pointer-events: none;
  opacity: 0.8;
  transform: scale(0.98);
}

/* Upload Prompt Profesional Centrado */
.upload-prompt.professional.centered {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 20px;
  text-align: center;
}

.upload-prompt.gallery.centered {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 20px;
  text-align: center;
}

/* Preview Container Profesional Mejorado */
.preview-container.professional {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}

.preview-container.professional.edit-mode {
  gap: 0;
}

.image-wrapper {
  position: relative;
  width: 220px;
  height: 220px;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 
    0 12px 40px rgba(0, 0, 0, 0.4),
    0 4px 20px rgba(0, 0, 0, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
  backdrop-filter: blur(10px);
}

.image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  filter: brightness(1.05);
}

.image-wrapper:hover img {
  transform: scale(1.08);
  filter: brightness(1.1);
}

.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.6) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 16px;
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(4px);
}

.image-overlay.edit-overlay {
  background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);
  gap: 20px;
}

.image-wrapper:hover .image-overlay {
  opacity: 1;
}

/* Botones de icono para modo edición */
.btn-icon {
  background: rgba(255,255,255,0.95);
  border: none;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 20px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.edit-btn:hover {
  background: linear-gradient(135deg, var(--color-primary), #00d2ff);
  color: white;
  transform: scale(1.1) translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 161, 255, 0.4);
}

.delete-btn:hover {
  background: linear-gradient(135deg, #ef4444, #f87171);
  color: white;
  transform: scale(1.1) translateY(-2px);
  box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.image-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  font-size: 14px;
  color: #bdc3c7;
}

.file-name {
  font-weight: 600;
  color: white;
}

.file-size {
  font-size: 12px;
  color: #7f8c8d;
}

/* Estado de carga */
.upload-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  padding: 20px;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(255,255,255,0.1);
  border-top: 3px solid var(--color-primary);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.progress-bar {
  width: 200px;
  height: 6px;
  background: rgba(255,255,255,0.1);
  border-radius: 3px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--color-primary) 0%, #00d2ff 100%);
  border-radius: 3px;
  transition: width 0.3s ease;
}

/* Upload Icon Profesional Mejorado */
.upload-icon {
  width: 72px;
  height: 72px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--color-primary) 0%, #00d2ff 100%);
  border-radius: 50%;
  color: white;
  font-size: 32px;
  animation: pulse 2s infinite;
  box-shadow: 
    0 8px 25px rgba(0, 161, 255, 0.3),
    0 4px 16px rgba(0, 161, 255, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

@keyframes pulse {
  0%, 100% { 
    transform: scale(1); 
    box-shadow: 
      0 8px 25px rgba(0, 161, 255, 0.3),
      0 4px 16px rgba(0, 161, 255, 0.2),
      inset 0 1px 0 rgba(255, 255, 255, 0.2);
  }
  50% { 
    transform: scale(1.08); 
    box-shadow: 
      0 12px 35px rgba(0, 161, 255, 0.4),
      0 6px 20px rgba(0, 161, 255, 0.3),
      inset 0 1px 0 rgba(255, 255, 255, 0.3);
  }
}

/* Galería Profesional Mejorada */
.gallery-zone {
  border: 2px dashed rgba(255,255,255,0.15);
  background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);
  backdrop-filter: blur(8px);
}

.gallery-preview {
  margin-top: 24px;
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 16px;
  padding: 24px;
  background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.gallery-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.gallery-header h4 {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: white;
  background: linear-gradient(135deg, #fff, #94a3b8);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.btn-clear-all {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 10px;
  padding: 8px 16px;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  font-weight: 600;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(8px);
}

.btn-clear-all:hover {
  background: linear-gradient(135deg, rgba(255,71,87,0.2), rgba(255,71,87,0.1));
  border-color: #ff4757;
  color: #ff4757;
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(255, 71, 87, 0.2);
}

.gallery-grid.professional {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 20px;
}

.gallery-item {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
  backdrop-filter: blur(8px);
  box-shadow: 
    0 4px 20px rgba(0, 0, 0, 0.2),
    0 2px 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-item:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 
    0 8px 30px rgba(0, 0, 0, 0.3),
    0 4px 20px rgba(0, 0, 0, 0.2);
}

.gallery-image-wrapper {
  position: relative;
  width: 100%;
  height: 140px;
  overflow: hidden;
}

.gallery-image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  filter: brightness(1.05);
}

.gallery-item:hover .gallery-image-wrapper img {
  transform: scale(1.1);
  filter: brightness(1.1);
}

.gallery-image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.6) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(4px);
}

.gallery-item:hover .gallery-image-overlay {
  opacity: 1;
}

.btn-view, .btn-remove {
  background: rgba(255,255,255,0.95);
  border: none;
  border-radius: 10px;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 18px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.btn-view:hover {
  background: linear-gradient(135deg, var(--color-primary), #00d2ff);
  color: white;
  transform: scale(1.1) translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 161, 255, 0.3);
}

.btn-remove:hover {
  background: linear-gradient(135deg, #ef4444, #f87171);
  color: white;
  transform: scale(1.1) translateY(-2px);
  box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
}

.new-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  background: linear-gradient(135deg, #00d2ff 0%, var(--color-primary) 100%);
  color: white;
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 10px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 16px rgba(0, 161, 255, 0.3);
  animation: pulse 2s infinite;
}

.gallery-item-info {
  padding: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12px;
}

.item-status {
  color: #7f8c8d;
}

.item-index {
  color: #bdc3c7;
  font-weight: 600;
}

.gallery-item.new .item-status {
  color: #00d2ff;
}

.gallery-item.existing .item-status {
  color: #27ae60;
}

/* Estilos responsive */
@media (max-width: 600px) {
  .form-grid, .offer-grid { 
    grid-template-columns: 1fr; 
  }
  
  .gallery-grid.professional {
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  }
  
  .image-wrapper {
    width: 160px;
    height: 160px;
  }
}

/* Estilos restantes del formulario */
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
