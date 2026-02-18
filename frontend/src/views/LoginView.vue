<template>
  <div class="login-container">
    <div class="login-card">
      <div class="logo-container">
        <img src="/img/LOGO AlberoPerezTech.png" alt="Logo" class="login-logo"/>
      </div>
      <h2>Iniciar Sessió</h2>
      <Form @submit="handleLogin" :validation-schema="schema" :initial-values="initialValues" v-slot="{ errors, isSubmitting }">
        <div class="form-group">
          <label for="email">Email</label>
          <Field name="email" type="email" id="email" class="form-control" :class="{ 'is-invalid': errors.email }" placeholder="nombre@gmail.com" />
          <ErrorMessage name="email" class="error-feedback" />
        </div>
        <div class="form-group">
          <label for="password">Contrasenya</label>
          <Field name="password" type="password" id="password" class="form-control" :class="{ 'is-invalid': errors.password }" placeholder="........" />
          <ErrorMessage name="password" class="error-feedback" />
        </div>
        
        <div class="form-group remember-me">
          <label class="checkbox-label">
            <input type="checkbox" v-model="rememberMe" />
            <span>Recorda'm</span>
          </label>
        </div>

        <div v-if="errorMessage" class="error-msg">
            {{ errorMessage }}
        </div>

        <button type="submit" :disabled="isSubmitting || authStore.loading" class="primary-btn">
            {{ (isSubmitting || authStore.loading) ? 'Entrant...' : 'Entrar' }}
        </button>

        <div class="divider">
          <span>o continua amb</span>
        </div>

        <button type="button" class="google-btn" @click="handleGoogleLogin">
           <svg class="google-icon" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
              <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.464 63.239 -14.754 63.239 Z"/>
                <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.769 -21.864 51.959 -21.864 51.129 C -21.864 50.299 -21.734 49.489 -21.484 48.729 L -21.484 45.639 L -25.464 45.639 C -26.284 47.269 -26.754 49.129 -26.754 51.129 C -26.754 53.129 -26.284 54.989 -25.464 56.619 L -21.484 53.529 Z"/>
                <path fill="#EA4335" d="M -14.754 43.769 C -12.984 43.769 -11.404 44.379 -10.154 45.579 L -6.734 42.159 C -8.804 40.229 -11.514 39.019 -14.754 39.019 C -19.464 39.019 -23.494 41.719 -25.464 45.639 L -21.484 48.729 C -20.534 45.879 -17.884 43.769 -14.754 43.769 Z"/>
              </g>
            </svg>
            <span>Iniciar sessió amb Google</span>
        </button>

        <div class="auth-links">
          <div class="register-link">
             <span class="text-muted">No tens compte?</span> 
             <router-link to="/register" class="highlight-link">Registra't aquí</router-link>
          </div>
          <div class="back-link">
               <router-link to="/" class="subtle-link">
                 <i class="bi bi-arrow-left"></i> Tornar a la botiga
               </router-link>
          </div>
        </div>
      </Form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter, useRoute } from 'vue-router';
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();
const localError = ref(null);
const rememberMe = ref(false);

const schema = yup.object({
    email: yup.string().required('El email és obligatori').email('Email no vàlid'),
    password: yup.string().required('La contrasenya és obligatoria').min(6, 'Mínim 6 caràcters')
});

const errorMessage = computed(() => {
  if (localError.value) return localError.value;
  if (authStore.errors?.message) return authStore.errors.message;
  if (authStore.errors) return 'Les credencials són incorrectes.';
  return null;
});

const handleLogin = async (values) => {
  localError.value = null;
  authStore.errors = null;
  
  try {
    // Pasar remember: true/false junto con credenciales
    await authStore.login({ ...values, remember: rememberMe.value });
    
    // Gestión de "Recordar Email" en local
    if (rememberMe.value) {
        localStorage.setItem('rememberedEmail', values.email);
    } else {
        localStorage.removeItem('rememberedEmail');
    }

    const redirectPath = route.query.redirect || '/';
    router.push(redirectPath);
  } catch (error) {
    if (error.response) {
      if (error.response.status === 401) {
        localError.value = 'Email o contrasenya incorrectes.';
      } else if (error.response.status === 422) {
        localError.value = 'Dades no vàlides.';
      } else {
        localError.value = 'Error en el servidor.';
      }
    } else {
      localError.value = 'Error de connexió.';
    }
  }
};

