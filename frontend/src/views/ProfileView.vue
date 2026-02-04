<template>
  <div class="profile-container">
    <h1 class="page-title">El meu perfil</h1>

    <div class="profile-grid">
      <!-- Tarjeta de Información Personal -->
      <div class="glass-card">
        <h2 class="card-title">Informació Personal</h2>
        <p class="card-desc">Actualitza la teva informació de compte.</p>

        <form @submit.prevent="handleUpdateProfile" class="profile-form">
          <div class="form-group">
            <label for="name">Nom</label>
            <input 
              id="name" 
              v-model="profileForm.name" 
              type="text" 
              required 
              class="form-input"
            />
            <span v-if="authStore.errors?.name" class="error-msg">{{ authStore.errors.name[0] }}</span>
          </div>

          <div class="form-group">
            <label for="email">Correu Electrònic</label>
            <input 
              id="email" 
              v-model="profileForm.email" 
              type="email" 
              required 
              class="form-input"
            />
            <span v-if="authStore.errors?.email" class="error-msg">{{ authStore.errors.email[0] }}</span>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="authStore.loading">
              {{ authStore.loading ? 'Guardant...' : 'Guardar Canvis' }}
            </button>
            <p v-if="profileMessage" class="success-msg">{{ profileMessage }}</p>
          </div>
        </form>
      </div>

      <!-- Tarjeta de Cambio de Contraseña -->
      <div class="glass-card">
        <h2 class="card-title">Canviar Contrasenya</h2>
        <p class="card-desc">Assegura el teu compte amb una contrasenya forta.</p>

        <form @submit.prevent="handleUpdatePassword" class="profile-form">
          <div class="form-group">
            <label for="current_password">Contrasenya Actual</label>
            <input 
              id="current_password" 
              v-model="passwordForm.current_password" 
              type="password" 
              required 
              class="form-input"
            />
            <span v-if="authStore.errors?.current_password" class="error-msg">{{ authStore.errors.current_password[0] }}</span>
          </div>

          <div class="form-group">
            <label for="password">Nova Contrasenya</label>
            <input 
              id="password" 
              v-model="passwordForm.password" 
              type="password" 
              required 
              class="form-input"
            />
            <span v-if="authStore.errors?.password" class="error-msg">{{ authStore.errors.password[0] }}</span>
          </div>

          <div class="form-group">
            <label for="password_confirmation">Confirmar Contrasenya</label>
            <input 
              id="password_confirmation" 
              v-model="passwordForm.password_confirmation" 
              type="password" 
              required 
              class="form-input"
            />
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="authStore.loading">
              {{ authStore.loading ? 'Actualitzant...' : 'Actualitzar Contrasenya' }}
            </button>
            <p v-if="passwordMessage" class="success-msg">{{ passwordMessage }}</p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const profileForm = ref({
  name: '',
  email: ''
});

const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
});

const profileMessage = ref('');
const passwordMessage = ref('');

onMounted(() => {
  if (authStore.user) {
    profileForm.value.name = authStore.user.name;
    profileForm.value.email = authStore.user.email;
  }
});

const handleUpdateProfile = async () => {
    profileMessage.value = '';
    try {
        await authStore.updateProfile(profileForm.value);
        profileMessage.value = 'Perfil actualitzat correctament.';
    } catch (e) {
        // Errores gestionados por el store
    }
};

const handleUpdatePassword = async () => {
    passwordMessage.value = '';
    try {
        await authStore.updatePassword(passwordForm.value);
        passwordMessage.value = 'Contrasenya actualitzada correctament.';
        passwordForm.value = { current_password: '', password: '', password_confirmation: '' };
    } catch (e) {
        // Errores gestionados por el store
    }
};
</script>

<style scoped>
.profile-container {
  width: 100%;
  max-width: 1600px;
  margin: 0 auto;
  padding: 40px 40px 120px 40px; /* Padding extra abajo para no tocar el footer */
  min-height: 80vh;
}

.page-title {
  font-size: 3em;
  color: #fff;
  margin-bottom: 50px;
  text-align: center;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.profile-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: start;
}

.glass-card {
  background: rgba(30, 35, 45, 0.6);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 24px;
  padding: 50px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
  height: 100%;
}

.card-title {
  font-size: 1.8em;
  color: #fff;
  margin-bottom: 15px;
  font-weight: 700;
}

.card-desc {
  color: #94a3b8;
  margin-bottom: 30px;
  font-size: 1em;
}

.profile-form .form-group {
  margin-bottom: 25px;
}

.profile-form label {
  display: block;
  margin-bottom: 10px;
  color: #cbd5e1;
  font-weight: 600;
}

.form-input {
  width: 100%;
  padding: 14px 18px;
  background: rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  color: #fff;
  font-size: 1.05em;
  transition: all 0.3s ease;
}

.form-input:focus {
  border-color: #0ea5e9;
  outline: none;
  background: rgba(0, 0, 0, 0.5);
  box-shadow: 0 0 15px rgba(14, 165, 233, 0.2);
}

.error-msg {
  color: #ef4444;
  font-size: 0.9em;
  margin-top: 8px;
  display: block;
}

.success-msg {
  color: #10b981;
  font-size: 1em;
  margin-top: 15px;
  font-weight: 600;
  text-align: center;
}

.btn-primary {
  background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
  color: white;
  border: none;
  padding: 15px 30px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 1.1em;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
  margin-top: 20px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
}

.btn-primary:disabled {
  background: #475569;
  cursor: not-allowed;
  opacity: 0.7;
}

@media (max-width: 1024px) {
  .profile-container {
    padding: 20px;
  }
  .profile-grid {
    grid-template-columns: 1fr;
    gap: 40px;
    max-width: 800px;
    margin: 0 auto;
  }
  .glass-card {
    padding: 30px;
  }
}
</style>
