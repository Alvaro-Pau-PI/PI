<template>
  <div class="login-container">
    <div class="login-card">
      <div class="logo-container">
        <img src="/img/LOGO AlberoPerezTech.png" alt="Logo" class="login-logo"/>
      </div>
      <h2>{{ $t('register.title') }}</h2>
      <form @submit.prevent="handleRegister">
        
        <div class="form-group">
          <label for="name">{{ $t('register.name') }}</label>
          <input type="text" id="name" v-model="name" required :placeholder="$t('register.name_ph')" />
        </div>

        <div class="form-group">
          <label for="email">{{ $t('register.email') }}</label>
          <input type="email" id="email" v-model="email" required :placeholder="$t('register.email_ph')" />
        </div>

        <div class="form-group">
          <label for="password">{{ $t('register.password') }}</label>
          <input type="password" id="password" v-model="password" required :placeholder="$t('register.password_ph')" />
        </div>
        
        <div class="form-group">
          <label for="password_confirmation">{{ $t('register.confirm_password') }}</label>
          <input type="password" id="password_confirmation" v-model="password_confirmation" required :placeholder="$t('register.confirm_ph')" />
        </div>

        <div v-if="errorMessage" class="error-msg">
            {{ errorMessage }}
        </div>

        <button type="submit" :disabled="authStore.loading">
            {{ authStore.loading ? $t('register.creating') : $t('register.register') }}
        </button>

        <div class="register-link">
            {{ $t('register.have_account') }} <router-link to="/login">{{ $t('register.login_here') }}</router-link>
        </div>
        <div class="back-link">
             <router-link to="/">← {{ $t('register.back') }}</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');

const authStore = useAuthStore();
const router = useRouter();
const localError = ref(null);

const errorMessage = computed(() => {
  if (localError.value) return localError.value;
  if (authStore.errors?.message) return authStore.errors.message;
  if (authStore.errors) {
    // Extraer el primer error si es un objeto con campos
    const firstError = Object.values(authStore.errors)[0];
    return Array.isArray(firstError) ? firstError[0] : firstError;
  }
  return null;
});

const handleRegister = async () => {
    localError.value = null;
    authStore.errors = null;
    
    if (password.value !== password_confirmation.value) {
        localError.value = 'Las contraseñas no coinciden. Vuelve a intentarlo.';
        return;
    }
    
    if (password.value.length < 8) {
        localError.value = 'La contraseña debe tener al menos 8 caracteres.';
        return;
    }

    try {
        await authStore.register({
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value
        });
        router.push('/');
    } catch (error) {
        // Manejar diferentes tipos de errores
        if (error.response) {
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                if (errors?.email) {
                    localError.value = 'Este email ya está registrado. Prueba a iniciar sesión.';
                } else {
                    localError.value = 'Los datos introducidos no son válidos. Revisa los campos.';
                }
            } else if (error.response.status === 404) {
                localError.value = 'El servicio de registro no está disponible. Contacta con el administrador.';
            } else {
                localError.value = 'Error en el servidor. Vuelve a intentarlo más tarde.';
            }
        } else if (error.message?.includes('Network Error')) {
            localError.value = 'No se puede conectar con el servidor. Verifica tu conexión a internet.';
        } else {
            localError.value = 'Error desconocido. Vuelve a intentarlo.';
        }
    }
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  background-color: #121418;
  padding: 40px 20px; /* Padding lateral para móvil */
}

.login-card {
  background-color: #1A1D24;
  padding: 40px;
  border-radius: 8px;
  width: 100%;
  max-width: 800px; /* Aumentado a 800px */
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

label {
  display: block;
  margin-bottom: 8px;
  color: #00A1FF;
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

button {
  width: 100%;
  padding: 12px;
  background-color: #00A1FF;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1em;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
  margin-top: 10px;
}

button:hover {
  background-color: #0088D6;
  transform: translateY(-1px);
}

button:disabled {
  background-color: #555;
  cursor: not-allowed;
  transform: none;
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

.register-link {
    text-align: center;
    margin-top: 25px;
    color: #9BA3B0;
    font-size: 0.9em;
    padding-top: 20px;
    border-top: 1px solid #3A4150;
}

.register-link a {
    color: #00A1FF;
    text-decoration: none;
    font-weight: 500;
}

.register-link a:hover {
    text-decoration: underline;
    color: #33b5ff;
}

.back-link {
    text-align: center;
    margin-top: 15px;
}
.back-link a {
    color: #6c757d;
    text-decoration: none;
    font-size: 0.85em;
    transition: color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.back-link a:hover {
    color: #aaa;
}
</style>
