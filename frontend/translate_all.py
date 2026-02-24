import json
import os
import re

locales = {'es': 'src/locales/es.json', 'en': 'src/locales/en.json', 'ca': 'src/locales/ca.json'}

dicts = {
    'es': {
        'legal': {
            'cookies_t': '🍪 Política de Cookies', 'cookies_s': 'Navegación transparente, control en tus manos',
            'privacy_t': '🛡️ Política de Privacidad', 'privacy_s': 'Protegiendo tus datos al máximo nivel.',
            'terms_t': '⚖️ Términos y Condiciones', 'terms_s': 'Condiciones de venta y uso correcto de nuestra plataforma',
        },
        'faq': { 'title': '❓ Preguntas Frecuentes (FAQ)', 'subtitle': 'Resolvemos todas tus inquietudes al instante.' },
        'guide': { 'title': '🛠️ Guía de Montaje de PCs', 'subtitle': 'Tu guía paso a paso para construir el equipo de tus sueños' },
        'sus': {
            'title': '🌱 Compromiso con el Planeta', 'subtitle': 'Construyendo un futuro sostenible, un componente a la vez',
            'stat1': 'Productos Sostenibles', 'stat2': 'Catálogo Eco', 'stat3': 'Reacondicionados', 'stat4': 'Proveedores Locales',
            'lbl_t': '🏷️ Nuestras Etiquetas de Sostenibilidad', 'lbl_s': 'Cada producto sostenible cuenta con etiquetas claras',
            'circ_t': '🔄 Economía Circular', 'circ_s': 'Productos reacondicionados que demuestran que calidad y sostenibilidad van de la mano',
            'load': 'Cargando productos sostenibles...', 'empty': 'No hay productos sostenibles disponibles actualmente.', 'btn': 'Ver todos los productos sostenibles →',
            'asg_t': '⚖️ Nuestros Pilares ASG',
            'obj_t': '🎯 Nuestros Objetivos 2026'
        }
    },
    'en': {
        'legal': {
            'cookies_t': '🍪 Cookies Policy', 'cookies_s': 'Transparent navigation, control in your hands',
            'privacy_t': '🛡️ Privacy Policy', 'privacy_s': 'Protecting your data at the highest level.',
            'terms_t': '⚖️ Terms & Conditions', 'terms_s': 'Sales conditions and correct platform usage',
        },
        'faq': { 'title': '❓ Frequently Asked Questions (FAQ)', 'subtitle': 'We resolve all your concerns instantly.' },
        'guide': { 'title': '🛠️ PC Assembly Guide', 'subtitle': 'Your step-by-step guide to building your dream machine' },
        'sus': {
            'title': '🌱 Commitment to the Planet', 'subtitle': 'Building a sustainable future, one component at a time',
            'stat1': 'Sustainable Products', 'stat2': 'Eco Catalog', 'stat3': 'Refurbished', 'stat4': 'Local Suppliers',
            'lbl_t': '🏷️ Our Sustainability Labels', 'lbl_s': 'Every sustainable product has clear labels',
            'circ_t': '🔄 Circular Economy', 'circ_s': 'Refurbished products proving quality and sustainability go hand in hand',
            'load': 'Loading sustainable products...', 'empty': 'No sustainable products currently available.', 'btn': 'View all sustainable products →',
            'asg_t': '⚖️ Our ESG Pillars',
            'obj_t': '🎯 Our 2026 Goals'
        }
    },
    'ca': {
        'legal': {
            'cookies_t': '🍪 Política de Cookies', 'cookies_s': 'Navegació transparent, control a les teues mans',
            'privacy_t': '🛡️ Política de Privacitat', 'privacy_s': 'Protegint les teues dades al màxim nivell.',
            'terms_t': '⚖️ Termes i Condicions', 'terms_s': 'Condicions de venda i ús correcte de la plataforma',
        },
        'faq': { 'title': '❓ Preguntes Freqüents (FAQ)', 'subtitle': 'Resolem totes les teues inquietuds a l\'instant.' },
        'guide': { 'title': '🛠️ Guia de Muntatge de PCs', 'subtitle': 'La teua guia pas a pas per construir l\'equip dels teus somnis' },
        'sus': {
            'title': '🌱 Compromís amb el Planeta', 'subtitle': 'Construint un futur sostenible, un component a la vegada',
            'stat1': 'Productes Sostenibles', 'stat2': 'Catàleg Eco', 'stat3': 'Recondicionats', 'stat4': 'Proveïdors Locals',
            'lbl_t': '🏷️ Les Nostres Etiquetes de Sostenibilitat', 'lbl_s': 'Cada producte sostenible compta amb etiquetes clares',
            'circ_t': '🔄 Economia Circular', 'circ_s': 'Productes recondicionats que demostren que qualitat i sostenibilitat van juntes',
            'load': 'Carregant productes sostenibles...', 'empty': 'No hi ha productes sostenibles disponibles actualment.', 'btn': 'Veure tots els productes sostenibles →',
            'asg_t': '⚖️ Els nostres Pilars ASG',
            'obj_t': '🎯 Els nostres Objectius 2026'
        }
    }
}

