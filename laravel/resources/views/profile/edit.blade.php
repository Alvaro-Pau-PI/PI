<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Profile') }} | AlberoPerez Tech</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Tailwind (needed for form components) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { background-color: #1A1D24; color: #EAEAEA; }
        
        .profile-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .profile-header {
            margin-bottom: 30px;
            text-align: center;
        }
        .profile-header h2 {
            font-family: 'Montserrat', sans-serif;
            color: #EAEAEA;
            font-size: 2em;
        }

        .profile-section {
            background-color: #242833;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #3A4150;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Override Laravel Breeze input styles if needed, but Tailwind classes usually handle it. 
           We just ensure contrasts. */
        input, select, textarea {
            background-color: #11141a !important; 
            border-color: #3A4150 !important;
            color: #EAEAEA !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="cabecera">
            <div class="logo-box">
                <a href="{{ url('/') }}" title="Ir al inicio AlberoPerez Tech">
                    <img src="{{ asset('img/LOGO AlberoPerezTech.png') }}" alt="Logo AlberoPerez Tech" class="logo" />
                </a>
            </div>
    
            <nav class="nav-box">
                <a href="{{ route('products.index') }}" style="color: #9BA3B0; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    <span class="material-icons">arrow_back</span> Tornar al catàleg
                </a>
            </nav>
    
            <div class="iconos-box">
                @auth
                    <a href="{{ route('profile.edit') }}" title="El meu perfil" class="icon-user">
                        <span class="material-icons" style="color: #00A1FF;">person</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" title="Tancar sessió" style="background:none; border:none; color:#EAEAEA; cursor:pointer;">
                            <span class="material-icons">logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" title="Iniciar sessió" class="icon-user">
                        <span class="material-icons">person_outline</span>
                    </a>
                @endauth
                
                <a href="#" title="Carrito de compras" class="icon-cart">
                    <span class="material-icons">shopping_cart</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="profile-container">
            <div class="profile-header">
                <h2>{{ __('Profile') }}</h2>
            </div>

            <div class="profile-section">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="profile-section">
                @include('profile.partials.update-password-form')
            </div>

            <div class="profile-section">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer">
            <div class="footerEspacio">
                <img src="{{ asset('img/LOGO AlberoPerezTech.png') }}" alt="Logo AlberoPerez Tech pie" style="width: 50px;">
                <p>Tu tienda de informática y componentes de confianza.</p>
            </div>
            <div class="footerEspacio">
                <strong>¡Suscríbete!</strong>
                <p>Recibe las mejores ofertas y novedades.</p>
                <div class="newsletter-form">
                    <input type="email" placeholder="Escribe tu email aquí">
                    <button>Suscribirse</button>
                </div>
            </div>
            <div class="footerEspacio">
                <strong>Enlaces Útiles</strong>
                <ul>
                    <li><a href="{{ route('contact') }}">Contacto</a></li>
                    <li><a href="#">Guía de montaje de PCs</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footerEspacio">
                <strong>Legal</strong>
                <ul>
                    <li><a href="#">Política de Privacidad</a></li>
                    <li><a href="#">Términos y Condiciones</a></li>
                </ul>
            </div>
        </div>
        <span class="copyright">&copy; {{ date('Y') }} AlberoPerez Tech. Todos los derechos reservados.</span>
    </footer>

</body>
</html>
