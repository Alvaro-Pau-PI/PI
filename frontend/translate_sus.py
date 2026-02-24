import json
import os

locales = {'es': 'src/locales/es.json', 'en': 'src/locales/en.json', 'ca': 'src/locales/ca.json'}

dicts = {
    'es': {
        'sus': {
            'lvl1': 'Eco Score Excelente', 'lvl1d': 'Productos con puntuación ecológica de 80 o superior. Máxima eficiencia y mínimo impacto ambiental.',
            'lvl2': 'Producto Reacondicionado', 'lvl2d': 'Segunda vida para componentes de calidad. Ahorro de recursos y reducción de residuos electrónicos.',
            'lvl3': 'Embalaje Reciclado', 'lvl3d': 'Embalaje 100% reciclado y reciclable. Menos plástico, más sostenibilidad.',
            'lvl4': 'Proveedor Local', 'lvl4d': 'Distribuido localmente. Menor huella de carbono en transporte y apoyo a la economía local.',
            'lvl5': 'Materiales Reciclables', 'lvl5d': 'Fabricado con materiales reciclables. Diseñado para facilitar su reciclaje al final de su vida útil.',
            'lvl6': 'Huella de Carbono Reducida', 'lvl6d': 'Menos de 5 kg de CO₂ en su ciclo de vida. Comprometidos con la reducción de emisiones.',
            'p1': 'Ambiental', 'p1_1': 'Optimización de recursos web (WebP, lazy loading)', 'p1_2': 'Promoción de productos reacondicionados', 'p1_3': 'Medición de huella de carbono', 'p1_4': 'Embalajes reciclados y reciclables', 'p1_5': 'Reducción de peso de página en 40%+',
            'p2': 'Social', 'p2_1': 'Accesibilidad WCAG AA completa', 'p2_2': 'Información clara y transparente', 'p2_3': 'UX inclusiva sin barreras', 'p2_4': 'Navegación por teclado optimizada', 'p2_5': 'Contraste de colores validado',
            'p3': 'Gobernanza', 'p3_1': 'Código documentado y trazable', 'p3_2': 'Criterios eco verificables', 'p3_3': 'Políticas publicadas y transparentes', 'p3_4': 'Mejora continua documentada', 'p3_5': 'Métricas de sostenibilidad públicas',
            'obj1': 'del catálogo con etiqueta eco', 'obj2': 'proveedores con certificación ambiental', 'obj3': 'reducción de huella de carbono web', 'obj4': 'score Lighthouse Accessibility'
        }
    },
    'en': {
        'sus': {
            'lvl1': 'Excellent Eco Score', 'lvl1d': 'Products with an eco-score of 80 or higher. Maximum efficiency and minimum environmental impact.',
            'lvl2': 'Refurbished Product', 'lvl2d': 'Second life for quality components. Resource savings and electronic waste reduction.',
            'lvl3': 'Recycled Packaging', 'lvl3d': '100% recycled and recyclable packaging. Less plastic, more sustainability.',
            'lvl4': 'Local Supplier', 'lvl4d': 'Locally distributed. Lower carbon footprint in transportation and support for local economy.',
            'lvl5': 'Recyclable Materials', 'lvl5d': 'Made with recyclable materials. Designed to facilitate recycling at the end of its life cycle.',
            'lvl6': 'Reduced Carbon Footprint', 'lvl6d': 'Less than 5 kg of CO₂ in its life cycle. Committed to reducing emissions.',
            'p1': 'Environmental', 'p1_1': 'Web resource optimization (WebP, lazy loading)', 'p1_2': 'Promotion of refurbished products', 'p1_3': 'Carbon footprint measurement', 'p1_4': 'Recycled and recyclable packaging', 'p1_5': '40%+ page weight reduction',
            'p2': 'Social', 'p2_1': 'Full WCAG AA accessibility', 'p2_2': 'Clear and transparent information', 'p2_3': 'Barrier-free inclusive UX', 'p2_4': 'Optimized keyboard navigation', 'p2_5': 'Validated color contrast',
            'p3': 'Governance', 'p3_1': 'Documented and traceable code', 'p3_2': 'Verifiable eco criteria', 'p3_3': 'Published and transparent policies', 'p3_4': 'Documented continuous improvement', 'p3_5': 'Public sustainability metrics',
            'obj1': 'of catalog with eco label', 'obj2': 'suppliers with environmental certification', 'obj3': 'web carbon footprint reduction', 'obj4': 'Lighthouse Accessibility score'  
        }
    },
    'ca': {
        'sus': {
            'lvl1': 'Eco Score Excel·lent', 'lvl1d': 'Productes amb puntuació ecològica de 80 o superior. Màxima eficiència i mínim impacte ambiental.',
            'lvl2': 'Producte Recondicionat', 'lvl2d': 'Segona vida per a components de qualitat. Estalvi de recursos i reducció de residus electrònics.',
            'lvl3': 'Embalatge Reciclat', 'lvl3d': 'Embalatge 100% reciclat i reciclable. Menys plàstic, més sostenibilitat.',
            'lvl4': 'Proveïdor Local', 'lvl4d': 'Distribuït localment. Menor petjada de carboni en transport i suport a l\'economia local.',
            'lvl5': 'Materials Reciclables', 'lvl5d': 'Fabricat amb materials reciclables. Dissenyat per facilitar el seu reciclatge al final de la seua vida útil.',
            'lvl6': 'Petjada de Carboni Reduïda', 'lvl6d': 'Menys de 5 kg de CO₂ en el seu cicle de vida. Compromesos amb la reducció d\'emissions.',
            'p1': 'Ambiental', 'p1_1': 'Optimització de recursos web (WebP, lazy loading)', 'p1_2': 'Promoció de productes recondicionats', 'p1_3': 'Mesurament de petjada de carboni', 'p1_4': 'Embalatges reciclats i reciclables', 'p1_5': 'Reducció de pes de pàgina en 40%+',
            'p2': 'Social', 'p2_1': 'Accessibilitat WCAG AA completa', 'p2_2': 'Informació clara i transparent', 'p2_3': 'UX inclusiva sense barreres', 'p2_4': 'Navegació per teclat optimitzada', 'p2_5': 'Contrast de colors validat',
            'p3': 'Governança', 'p3_1': 'Codi documentat i traçable', 'p3_2': 'Criteris eco verificables', 'p3_3': 'Polítiques publicades i transparents', 'p3_4': 'Millora contínua documentada', 'p3_5': 'Mètriques de sostenibilitat públiques',
            'obj1': 'del catàleg amb etiqueta eco', 'obj2': 'proveïdors amb certificació ambiental', 'obj3': 'reducció de petjada de carboni web', 'obj4': 'score Lighthouse Accessibility'
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
    'src/views/SostenibilidadView.vue': [
        ('Eco Score Excelente', "{{ $t('sus.lvl1') }}"), ('Productos con puntuación ecológica de 80 o superior. Máxima eficiencia y mínimo impacto ambiental.', "{{ $t('sus.lvl1d') }}"),
        ('Producto Reacondicionado', "{{ $t('sus.lvl2') }}"), ('Segunda vida para componentes de calidad. Ahorro de recursos y reducción de residuos electrónicos.', "{{ $t('sus.lvl2d') }}"),
        ('Embalaje Reciclado', "{{ $t('sus.lvl3') }}"), ('Embalaje 100% reciclado y reciclable. Menos plástico, más sostenibilidad.', "{{ $t('sus.lvl3d') }}"),
        ('Proveedor Local', "{{ $t('sus.lvl4') }}"), ('Distribuido localmente. Menor huella de carbono en transporte y apoyo a la economía local.', "{{ $t('sus.lvl4d') }}"),
        ('Materiales Reciclables', "{{ $t('sus.lvl5') }}"), ('Fabricado con materiales reciclables. Diseñado para facilitar su reciclaje al final de su vida útil.', "{{ $t('sus.lvl5d') }}"),
        ('Huella de Carbono Reducida', "{{ $t('sus.lvl6') }}"), ('Menos de 5 kg de CO₂ en su ciclo de vida. Comprometidos con la reducción de emisiones.', "{{ $t('sus.lvl6d') }}"),
        ('Ambiental', "{{ $t('sus.p1') }}"), ('Optimización de recursos web (WebP, lazy loading)', "{{ $t('sus.p1_1') }}"), ('Promoción de productos reacondicionados', "{{ $t('sus.p1_2') }}"), ('Medición de huella de carbono', "{{ $t('sus.p1_3') }}"), ('Embalajes reciclados y reciclables', "{{ $t('sus.p1_4') }}"), ('Reducción de peso de página en 40%+', "{{ $t('sus.p1_5') }}"),
        ('Social', "{{ $t('sus.p2') }}"), ('Accesibilidad WCAG AA completa', "{{ $t('sus.p2_1') }}"), ('Información clara y transparente', "{{ $t('sus.p2_2') }}"), ('UX inclusiva sin barreras', "{{ $t('sus.p2_3') }}"), ('Navegación por teclado optimizada', "{{ $t('sus.p2_4') }}"), ('Contraste de colores validado', "{{ $t('sus.p2_5') }}"),
        ('Gobernanza', "{{ $t('sus.p3') }}"), ('Código documentado y trazable', "{{ $t('sus.p3_1') }}"), ('Criterios eco verificables', "{{ $t('sus.p3_2') }}"), ('Políticas publicadas y transparentes', "{{ $t('sus.p3_3') }}"), ('Mejora continua documentada', "{{ $t('sus.p3_4') }}"), ('Métricas de sostenibilidad públicas', "{{ $t('sus.p3_5') }}"),
        ('del catálogo con etiqueta eco', "{{ $t('sus.obj1') }}"), ('proveedores con certificación ambiental', "{{ $t('sus.obj2') }}"), ('reducción de huella de carbono web', "{{ $t('sus.obj3') }}"), ('score Lighthouse Accessibility', "{{ $t('sus.obj4') }}")
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

print("Remaining sustainability strings translated.")
