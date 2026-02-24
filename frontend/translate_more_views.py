import os
import json

locales = {
  'es': 'src/locales/es.json',
  'en': 'src/locales/en.json',
  'ca': 'src/locales/ca.json'
}

new_dicts = {
    'es': {
        'profile': {
            'title': 'Mi Perfil',
            'personal_info': 'Información Personal',
            'name': 'Nombre',
            'email': 'Email',
            'phone': 'Teléfono',
            'save': 'Guardar Cambios',
            'saving': 'Guardando...'
        },
        'orders': {
            'title': 'Mis Pedidos',
            'no_orders': 'No tienes pedidos todavía.',
            'order_num': 'Pedido #',
            'date': 'Fecha',
            'total': 'Total',
            'status': 'Estado'
        },
        'favorites': {
            'title': 'Mis Favoritos',
            'empty': 'No tienes productos favoritos todavía.',
            'explore': 'Explorar Productos'
        },
        'payment': {
            'title': 'Proceso de Pago',
            'checkout': 'Finalizar Compra',
            'card': 'Tarjeta de Crédito',
            'pay': 'Pagar',
            'processing': 'Procesando...'
        }
    },
    'en': {
        'profile': {
            'title': 'My Profile',
            'personal_info': 'Personal Information',
            'name': 'Name',
            'email': 'Email',
            'phone': 'Phone',
            'save': 'Save Changes',
            'saving': 'Saving...'
        },
        'orders': {
            'title': 'My Orders',
            'no_orders': 'You do not have any orders yet.',
            'order_num': 'Order #',
            'date': 'Date',
            'total': 'Total',
            'status': 'Status'
        },
        'favorites': {
            'title': 'My Favorites',
            'empty': 'You have no favorite products yet.',
            'explore': 'Explore Products'
        },
        'payment': {
            'title': 'Checkout Process',
            'checkout': 'Complete Purchase',
            'card': 'Credit Card',
            'pay': 'Pay',
            'processing': 'Processing...'
        }
    },
    'ca': {
        'profile': {
            'title': 'El meu perfil',
            'personal_info': 'Informació Personal',
            'name': 'Nom',
            'email': 'Correu',
            'phone': 'Telèfon',
            'save': 'Guardar Canvis',
            'saving': 'Guardant...'
        },
        'orders': {
            'title': 'Les meves comandes',
            'no_orders': 'Encara no tens cap comanda.',
            'order_num': 'Comanda #',
            'date': 'Data',
            'total': 'Total',
            'status': 'Estat'
        },
        'favorites': {
            'title': 'Els meus favorits',
            'empty': 'Encara no tens productes favorits.',
            'explore': 'Explorar Productes'
        },
        'payment': {
            'title': 'Procés de Pagament',
            'checkout': 'Finalitzar Compra',
            'card': 'Targeta de Crèdit',
            'pay': 'Pagar',
            'processing': 'Processant...'
        }
    }
}

file_modifications = {
    "src/views/PerfilView.vue": [
        ("<h1>Mi Perfil</h1>", "<h1>{{ $t('profile.title') }}</h1>"),
        ("<h3>Información Personal</h3>", "<h3>{{ $t('profile.personal_info') }}</h3>"),
        ("<label>Nombre</label>", "<label>{{ $t('profile.name') }}</label>"),
        ("<label>Email</label>", "<label>{{ $t('profile.email') }}</label>"),
        ("<label>Teléfono</label>", "<label>{{ $t('profile.phone') }}</label>"),
        ("{{ isSaving ? 'Guardando...' : 'Guardar Cambios' }}", "{{ isSaving ? $t('profile.saving') : $t('profile.save') }}")
    ],
    "src/views/PedidosView.vue": [
        ("<h2>Mis Pedidos</h2>", "<h2>{{ $t('orders.title') }}</h2>"),
        ("<p>No tienes pedidos todavía.</p>", "<p>{{ $t('orders.no_orders') }}</p>"),
        ("Pedido #", "{{ $t('orders.order_num') }}")
    ],
    "src/views/FavoritosView.vue": [
        ("<h2>Mis Favoritos</h2>", "<h2>{{ $t('favorites.title') }}</h2>"),
        ("<p>No tienes productos en favoritos todavía.</p>", "<p>{{ $t('favorites.empty') }}</p>"),
        ("Explorar Productos", "{{ $t('favorites.explore') }}")
    ],
    "src/views/PagoView.vue": [
        ("Finalizar Compra", "{{ $t('payment.checkout') }}"),
        ("Procesando...", "{{ $t('payment.processing') }}"),
        ("Pagar ", "{{ $t('payment.pay') }} ")
    ]
}

def update_locales():
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

def update_files():
    for fpath, replacements in file_modifications.items():
        if not os.path.exists(fpath):
            continue
        with open(fpath, 'r', encoding='utf-8') as f:
            content = f.read()
        for old, new in replacements:
            if old in content:
                content = content.replace(old, new)
        with open(fpath, 'w', encoding='utf-8') as f:
            f.write(content)

update_locales()
update_files()
print("Remaining pages updated")
