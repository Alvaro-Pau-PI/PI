import json
import os

locales = {
  'es': 'src/locales/es.json',
  'en': 'src/locales/en.json',
  'ca': 'src/locales/ca.json'
}

new_data = {
  'es': {
    'contact': {
      'title': 'Contacta con Nosotros',
      'subtitle': '¿Tienes dudas o quieres pedir presupuesto? ¡Escríbenos!',
      'name': 'Nombre',
      'name_ph': 'Tu nombre',
      'email': 'Correo Electrónico',
      'email_ph': 'tucorreo@ejemplo.com',
      'subject': 'Asunto',
      'subject_ph': 'Asunto del mensaje',
      'message': 'Mensaje',
      'message_ph': 'Escribe aquí tu mensaje...',
      'sending': 'Enviando...',
      'send': 'Enviar Mensaje',
      'success': '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo pronto.',
      'error': 'Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.'
    },
    'products': {
      'title': 'Catálogo de Productos',
      'hide_filters': 'Ocultar Filtros',
      'show_filters': 'Mostrar Filtros',
      'search': 'Búsqueda',
      'search_ph': 'Nombre del producto...',
      'category': 'Categoría',
      'all': 'Todas',
      'price': 'Precio',
      'min': 'Min',
      'max': 'Max',
      'sustainability': '🌱 Sostenibilidad',
      'eco': 'Solo productos eco (Score ≥ 70)',
      'refurbished': '♻️ Solo reacondicionados',
      'local': '🏠 Solo proveedores locales',
      'clear_filters': 'Limpiar Filtros',
      'no_results': 'No se han encontrado productos con estos filtros.',
      'showing': 'Mostrando',
      'of': 'de',
      'products_lower': 'productos',
      'loading': 'Cargando...',
      'load_more': 'Ver más productos'
    },
    'cart': {
      'title': 'Mi carrito',
      'empty': 'Tu carrito está vacío.',
      'explore': 'Explorar Productos',
      'summary': 'Resumen del pedido',
      'subtotal': 'Subtotal',
      'tax': 'IVA (21%)',
      'total': 'Total',
      'checkout': 'Tramitar Pedido',
      'processing': 'Procesando...'
    }
  },
  'en': {
    'contact': {
      'title': 'Contact Us',
      'subtitle': 'Have questions or want a quote? Write to us!',
      'name': 'Name',
      'name_ph': 'Your name',
      'email': 'Email',
      'email_ph': 'youremail@example.com',
      'subject': 'Subject',
      'subject_ph': 'Message subject',
      'message': 'Message',
      'message_ph': 'Write your message here...',
      'sending': 'Sending...',
      'send': 'Send Message',
      'success': 'Message sent successfully! We will contact you soon.',
      'error': 'There was an error sending the message. Please try again.'
    },
    'products': {
      'title': 'Product Catalog',
      'hide_filters': 'Hide Filters',
      'show_filters': 'Show Filters',
      'search': 'Search',
      'search_ph': 'Product name...',
      'category': 'Category',
      'all': 'All',
      'price': 'Price',
      'min': 'Min',
      'max': 'Max',
      'sustainability': '🌱 Sustainability',
      'eco': 'Eco products only (Score ≥ 70)',
      'refurbished': '♻️ Refurbished only',
      'local': '🏠 Local suppliers only',
      'clear_filters': 'Clear Filters',
      'no_results': 'No products found with these filters.',
      'showing': 'Showing',
      'of': 'of',
      'products_lower': 'products',
      'loading': 'Loading...',
      'load_more': 'Load more products'
    },
    'cart': {
      'title': 'My Cart',
      'empty': 'Your cart is empty.',
      'explore': 'Explore Products',
      'summary': 'Order Summary',
      'subtotal': 'Subtotal',
      'tax': 'Tax (21%)',
      'total': 'Total',
      'checkout': 'Checkout',
      'processing': 'Processing...'
    }
  },
  'ca': {
    'contact': {
      'title': 'Contacta amb Nosaltres',
      'subtitle': 'Tens dubtes o vols demanar pressupost? Escriu-nos!',
      'name': 'Nom',
      'name_ph': 'El teu nom',
      'email': 'Correu Electrònic',
      'email_ph': 'tucorreu@exemple.com',
      'subject': 'Assumpte',
      'subject_ph': 'Assumpte del missatge',
      'message': 'Missatge',
      'message_ph': 'Escriu aquí el teu missatge...',
      'sending': 'Enviant...',
      'send': 'Enviar Missatge',
      'success': 'Missatge enviat correctament! Ens posarem en contacte amb tu prompte.',
      'error': 'Hi ha hagut un error en enviar el missatge. Per favor, torna-ho a provar.'
    },
    'products': {
      'title': 'Catàleg de Productes',
      'hide_filters': 'Ocultar Filtres',
      'show_filters': 'Mostrar Filtres',
      'search': 'Cerca',
      'search_ph': 'Nom del producte...',
      'category': 'Categoria',
      'all': 'Totes',
      'price': 'Preu',
      'min': 'Min',
      'max': 'Max',
      'sustainability': '🌱 Sostenibilitat',
      'eco': 'Només productes eco (Score ≥ 70)',
      'refurbished': '♻️ Només reacondicionats',
      'local': '🏠 Només proveïdors locals',
      'clear_filters': 'Netejar Filtres',
      'no_results': 'No s\'han trobat productes amb aquests filtres.',
      'showing': 'Mostrant',
      'of': 'de',
      'products_lower': 'productes',
      'loading': 'Carregant...',
      'load_more': 'Veure més productes'
    },
    'cart': {
      'title': 'El meu carret',
      'empty': 'El teu carret està buit.',
      'explore': 'Explorar Productes',
      'summary': 'Resum de la comanda',
      'subtotal': 'Subtotal',
      'tax': 'IVA (21%)',
      'total': 'Total',
      'checkout': 'Tramitar Comanda',
      'processing': 'Processant...'
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
    data.update(new_data[lang])
    with open(path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

print("Locales updated successfully!")
