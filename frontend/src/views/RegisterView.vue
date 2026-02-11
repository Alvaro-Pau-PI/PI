<template>
  <div class="login-container">
    <div class="login-card">
      <div class="logo-container">
        <img src="/img/LOGO AlberoPerezTech.png" alt="Logo" class="login-logo"/>
      </div>
      <h2>Crear Compte</h2>
      <form @submit.prevent="handleRegister">
        
        <div class="form-group">
          <label for="name">Nom</label>
          <input type="text" id="name" v-model="name" required placeholder="El teu nom" />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="email" required placeholder="tucorreu@exemple.com" />
        </div>

        <div class="form-group">
          <label for="password">Contrasenya</label>
          <input type="password" id="password" v-model="password" required placeholder="Mínim 8 caràcters" />
        </div>
        
        <div class="form-group">
          <label for="password_confirmation">Confirmar Contrasenya</label>
          <input type="password" id="password_confirmation" v-model="password_confirmation" required placeholder="Repeteix la contrasenya" />
        </div>

        <div v-if="errorMessage" class="error-msg">
            {{ errorMessage }}
        </div>

        <button type="submit" :disabled="authStore.loading">
            {{ authStore.loading ? 'Creant compte...' : "Registra't" }}
        </button>

        <div class="register-link">
            Ja tens compte? <router-link to="/login">Inicia Sessió</router-link>
        </div>
        <div class="back-link">
             <router-link to="/">← Tornar a la botiga</router-link>
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
        localError.value = 'Les contrasenyes no coincideixen. Torna-ho a intentar.';
        return;
    }
    
    if (password.value.length < 8) {
        localError.value = 'La contrasenya ha de tenir almenys 8 caràcters.';
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
                    localError.value = 'Aquest email ja està registrat. Prova a iniciar sessió.';
                } else {
                    localError.value = 'Les dades introduïdes no són vàlides. Revisa els camps.';
                }
            } else if (error.response.status === 404) {
                localError.value = 'El servei de registre no està disponible. Contacta amb l\'administrador.';
            } else {
                localError.value = 'Error en el servidor. Torna-ho a intentar més tard.';
            }
        } else if (error.message?.includes('Network Error')) {
            localError.value = 'No es pot connectar amb el servidor. Verifica la teva connexió a Internet.';
        } else {
            localError.value = 'Error desconegut. Torna-ho a intentar.';
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
  padding: 40px 0;
}

.login-card {
  background-color: #1A1D24;
  padding: 40px;
  border-radius: 8px;
  width: 100%;
  max-width: 450px;
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
  transition: background 0.3s;
  margin-top: 10px;
}

button:hover {
  background-color: #0088D6;
}

button:disabled {
  background-color: #555;
  cursor: not-allowed;
}

.error-msg {
  color: #e74c3c;
  background-color: rgba(231, 76, 60, 0.1);
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 20px;
  font-size: 0.9em;
}

.register-link {
    text-align: center;
    margin-top: 20px;
    color: #9BA3B0;
    font-size: 0.9em;
}

.register-link a {
    color: #00A1FF;
    text-decoration: none;
}

.register-link a:hover {
    text-decoration: underline;
}

.back-link {
    text-align: center;
    margin-top: 15px;
}
.back-link a {
    color: #9BA3B0;
    text-decoration: none;
    font-size: 0.85em;
    transition: color 0.3s;
}
.back-link a:hover {
    color: #FFFFFF;
}
</style>
