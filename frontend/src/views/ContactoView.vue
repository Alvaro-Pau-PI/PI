<template>
  <div class="contact-container">
    <h1>{{ t('contact.title') }}</h1>
    <p class="subtitle">{{ t('contact.subtitle') }}</p>

    <div class="contact-box">
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="name">{{ t('contact.name') }}</label>
          <input type="text" id="name" v-model="name" :class="{ 'is-invalid': nameError }" :placeholder="t('contact.name_ph')" />
          <span v-if="nameError" class="error-msg">{{ nameError }}</span>
        </div>
        <div class="form-group">
          <label for="email">{{ t('contact.email') }}</label>
          <input type="email" id="email" v-model="email" :class="{ 'is-invalid': emailError }" :placeholder="t('contact.email_ph')" />
          <span v-if="emailError" class="error-msg">{{ emailError }}</span>
        </div>
        <div class="form-group">
          <label for="subject">{{ t('contact.subject') }}</label>
          <input type="text" id="subject" v-model="subject" :class="{ 'is-invalid': subjectError }" :placeholder="t('contact.subject_ph')" />
          <span v-if="subjectError" class="error-msg">{{ subjectError }}</span>
        </div>
        <div class="form-group">
          <label for="message">{{ t('contact.message') }}</label>
          <textarea id="message" v-model="message" rows="5" :class="{ 'is-invalid': messageError }" :placeholder="t('contact.message_ph')"></textarea>
          <span v-if="messageError" class="error-msg">{{ messageError }}</span>
        </div>

        <button type="submit" class="btn-send" :disabled="isSubmitting">
          {{ isSubmitting ? t('contact.sending') : t('contact.send') }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, useField } from 'vee-validate';
import * as yup from 'yup';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const validationSchema = yup.object({
  name: yup.string()
    .required('El nom és obligatori')
    .min(3, 'El nom ha de tindre almenys 3 caràcters'),
  email: yup.string()
    .required('El correu electrònic és obligatori')
    .email('Introdueix un correu electrònic vàlid'),
  subject: yup.string()
    .required("L'assumpte és obligatori")
    .min(5, "L'assumpte ha de tindre almenys 5 caràcters"),
  message: yup.string()
    .required('El missatge és obligatori')
    .min(10, 'El missatge ha de tindre almenys 10 caràcters')
});

const { handleSubmit } = useForm({
  validationSchema,
});

const { value: name, errorMessage: nameError } = useField('name');
const { value: email, errorMessage: emailError } = useField('email');
const { value: subject, errorMessage: subjectError } = useField('subject');
const { value: message, errorMessage: messageError } = useField('message');

const isSubmitting = ref(false);

const submitForm = handleSubmit(async (values, { resetForm }) => {
  isSubmitting.value = true;
  try {
    // 1. Guardar en nuestra base de datos local a través de la API de Laravel
    const responseLocal = await fetch('/api/contacts', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(values)
    });

    if (!responseLocal.ok) throw new Error('Error al guardar en la base de datos');

    // 2. Opcional: Notificar a través de n8n (webhook)
    const baseUrlN8n = import.meta.env.VITE_N8N_BASE_URL;
    if (baseUrlN8n) {
      try {
        const webhookUrl = `${baseUrlN8n}/webhook/contact-form`; 
        await fetch(webhookUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(values)
        });
      } catch (n8nError) {
        console.warn("Fallo al notificar a n8n, pero el mensaje se guardó en la DB:", n8nError);
      }
    }

    alert(t('contact.success'));
    resetForm();
  } catch (error) {
    console.error("Error al enviar el formulario:", error);
    alert(t('contact.error'));
  } finally {
    isSubmitting.value = false;
  }
});
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

input.is-invalid, textarea.is-invalid {
  border-color: #ff4d4f;
  box-shadow: 0 0 0 3px rgba(255, 77, 79, 0.1);
}

.error-msg {
  display: block;
  color: #ff4d4f;
  font-size: 0.85em;
  margin-top: 5px;
  font-weight: 500;
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
  .contact-container { padding: 40px 20px; }
  h1 { font-size: 2.2em; }
  .contact-box { padding: 30px 25px; }
  textarea { min-height: 120px; }
}

@media (max-width: 480px) {
  .contact-container { padding: 30px 15px; }
  h1 { font-size: 1.8em; }
  .subtitle { font-size: 1em; }
  .contact-box { padding: 25px 20px; }
  input, textarea { padding: 12px; }
  .btn-send { padding: 14px; font-size: 1em; }
}
</style>