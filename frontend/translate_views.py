import os
import json

locales = {
  'es': 'src/locales/es.json',
  'en': 'src/locales/en.json',
  'ca': 'src/locales/ca.json'
}

new_dicts = {
    'es': {
        'login': {
            'title': 'Iniciar Sesión',
            'email': 'Email',
            'email_ph': 'nombre@gmail.com',
            'password': 'Contraseña',
            'password_ph': '........',
            'remember': 'Recuérdame',
            'enter': 'Entrar',
            'or': 'o continúa con',
            'google': 'Iniciar sesión con Google',
            'no_account': '¿No tienes cuenta?',
            'register_here': 'Regístrate aquí',
            'back': 'Volver a la tienda'
        },
        'register': {
            'title': 'Crear Cuenta',
            'name': 'Nombre',
            'name_ph': 'Tu nombre',
            'email': 'Email',
            'email_ph': 'tucorreo@ejemplo.com',
            'password': 'Contraseña',
            'password_ph': 'Mínimo 8 caracteres',
            'confirm_password': 'Confirmar Contraseña',
            'confirm_ph': 'Repite la contraseña',
            'creating': 'Creando cuenta...',
            'register': 'Regístrate',
            'have_account': '¿Ya tienes cuenta?',
            'login_here': 'Inicia Sesión',
            'back': 'Volver a la tienda'
        },
        'detail': {
            'home': 'Inicio',
            'products': 'Productos',
            'ref': 'REF',
            'ratings': 'valoraciones',
            'rating_single': 'valoración',
            'in_stock': 'En Stock',
            'out_stock': 'Agotado',
            'desc_title': 'Descripción del producto',
            'add_cart': 'Añadir al Carrito',
            'free_shipping': 'Envío gratuito +50€',
            'warranty': 'Garantía de 2 años',
            'return': 'Devolución rápida en 30 días',
            'eco_commitment': 'Compromiso Sostenible',
            'refurbished': 'Reacondicionado',
            'eco_pack': 'Embalaje Reciclable',
            'local_sup': 'Proveedor Local',
            'carbon': 'Huella de carbono',
            'reviews_title': 'Valoraciones',
            'write_review': 'Escribir Valoración',
            'already_reviewed': 'Ya has valorado',
            'no_reviews': 'Todavía no hay valoraciones',
            'be_first': '¡Sé el primero en compartir tu experiencia con este producto!',
            'write_first': 'Escribir la primera valoración',
            'back': 'Volver al catálogo',
            'not_found': 'Producto no encontrado'
        }
    },
    'en': {
        'login': {
            'title': 'Log In',
            'email': 'Email',
            'email_ph': 'name@gmail.com',
            'password': 'Password',
            'password_ph': '........',
            'remember': 'Remember Me',
            'enter': 'Log In',
            'or': 'or continue with',
            'google': 'Log in with Google',
            'no_account': "Don't have an account?",
            'register_here': 'Register here',
            'back': 'Back to store'
        },
        'register': {
            'title': 'Create Account',
            'name': 'Name',
            'name_ph': 'Your name',
            'email': 'Email',
            'email_ph': 'youremail@example.com',
            'password': 'Password',
            'password_ph': 'Minimum 8 characters',
            'confirm_password': 'Confirm Password',
            'confirm_ph': 'Repeat password',
            'creating': 'Creating account...',
            'register': 'Sign Up',
            'have_account': 'Already have an account?',
            'login_here': 'Log In',
            'back': 'Back to store'
        },
        'detail': {
            'home': 'Home',
            'products': 'Products',
            'ref': 'REF',
            'ratings': 'ratings',
            'rating_single': 'rating',
            'in_stock': 'In Stock',
            'out_stock': 'Out of Stock',
            'desc_title': 'Product Description',
            'add_cart': 'Add to Cart',
            'free_shipping': 'Free shipping +50€',
            'warranty': '2-year Warranty',
            'return': 'Quick return within 30 days',
            'eco_commitment': 'Sustainable Commitment',
            'refurbished': 'Refurbished',
            'eco_pack': 'Recyclable Packaging',
            'local_sup': 'Local Supplier',
            'carbon': 'Carbon Footprint',
            'reviews_title': 'Reviews',
            'write_review': 'Write a Review',
            'already_reviewed': 'You have already reviewed',
            'no_reviews': 'No reviews yet',
            'be_first': 'Be the first to share your experience!',
            'write_first': 'Write the first review',
            'back': 'Back to catalog',
            'not_found': 'Product not found'
        }
    },
    'ca': {
        'login': {
            'title': 'Iniciar Sessió',
            'email': 'Correu',
            'email_ph': 'nom@gmail.com',
            'password': 'Contrasenya',
            'password_ph': '........',
            'remember': 'Recorda\'m',
            'enter': 'Entrar',
            'or': 'o continua amb',
            'google': 'Inicia sessió amb Google',
            'no_account': 'No tens compte?',
            'register_here': 'Registra\'t aquí',
            'back': 'Tornar a la botiga'
        },
        'register': {
            'title': 'Crear Compte',
            'name': 'Nom',
            'name_ph': 'El teu nom',
            'email': 'Correu',
            'email_ph': 'elcorreu@exemple.com',
            'password': 'Contrasenya',
            'password_ph': 'Mínim 8 caràcters',
            'confirm_password': 'Confirmar Contrasenya',
            'confirm_ph': 'Repeteix la contrasenya',
            'creating': 'Creant compte...',
            'register': 'Registra\'t',
            'have_account': 'Ja tens compte?',
            'login_here': 'Inicia Sessió',
            'back': 'Tornar a la botiga'
        },
        'detail': {
            'home': 'Inici',
            'products': 'Productes',
            'ref': 'REF',
            'ratings': 'valoracions',
            'rating_single': 'valoració',
            'in_stock': 'En Estoc',
            'out_stock': 'Esgotat',
            'desc_title': 'Descripció del producte',
            'add_cart': 'Afegir al Carret',
            'free_shipping': 'Enviament gratuït +50€',
            'warranty': 'Garantia de 2 anys',
            'return': 'Devolució ràpida en 30 dies',
            'eco_commitment': 'Compromís Sostenible',
            'refurbished': 'Reacondicionat',
            'eco_pack': 'Embalatge Reciclable',
            'local_sup': 'Proveïdor Local',
            'carbon': 'Petjada de carboni',
            'reviews_title': 'Valoracions',
            'write_review': 'Escriure Valoració',
            'already_reviewed': 'Ja has valorat',
            'no_reviews': 'Encara no hi ha valoracions',
            'be_first': 'Sigues el primer en compartir la teva experiència!',
            'write_first': 'Escriure la primera valoració',
            'back': 'Tornar al catàleg',
            'not_found': 'Producte no trobat'
        }
    }
}