// Cargar email guardado al iniciar
if (localStorage.getItem('rememberedEmail')) {
    // Pre-rellenamos el valor inicial del formulario (usando 'initialValues' si usáramos useForm, 
    // pero como usamos <Form> de vee-validate, podemos pasarlo como prop o setearlo en un ref si lo vinculamos)
    // Para simplificar con VeeValidate Component:
}
// Nota: Con el componente <Form> de vee-validate, la prop initial-values es la clave.
const initialValues = {
    email: localStorage.getItem('rememberedEmail') || '',
    password: ''
};

if (initialValues.email) {
    rememberMe.value = true;
}

const handleGoogleLogin = () => {
    window.location.href = `${import.meta.env.VITE_API_URL || 'http://localhost:8000'}/auth/google`;
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  background-color: #121418; /* Fondo oscuro global */
}

.login-card {
  background-color: #1A1D24;
  padding: 40px;
  border-radius: 8px;
  width: 100%;
  max-width: 800px; /* Ancho aumentado para escritorio */
  box-shadow: 0 4px 15px rgba(0,0,0,0.5);
  border: 1px solid #3A4150;
}

.logo-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.login-logo {
    height: 80px;
}

h2 {
  text-align: center;
  color: #EAEAEA;
  margin-bottom: 30px;
  font-weight: 600;
}

.form-group {
  margin-bottom: 20px;
}

.remember-me {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  color: #EAEAEA;
  font-weight: 400;
}

.checkbox-label input[type="checkbox"] {
  width: auto;
  margin: 0;
  cursor: pointer;
}

label {
  display: block;
  margin-bottom: 8px;
  color: #00A1FF; /* Azul claro para labels */
  font-weight: 500;
}

input {
  width: 100%;
  padding: 12px;
  background-color: #121418;
  border: 1px solid #3A4150;
  border-radius: 4px;
  color: #FFFFFF;
  font-size: 1em;
  outline: none;
  transition: border-color 0.3s;
}

input:focus {
  border-color: #00A1FF;
}

/* Botones Generales */
button {
  width: 100%;
  padding: 12px;
  border-radius: 6px;
  font-size: 1em;
  font-weight: 600;
  cursor: pointer;
  border: none;
  transition: all 0.2s ease;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}

.primary-btn {
  background-color: #00A1FF;
  color: white;
  margin-top: 10px;
}

.primary-btn:hover {
  background-color: #0088D6;
  transform: translateY(-1px);
}

.primary-btn:disabled {
  background-color: #555;
  cursor: not-allowed;
  transform: none;
}

/* Divisor */
.divider {
  display: flex;
  align-items: center;
  text-align: center;
  margin: 25px 0;
  color: #6c757d;
  font-size: 0.9em;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #3A4150;
}

.divider span {
  padding: 0 15px;
  background-color: #1A1D24; /* Oculta la línea detrás del texto */
}

/* Auth Links Container */
.auth-links {
  margin-top: 25px;
  display: flex;
  flex-direction: column;
  gap: 15px;
  text-align: center;
  border-top: 1px solid #3A4150;
  padding-top: 20px;
}

/* Enlaces */
.text-muted {
  color: #888;
  margin-right: 5px;
}

.highlight-link {
  color: #00A1FF;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

.highlight-link:hover {
  color: #33b5ff;
  text-decoration: underline;
}

.subtle-link {
  color: #6c757d;
  text-decoration: none;
  font-size: 0.9em;
  transition: color 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.subtle-link:hover {
  color: #aaa;
}

/* Opcional: Si tienes iconos de bootstrap importados globalmente, esto funcionará. 
   Si no, podrías necesitar un svg para la flecha también o ignorar el icono por ahora. */

/* Botón Google */
.google-btn {
  background-color: #ffffff;
  color: #1f1f1f;
  margin-top: 0;
  border: 1px solid #ddd;
  position: relative;
}

.google-btn:hover {
  background-color: #f1f1f1;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.google-icon {
  width: 20px;
  height: 20px;
}

/* Validation Styles */
.form-control {
  width: 100%;
  padding: 12px;
  background-color: #121418;
  border: 1px solid #3A4150;
  border-radius: 4px;
  color: #FFFFFF;
  font-size: 1em;
  outline: none;
  transition: border-color 0.3s;
}

.form-control:focus {
  border-color: #00A1FF;
}

.form-control.is-invalid {
  border-color: #ff4444;
}

.error-feedback {
  color: #ff4444;
  font-size: 0.85em;
  margin-top: 5px;
  display: block;
}

.error-msg {
    color: #ff4444;
    background: rgba(255, 68, 68, 0.1);
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    text-align: center;
    border: 1px solid rgba(255, 68, 68, 0.2);
}

</style>
