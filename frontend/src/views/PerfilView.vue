<template>
  <div class="profile-container">
    <div class="profile-dashboard-layout">
      <!-- Sidebar (Componente Reutilizable) -->
      <UserSidebar activeTab="profile" />

      <!-- Área de Formularios Principal -->
      <main class="profile-content">
        <!-- Tarjeta de Información Personal -->
        <section class="content-section glass-card">
          <header class="section-header">
            <h2 class="card-title"><span class="material-icons title-icon">badge</span> Información Personal</h2>
            <p class="card-desc">Actualiza tus datos básicos de contacto.</p>
          </header>

          <form @submit.prevent="handleUpdateProfile" class="profile-form">
            <div class="form-grid">
              <div class="form-group">
                <label for="name">Nombre</label>
                <div class="input-wrapper">
                  <span class="material-icons input-icon">person</span>
                  <input 
                    id="name" 
                    v-model="profileForm.name" 
                    type="text" 
                    required 
                    class="form-input"
                    placeholder="Tu nombre completo"
                  />
                </div>
                <span v-if="authStore.errors?.name" class="error-msg">{{ authStore.errors.name[0] }}</span>
              </div>

              <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-wrapper">
                  <span class="material-icons input-icon">email</span>
                  <input 
                    id="email" 
                    v-model="profileForm.email" 
                    type="email" 
                    required 
                    class="form-input"
                    placeholder="correo@ejemplo.com"
                  />
                </div>
                <span v-if="authStore.errors?.email" class="error-msg">{{ authStore.errors.email[0] }}</span>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-primary" :disabled="authStore.loading">
                <span class="material-icons">save</span>
                {{ authStore.loading ? 'Guardando...' : 'Guardar Cambios' }}
              </button>
              <transition name="fade">
                <p v-if="profileMessage" class="success-msg"><span class="material-icons">check_circle</span> {{ profileMessage }}</p>
              </transition>
            </div>
          </form>
        </section>

        <!-- Tarjeta de Cambio de Contraseña -->
        <section class="content-section glass-card">
          <header class="section-header">
            <h2 class="card-title"><span class="material-icons title-icon">lock_reset</span> Seguridad</h2>
            <p class="card-desc">Asegura tu cuenta cambiando la contraseña periódicamente.</p>
          </header>

          <form @submit.prevent="handleUpdatePassword" class="profile-form">
            <div class="form-group full-width">
              <label for="current_password">Contraseña Actual</label>
              <div class="input-wrapper">
                <span class="material-icons input-icon">password</span>
                <input 
                  id="current_password" 
                  v-model="passwordForm.current_password" 
                  type="password" 
                  required 
                  class="form-input"
                  placeholder="Introduce tu contraseña actual"
                />
              </div>
              <span v-if="authStore.errors?.current_password" class="error-msg">{{ authStore.errors.current_password[0] }}</span>
            </div>

            <div class="form-grid">
              <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <div class="input-wrapper">
                  <span class="material-icons input-icon">vpn_key</span>
                  <input 
                    id="password" 
                    v-model="passwordForm.password" 
                    type="password" 
                    required 
                    class="form-input"
                    placeholder="Introduce una contraseña nueva"
                  />
                </div>
                <span v-if="authStore.errors?.password" class="error-msg">{{ authStore.errors.password[0] }}</span>
              </div>

              <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <div class="input-wrapper">
                  <span class="material-icons input-icon">verified_user</span>
                  <input 
                    id="password_confirmation" 
                    v-model="passwordForm.password_confirmation" 
                    type="password" 
                    required 
                    class="form-input"
                    placeholder="Repite la contraseña nueva"
                  />
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-primary" :disabled="authStore.loading">
                <span class="material-icons">update</span>
                {{ authStore.loading ? 'Actualizando...' : 'Actualizar Contraseña' }}
              </button>
              <transition name="fade">
                <p v-if="passwordMessage" class="success-msg"><span class="material-icons">check_circle</span> {{ passwordMessage }}</p>
              </transition>
            </div>
          </form>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import UserSidebar from '@/components/UserSidebar.vue';

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
        profileMessage.value = 'Perfil actualizado correctamente.';
        setTimeout(() => profileMessage.value = '', 4000);
    } catch (e) {
        // Errores gestionados por el store
    }
};

const handleUpdatePassword = async () => {
    passwordMessage.value = '';
    try {
        await authStore.updatePassword(passwordForm.value);
        passwordMessage.value = 'Contraseña actualizada correctamente.';
        passwordForm.value = { current_password: '', password: '', password_confirmation: '' };
        setTimeout(() => passwordMessage.value = '', 4000);
    } catch (e) {
        // Errores gestionados por el store
    }
};
</script>