file_modifications = {
    "src/views/AccesoView.vue": [
        ("<h2>Iniciar Sesión</h2>", "<h2>{{ $t('login.title') }}</h2>"),
        ("<label for=\"email\">Email</label>", "<label for=\"email\">{{ $t('login.email') }}</label>"),
        ("placeholder=\"nombre@gmail.com\"", ":placeholder=\"$t('login.email_ph')\""),
        ("<label for=\"password\">Contraseña</label>", "<label for=\"password\">{{ $t('login.password') }}</label>"),
        ("placeholder=\"........\"", ":placeholder=\"$t('login.password_ph')\""),
        ("<span>Recuérdame</span>", "<span>{{ $t('login.remember') }}</span>"),
        ("{{ (isSubmitting || authStore.loading) ? '' : 'Entrar' }}", "{{ (isSubmitting || authStore.loading) ? '' : $t('login.enter') }}"),
        ("<span>o continúa con</span>", "<span>{{ $t('login.or') }}</span>"),
        ("<span>Iniciar sesión con Google</span>", "<span>{{ $t('login.google') }}</span>"),
        ("<span class=\"text-muted\">¿No tienes cuenta?</span>", "<span class=\"text-muted\">{{ $t('login.no_account') }}</span>"),
        (">Regístrate aquí<", ">{{ $t('login.register_here') }}<"),
        ("Volver a la tienda", "{{ $t('login.back') }}")
    ],
    "src/views/RegistroView.vue": [
        ("<h2>Crear Cuenta</h2>", "<h2>{{ $t('register.title') }}</h2>"),
        ("<label for=\"name\">Nombre</label>", "<label for=\"name\">{{ $t('register.name') }}</label>"),
        ("placeholder=\"Tu nombre\"", ":placeholder=\"$t('register.name_ph')\""),
        ("<label for=\"email\">Email</label>", "<label for=\"email\">{{ $t('register.email') }}</label>"),
        ("placeholder=\"tucorreo@ejemplo.com\"", ":placeholder=\"$t('register.email_ph')\""),
        ("<label for=\"password\">Contraseña</label>", "<label for=\"password\">{{ $t('register.password') }}</label>"),
        ("placeholder=\"Mínimo 8 caracteres\"", ":placeholder=\"$t('register.password_ph')\""),
        ("<label for=\"password_confirmation\">Confirmar Contraseña</label>", "<label for=\"password_confirmation\">{{ $t('register.confirm_password') }}</label>"),
        ("placeholder=\"Repite la contraseña\"", ":placeholder=\"$t('register.confirm_ph')\""),
        ("{{ authStore.loading ? 'Creando cuenta...' : \"Regístrate\" }}", "{{ authStore.loading ? $t('register.creating') : $t('register.register') }}"),
        ("¿Ya tienes cuenta?", "{{ $t('register.have_account') }}"),
        (">Inicia Sesión<", ">{{ $t('register.login_here') }}<"),
        ("Volver a la tienda", "{{ $t('register.back') }}")
    ],
    "src/views/ProductoDetalleView.vue": [
        (">Inicio<", ">{{ $t('detail.home') }}<"),
        (">Productos<", ">{{ $t('detail.products') }}<"),
        ("REF:", "{{ $t('detail.ref') }}:"),
        ("{{ product.reviews.length === 1 ? 'valoración' : 'valoraciones' }}", "{{ product.reviews.length === 1 ? $t('detail.rating_single') : $t('detail.ratings') }}"),
        ("valoraciones)", "{{ $t('detail.ratings') }})"),
        ("En Stock", "{{ $t('detail.in_stock') }}"),
        ("Agotado", "{{ $t('detail.out_stock') }}"),
        ("Descripción del producto", "{{ $t('detail.desc_title') }}"),
        ("Añadir al Carrito", "{{ $t('detail.add_cart') }}"),
        ("Envío gratuito +50€", "{{ $t('detail.free_shipping') }}"),
        ("Garantía de 2 años", "{{ $t('detail.warranty') }}"),
        ("Devolución rápida en 30 días", "{{ $t('detail.return') }}"),
        ("Compromiso Sostenible", "{{ $t('detail.eco_commitment') }}"),
        (">Reacondicionado<", ">{{ $t('detail.refurbished') }}<"),
        (">Embalaje Reciclable<", ">{{ $t('detail.eco_pack') }}<"),
        (">Proveedor Local<", ">{{ $t('detail.local_sup') }}<"),
        ("Huella de carbono:", "{{ $t('detail.carbon') }}:"),
        ("Valoraciones", "{{ $t('detail.reviews_title') }}"),
        ("Escribir Valoración", "{{ $t('detail.write_review') }}"),
        ("Ya has valorado", "{{ $t('detail.already_reviewed') }}"),
        ("Todavía no hay valoraciones", "{{ $t('detail.no_reviews') }}"),
        ("¡Sé el primero en compartir tu experiencia con este producto!", "{{ $t('detail.be_first') }}"),
        ("Escribir la primera valoración", "{{ $t('detail.write_first') }}"),
        ("Volver al catálogo", "{{ $t('detail.back') }}"),
        ("Producto no encontrado", "{{ $t('detail.not_found') }}")
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
            content = content.replace(old, new)
        with open(fpath, 'w', encoding='utf-8') as f:
            f.write(content)

update_locales()
update_files()
print("Success!")
