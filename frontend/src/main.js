import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
// Sistema CSS modular y accesible (DIW)
import './assets/css/main.css'

// Backup del sistema anterior (comentado, no eliminar)
// import './assets/styles.css'

import i18n from './i18n'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(i18n)

app.mount('#app')