<style scoped>
/* ══════════════════════════════════════════════════════════════
   LAYOUT PRINCIPAL (DASHBOARD)
   ══════════════════════════════════════════════════════════════ */
.profile-container {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  padding: 40px 20px 100px;
  min-height: calc(100vh - 80px);
}

.profile-dashboard-layout {
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 40px;
  align-items: start;
}

/* ══════════════════════════════════════════════════════════════
   TARJETA GLASS GENÉRICA
   ══════════════════════════════════════════════════════════════ */
.glass-card {
  background: rgba(30, 34, 43, 0.65);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}



/* ══════════════════════════════════════════════════════════════
   CONTENIDO PRINCIPAL (COLUMNA DERECHA)
   ══════════════════════════════════════════════════════════════ */
.profile-content {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.content-section {
  padding: 45px;
}

.section-header {
  margin-bottom: 35px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  padding-bottom: 20px;
}

.card-title {
  font-size: 1.6em;
  color: #fff;
  margin-bottom: 8px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
}

.title-icon {
  color: #00A1FF;
  font-size: 1.2em;
  background: rgba(0, 161, 255, 0.1);
  padding: 8px;
  border-radius: 10px;
}

.card-desc {
  color: #94a3b8;
  font-size: 0.95em;
  line-height: 1.5;
}

/* ══════════════════════════════════════════════════════════════
   FORMULARIOS Y CAMPOS
   ══════════════════════════════════════════════════════════════ */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 25px;
}

.form-group {
  margin-bottom: 20px;
}
.form-group.full-width {
  grid-column: 1 / -1;
}

.profile-form label {
  display: block;
  margin-bottom: 10px;
  color: #cbd5e1;
  font-weight: 600;
  font-size: 0.95em;
  transition: color 0.3s ease;
}

.profile-form .form-group:focus-within label {
  color: #00A1FF;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 16px;
  color: #64748b;
  font-size: 1.3em;
  transition: color 0.3s ease;
  z-index: 2;
}

.profile-form .form-group:focus-within .input-icon {
  color: #00A1FF;
}

.form-input {
  width: 100%;
  padding: 14px 18px 14px 48px;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  color: #fff;
  font-size: 1em;
  transition: all 0.3s ease;
  position: relative;
  z-index: 1;
}

.form-input::placeholder {
  color: #475569;
}

.form-input:focus {
  border-color: #0ea5e9;
  outline: none;
  background: rgba(0, 0, 0, 0.6);
  box-shadow: 0 0 15px rgba(14, 165, 233, 0.3);
}

.error-msg {
  color: #ef4444;
  font-size: 0.85em;
  margin-top: 8px;
  display: block;
  animation: slideDown 0.3s ease-out forwards;
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

.success-msg {
  color: #10b981;
  font-size: 1em;
  margin-top: 20px;
  font-weight: 600;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: rgba(16, 185, 129, 0.1);
  padding: 12px;
  border-radius: 8px;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

/* ══════════════════════════════════════════════════════════════
   BOTONES
   ══════════════════════════════════════════════════════════════ */
.form-actions {
  margin-top: 15px;
}

.btn-primary {
  background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
  color: white;
  border: none;
  padding: 16px 30px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 1.05em;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  width: fit-content;
  min-width: 220px;
  text-transform: uppercase;
  letter-spacing: 1px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-3px);
  box-shadow: 0 12px 25px rgba(14, 165, 233, 0.4);
}

.btn-primary:active:not(:disabled) {
  transform: translateY(-1px);
}

.btn-primary:active:not(:disabled)::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.4);
  border-radius: 50%;
  transform: translate(-50%, -50%);
  animation: ripple 0.6s ease-out forwards;
}

@keyframes ripple {
  0% { width: 0; height: 0; opacity: 1; }
  100% { width: 400px; height: 400px; opacity: 0; }
}

.btn-primary:disabled {
  background: #334155;
  color: #64748b;
  cursor: not-allowed;
  box-shadow: none;
}

/* Transición estática suave para los mensajes */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* ══════════════════════════════════════════════════════════════
   RESPONSIVE DESIGN COMPLETO
   ══════════════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .profile-dashboard-layout {
    grid-template-columns: 280px 1fr;
    gap: 30px;
  }
}

@media (max-width: 860px) {
  .profile-dashboard-layout {
    grid-template-columns: 1fr;
  }



  .form-grid {
    grid-template-columns: 1fr; /* Una columna entera en el móvil para formularios */
  }

  .btn-primary {
    width: 100%;
  }

  .content-section {
    padding: 30px;
  }
}
</style>
