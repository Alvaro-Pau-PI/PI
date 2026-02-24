import json
import os

locales = {'es': 'src/locales/es.json', 'en': 'src/locales/en.json', 'ca': 'src/locales/ca.json'}

dicts = {
    'es': {
        'sus': {
            'title': '🌱 Compromiso con el Planeta', 'subtitle': 'Construyendo un futuro sostenible, un componente a la vez',
            'stat1': 'Productos Sostenibles', 'stat2': 'Catálogo Eco', 'stat3': 'Reacondicionados', 'stat4': 'Proveedores Locales',
            'lbl_t': '🏷️ Nuestras Etiquetas de Sostenibilidad', 'lbl_s': 'Cada producto sostenible cuenta con etiquetas claras que te ayudan a tomar decisiones informadas',
            'circ_t': '🔄 Economía Circular', 'circ_s': 'Productos reacondicionados que demuestran que calidad y sostenibilidad van de la mano',
            'load': 'Cargando productos sostenibles...', 'empty': 'No hay productos sostenibles disponibles actualmente.', 'btn': 'Ver todos los productos sostenibles →',
            'asg_t': '⚖️ Nuestros Pilares ASG',
            'obj_t': '🎯 Nuestros Objetivos 2026'
        }
    },
    'en': {
        'sus': {
            'title': '🌱 Commitment to the Planet', 'subtitle': 'Building a sustainable future, one component at a time',
            'stat1': 'Sustainable Products', 'stat2': 'Eco Catalog', 'stat3': 'Refurbished', 'stat4': 'Local Suppliers',
            'lbl_t': '🏷️ Our Sustainability Labels', 'lbl_s': 'Every sustainable product has clear labels that help you make informed decisions',
            'circ_t': '🔄 Circular Economy', 'circ_s': 'Refurbished products proving quality and sustainability go hand in hand',
            'load': 'Loading sustainable products...', 'empty': 'No sustainable products currently available.', 'btn': 'View all sustainable products →',
            'asg_t': '⚖️ Our ESG Pillars',
            'obj_t': '🎯 Our 2026 Goals'
        }
    },
    'ca': {
        'sus': {
            'title': '🌱 Compromís amb el Planeta', 'subtitle': 'Construint un futur sostenible, un component a la vegada',
            'stat1': 'Productes Sostenibles', 'stat2': 'Catàleg Eco', 'stat3': 'Recondicionats', 'stat4': 'Proveïdors Locals',
            'lbl_t': '🏷️ Les Nostres Etiquetes de Sostenibilitat', 'lbl_s': 'Cada producte sostenible compta amb etiquetes clares que t\'ajuden a prendre decisions informades',
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
    if 'sus' not in data:
        data['sus'] = {}
    data['sus'].update(dicts[lang]['sus'])
    with open(path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

print("Restored deleted sus strings!")
