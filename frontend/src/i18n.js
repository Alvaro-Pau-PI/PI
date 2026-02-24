import { createI18n } from 'vue-i18n'
import es from './locales/es.json'
import en from './locales/en.json'
import ca from './locales/ca.json'

const i18n = createI18n({
    legacy: false, // Use Composition API
    locale: localStorage.getItem('lang') || 'es', // Default to Spanish
    fallbackLocale: 'es',
    messages: {
        es,
        en,
        ca
    }
})

export default i18n
