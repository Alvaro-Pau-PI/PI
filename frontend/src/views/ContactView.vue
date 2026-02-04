<template>
  <div class="contact-container">
    <h1>Contacta amb Nosaltres</h1>
    <p class="subtitle">Tens dubtes o vols demanar pressupost? Escriu-nos!</p>

    <div class="contact-box">
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="name">Nom</label>
          <input type="text" id="name" v-model="form.name" required placeholder="El teu nom" />
        </div>
        <div class="form-group">
          <label for="email">Correu Electrònic</label>
          <input type="email" id="email" v-model="form.email" required placeholder="tucorreu@exemple.com" />
        </div>
        <div class="form-group">
          <label for="subject">Assumpte</label>
          <input type="text" id="subject" v-model="form.subject" required placeholder="Assumpte del missatge" />
        </div>
        <div class="form-group">
          <label for="message">Missatge</label>
          <textarea id="message" v-model="form.message" rows="5" required placeholder="Escriu aquí el teu missatge..."></textarea>
        </div>

        <button type="submit" class="btn-send">Enviar Missatge</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
});

const submitForm = () => {
  // Placeholder logic
  alert("Missatge enviat correctament! (Simulació)");
  form.value = { name: '', email: '', subject: '', message: '' };
};
</script>

<style scoped>
.contact-container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 60px 40px;
  color: #EAEAEA;
  min-height: 70vh;
}

h1 {
  text-align: center;
  font-size: 3em;
  margin-bottom: 20px;
  background: linear-gradient(135deg, #00A1FF, #00E4FF);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.subtitle { 
  text-align: center;
  color: #9BA3B0; 
  margin-bottom: 50px;
  font-size: 1.2em;
}

.contact-box {
  background: #242833;
  padding: 50px;
  border-radius: 12px;
  border: 1px solid #3A4150;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.form-group { 
  margin-bottom: 25px;
}

label { 
  display: block; 
  margin-bottom: 10px; 
  font-weight: 600;
  color: #00A1FF;
  font-size: 1.05em;
}

input, textarea {
  width: 100%;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #3A4150;
  background: #1A1D24;
  color: white;
  font-family: inherit;
  font-size: 1em;
  transition: all 0.3s;
}

input:focus, textarea:focus { 
  border-color: #00A1FF; 
  outline: none;
  box-shadow: 0 0 0 3px rgba(0, 161, 255, 0.1);
}

textarea {
  resize: vertical;
  min-height: 150px;
}

.btn-send {
  width: 100%;
  padding: 16px;
  background-color: #00A1FF;
  border: none;
  border-radius: 8px;
  color: white;
  font-weight: bold;
  cursor: pointer;
  font-size: 1.15em;
  transition: all 0.3s;
  margin-top: 10px;
}

.btn-send:hover { 
  background-color: #0088d4;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3);
}

/* Diseño responsivo */
@media (max-width: 768px) {
  .contact-container {
    padding: 40px 20px;
  }
  
  h1 {
    font-size: 2.2em;
  }
  
  .contact-box {
    padding: 30px 25px;
  }
  
  textarea {
    min-height: 120px;
  }
}

@media (max-width: 480px) {
  .contact-container {
    padding: 30px 15px;
  }
  
  h1 {
    font-size: 1.8em;
  }
  
  .subtitle {
    font-size: 1em;
  }
  
  .contact-box {
    padding: 25px 20px;
  }
  
  input, textarea {
    padding: 12px;
  }
  
  .btn-send {
    padding: 14px;
    font-size: 1em;
  }
}
</style>