for lang, path in locales.items():
    if not os.path.exists(path):
        continue
    with open(path, 'r', encoding='utf-8') as f:
        data = json.load(f)
    data.update(dicts[lang])
    with open(path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

replacements = {
    'src/views/PoliticaCookiesView.vue': [
        ('🍪 Política de Cookies', "{{ $t('legal.cookies_t') }}"),
        ('Navegación transparente, control en tus manos', "{{ $t('legal.cookies_s') }}")
    ],
    'src/views/PoliticaPrivacidadView.vue': [
        ('🛡️ Política de Privacidad', "{{ $t('legal.privacy_t') }}"),
        ('Protegiendo tus datos al máximo nivel.', "{{ $t('legal.privacy_s') }}")
    ],
    'src/views/TerminosCondicionesView.vue': [
        ('⚖️ Términos y Condiciones', "{{ $t('legal.terms_t') }}"),
        ('Condiciones de venta y uso correcto de nuestra plataforma', "{{ $t('legal.terms_s') }}")
    ],
    'src/views/PreguntasFrecuentesView.vue': [
        ('❓ Preguntas Frecuentes (FAQ)', "{{ $t('faq.title') }}"),
        ('Resolvemos todas tus inquietudes al instante.', "{{ $t('faq.subtitle') }}")
    ],
    'src/views/GuiaMontajeView.vue': [
        ('🛠️ Guía de Montaje de PCs', "{{ $t('guide.title') }}"),
        ('Tu guía paso a paso para construir el equipo de tus sueños', "{{ $t('guide.subtitle') }}")
    ],
    'src/views/SostenibilidadView.vue': [
        ('🌱 Compromiso con el Planeta', "{{ $t('sus.title') }}"),
        ('Construyendo un futuro sostenible, un componente a la vez', "{{ $t('sus.subtitle') }}"),
        ('Productos Sostenibles<', "{{ $t('sus.stat1') }}<"),
        ('Catálogo Eco<', "{{ $t('sus.stat2') }}<"),
        ('Reacondicionados<', "{{ $t('sus.stat3') }}<"),
        ('Proveedores Locales<', "{{ $t('sus.stat4') }}<"),
        ('🏷️ Nuestras Etiquetas de Sostenibilidad', "{{ $t('sus.lbl_t') }}"),
        ('Cada producto sostenible cuenta con etiquetas claras que te ayudan a tomar decisiones informadas', "{{ $t('sus.lbl_s') }}"),
        ('🔄 Economía Circular', "{{ $t('sus.circ_t') }}"),
        ('Productos reacondicionados que demuestran que calidad y sostenibilidad van de la mano', "{{ $t('sus.circ_s') }}"),
        ('Cargando productos sostenibles...', "{{ $t('sus.load') }}"),
        ('No hay productos sostenibles disponibles actualmente.', "{{ $t('sus.empty') }}"),
        ('Ver todos los productos sostenibles →', "{{ $t('sus.btn') }}"),
        ('⚖️ Nuestros Pilares ASG', "{{ $t('sus.asg_t') }}"),
        ('🎯 Nuestros Objetivos 2026', "{{ $t('sus.obj_t') }}")
    ]
}

for path, reps in replacements.items():
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        for old, new in reps:
            content = content.replace(old, new)
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)

print("Remaining pages fully translated.")
