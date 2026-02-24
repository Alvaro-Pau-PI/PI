<template>
  <div class="modal-backdrop" @click.self="$emit('close')">
    <div class="modal-content">
      <div class="modal-header">
        <h3>{{ isEditing ? 'Editar tu reseña' : 'Deja tu opinión' }}</h3>
        <button class="close-btn" @click="$emit('close')">&times;</button>
      </div>
      
      <form @submit.prevent="submitReview">
        <div class="form-group">
          <label>Puntuación (1-5)</label>
          <div class="stars-input">
            <span 
                v-for="star in 5" 
                :key="star" 
                @click="rating = star"
                :class="{ active: star <= rating }"
            >★</span>
          </div>
        </div>

        <div class="form-group">
          <textarea 
            v-model="comment" 
            placeholder="Escribe tu comentario..." 
            required
            rows="4"
          ></textarea>
        </div>

        <button type="submit" :disabled="submitting" class="submit-btn">
            {{ submitting ? 'Enviando...' : (isEditing ? 'Guardar cambios' : 'Enviar Valoración') }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  productId: { type: [Number, String], required: true },
  // Datos para edición (si existen, estamos editando)
  editReview: { type: Object, default: null }
});

const emit = defineEmits(['close', 'submit']);

// Si hay datos de edición, usarlos como valores iniciales
const isEditing = ref(!!props.editReview);
const rating = ref(props.editReview?.rating || 5);
const comment = ref(props.editReview?.text || props.editReview?.comment || '');
const submitting = ref(false);

const submitReview = async () => {
    submitting.value = true;
    try {
        await emit('submit', { 
          rating: rating.value, 
          text: comment.value,
          reviewId: props.editReview?.id || null
        });
        emit('close');
    } catch (e) {
        console.error(e);
    } finally {
        submitting.value = false;
    }
};
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.modal-content {
    background: #1A1D24;
    padding: 25px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    border: 1px solid #3A4150;
    color: #EAEAEA;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.close-btn {
    background: none;
    border: none;
    color: #9BA3B0;
    font-size: 1.5em;
    cursor: pointer;
}
.stars-input span {
    font-size: 2em;
    cursor: pointer;
    color: #3A4150;
    transition: color 0.2s;
}
.stars-input span.active {
    color: #f39c12;
}
textarea {
    width: 100%;
    background: #121418;
    border: 1px solid #3A4150;
    color: white;
    padding: 10px;
    border-radius: 4px;
    resize: vertical;
}
.submit-btn {
    width: 100%;
    padding: 10px;
    background: #00A1FF;
    color: white;
    border: none;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 15px;
}
.submit-btn:hover {
    background: #0088D6;
}
</style>
