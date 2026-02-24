import json
import os

locales = {
  'es': 'src/locales/es.json',
  'en': 'src/locales/en.json',
  'ca': 'src/locales/ca.json'
}

new_dicts = {
    'es': {
        'footer': {
            'tagline': 'Tu tienda de informática y componentes de confianza.',
            'subscribe_title': '¡Suscríbete!',
            'subscribe_desc': 'Recibe las mejores ofertas y novedades.',
            'email_ph': 'Escribe tu email aquí',
            'subscribe_btn': 'Suscribirse',
            'links_title': 'Enlaces Útiles',
            'contact': 'Contacto',
            'sustainability': '🌱 Sostenibilidad',
            'guide': 'Guía de montaje de PCs',
            'faq': 'FAQ',
            'legal_title': 'Legal',
            'privacy': 'Política de Privacidad',
            'terms': 'Términos y Condiciones',
            'cookies': 'Política de Cookies',
            'rights': 'Todos los derechos reservados.'
        }
    },
    'en': {
        'footer': {
            'tagline': 'Your trusted store for computing and components.',
            'subscribe_title': 'Subscribe!',
            'subscribe_desc': 'Receive the best offers and news.',
            'email_ph': 'Enter your email here',
            'subscribe_btn': 'Subscribe',
            'links_title': 'Useful Links',
            'contact': 'Contact',
            'sustainability': '🌱 Sustainability',
            'guide': 'PC Assembly Guide',
            'faq': 'FAQ',
            'legal_title': 'Legal',
            'privacy': 'Privacy Policy',
            'terms': 'Terms and Conditions',
            'cookies': 'Cookies Policy',
            'rights': 'All rights reserved.'
        }
    },
    'ca': {
        'footer': {
            'tagline': 'La teua botiga d\'informàtica i components de confiança.',
            'subscribe_title': 'Subscriu-te!',
            'subscribe_desc': 'Rep les millors ofertes i novetats.',
            'email_ph': 'Escriu el teu email aquí',
            'subscribe_btn': 'Subscriure\'s',
            'links_title': 'Enllaços Útils',
            'contact': 'Contacte',
            'sustainability': '🌱 Sostenibilitat',
            'guide': 'Guia de muntatge de PCs',
            'faq': 'FAQ',
            'legal_title': 'Legal',
            'privacy': 'Política de Privacitat',
            'terms': 'Termes i Condicions',
            'cookies': 'Política de Cookies',
            'rights': 'Tots els drets reservats.'
        }
    }
}

for lang, path in locales.items():
    if not os.path.exists(path):
        continue
    with open(path, 'r', encoding='utf-8') as f:
        try:
            data = json.load(f)
        except:
            data = {}
    data.update(new_dicts[lang])
    with open(path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

print("Footer translations injected!")
