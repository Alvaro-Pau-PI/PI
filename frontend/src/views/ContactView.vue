<template>
  <div class="contact-container">
    <h1>Contacta amb Nosaltres</h1>
    <p class="subtitle">Tens dubtes o vols demanar pressupost? Escriu-nos!</p>

    <div class="contact-box">
      <!-- Formulari validat amb VeeValidate + Yup -->
      <Form @submit="submitForm" :validation-schema="schema" v-slot="{ errors, isSubmitting }">
        <div class="form-group">
          <label for="name">Nom</label>
          <Field name="name" type="text" id="name" class="form-control" 
            :class="{ 'is-invalid': errors.name }" placeholder="El teu nom" />
          <ErrorMessage name="name" class="error-feedback" />
        </div>
        <div class="form-group">
          <label for="email">Correu Electrònic</label>
          <Field name="email" type="email" id="email" class="form-control" 
            :class="{ 'is-invalid': errors.email }" placeholder="tucorreu@exemple.com" />
          <ErrorMessage name="email" class="error-feedback" />
        </div>
        <div class="form-group">
          <label for="subject">Assumpte</label>
          <Field name="subject" type="text" id="subject" class="form-control" 
            :class="{ 'is-invalid': errors.subject }" placeholder="Assumpte del missatge" />
          <ErrorMessage name="subject" class="error-feedback" />
        </div>
        <div class="form-group">
          <label for="message">Missatge</label>
          <Field name="message" as="textarea" id="message" class="form-control" 
            :class="{ 'is-invalid': errors.message }" rows="5" 
            placeholder="Escriu aquí el teu missatge..." />
          <ErrorMessage name="message" class="error-feedback" />
        </div>

        <button type="submit" class="btn-send" :disabled="isSubmitting">
          {{ isSubmitting ? 'Enviant...' : 'Enviar Missatge' }}
        </button>
      </Form>
    </div>
  </div>
</template>

<script setup>
// Importamos los componentes de VeeValidate y Yup para la validación reactiva
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import Swal from 'sweetalert2';

// Esquema de validación con Yup: definimos las reglas de cada campo
const schema = yup.object({
  name: yup.string()
    .required('El nom és obligatori')
    .min(2, 'El nom ha de tenir almenys 2 caràcters'),
  email: yup.string()
    .required('El correu electrònic és obligatori')
    .email('El format del correu no és vàlid'),
  subject: yup.string()
    .required("L'assumpte és obligatori")
    .min(3, "L'assumpte ha de tenir almenys 3 caràcters"),
  message: yup.string()
    .required('El missatge és obligatori')
    .min(10, 'El missatge ha de tenir almenys 10 caràcters')
});

// El handler del submit: només s'executa si la validació passa
const submitForm = (values, { resetForm }) => {
  // Simulació d'enviament (no hi ha backend per a contacte)
  Swal.fire({
    icon: 'success',
    title: 'Missatge enviat! ✉️',
    text: `Gràcies ${values.name}, et respondrem el més aviat possible.`,
    background: '#1a1f2e',
    color: '#ffffff',
    confirmButtonColor: '#00A1FF',
    confirmButtonText: 'Entesos'
  });

  // Netejar el formulari després de l'enviament
  resetForm();
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

/* Estil dels inputs i textareas (via .form-control de VeeValidate Field) */
.form-control {
  width: 100%;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #3A4150;
  background: #1A1D24;
  color: white;
  font-family: inherit;
  font-size: 1em;
  transition: all 0.3s;
  box-sizing: border-box;
}

.form-control:focus { 
  border-color: #00A1FF; 
  outline: none;
  box-shadow: 0 0 0 3px rgba(0, 161, 255, 0.1);
}

/* Camp amb error de validació: borde roig */
.form-control.is-invalid {
  border-color: #ff4444;
  box-shadow: 0 0 0 3px rgba(255, 68, 68, 0.1);
}

/* Missatge d'error individual per camp */
.error-feedback {
  color: #ff4444;
  font-size: 0.85em;
  margin-top: 6px;
  display: block;
}

textarea.form-control {
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

.btn-send:hover:not(:disabled) { 
  background-color: #0088d4;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 161, 255, 0.3);
}

.btn-send:disabled {
  background-color: #555;
  cursor: not-allowed;
  transform: none;
}

/* Disseny responsiu */
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
  
  textarea.form-control {
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
  
  .form-control {
    padding: 12px;
  }
  
  .btn-send {
    padding: 14px;
    font-size: 1em;
  }
}
</style>
