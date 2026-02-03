<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AlberoPerez Tech | Productes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        /* Ajustes específicos para Laravel sobre el CSS original */
        .producto { display: flex; flex-direction: column; position: relative; }
        
        .btn-group {
            display: flex; gap: 10px; justify-content: center; margin-top: auto;
        }

        .btn-reviews {
            background: transparent; border: 1px solid #00A1FF; color: #00A1FF;
            padding: 8px 16px; border-radius: 20px; cursor: pointer;
            transition: all 0.2s; font-family: 'Montserrat', sans-serif; font-weight: 600;
        }
        .btn-reviews:hover { background: #00A1FF; color: white; }

        .btn-buy {
            background: #FF6C00; border: none; color: white;
            padding: 8px 20px; border-radius: 20px; cursor: pointer;
            transition: all 0.2s; font-family: 'Montserrat', sans-serif; font-weight: 600;
            text-decoration: none; display: inline-block;
        }
        .btn-buy:hover { background: #e65c00; transform: scale(1.05); }

        /* Modal Reviews */
        #reviews-modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.7); justify-content: center; align-items: center;
        }
        .modal-content {
            background-color: #242833; margin: auto; padding: 20px; border: 1px solid #3A4150; border-radius: 12px;
            width: 90%; max-width: 600px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); color: #EAEAEA;
            max-height: 90vh; overflow-y: auto; text-align: left;
        }
        .close-modal { color: #aaaaaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close-modal:hover { color: #00A1FF; }
        .review-item { border-bottom: 1px solid #3A4150; padding: 10px 0; }
        .review-header { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 0.9em; color: #9BA3B0; }
        .review-stars { color: #FFC107; margin-bottom: 5px; }

        /* N8N Chatbot Overrides */
        :root {
            --chat--color--primary: #00A1FF !important;
            --chat--toggle--background-color: #00A1FF !important;
        }
    </style>
    
    <!-- N8N Chatbot CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />
</head>
<body>

    <header>
        <div class="cabecera">
            <div class="logo-box">
                <a href="{{ url('/') }}" title="Ir al inicio AlberoPerez Tech">
                    <img src="{{ asset('img/LOGO AlberoPerezTech.png') }}" alt="Logo AlberoPerez Tech" class="logo" />
                </a>
            </div>
    
            <nav class="nav-box">
                <div class="dropdown">
                    <button type="button" class="categoria-link" aria-label="Abrir menú">
                        <span class="material-icons">menu</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('contact') }}">Contacto</a>
                        <a href="{{ url('/dashboard') }}">Area Client</a>
                        <a href="{{ route('products.index') }}">Productes</a>
                    </div>
                </div>
    
                <div class="buscador-box">
                    <form action="{{ route('products.index') }}" method="get" role="search">
                        <input type="text" class="buscador" name="q" value="{{ request('q') }}" placeholder="Buscar componente, PC...">
                    </form>
                </div>
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

    <section class="banner">
        <img src="{{ asset('img/banner2.png') }}" alt="Banner Promocional" />
    </section>

    <main>
        <h2>Novedades Destacadas</h2>
        <p class="subtitle" style="text-align: center; color: #9BA3B0; margin-bottom: 40px;">Descubre los componentes más recientes y potentes del mercado.</p>

        <section class="productos">
            @forelse($products as $product)
                <article class="producto">
                    <!-- Imagen: Usamos asset() si existe ruta, sino placeholder -->
                    <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; flex-grow: 1;">
                        <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset($product->image ?? 'img/placeholder.png') }}" 
                             alt="{{ $product->name }}"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/200x200?text=No+Image';">
                        
                        <span class="precio">{{ number_format($product->price, 2) }} €</span>
                        <h3 class="nombre">{{ $product->name }}</h3>
                        <p class="detalle">{{ Str::limit($product->description, 60) }}</p>
                    </a>

                    <div class="btn-group">
                        <a href="{{ route('products.show', $product) }}" class="btn-buy" title="Veure detalls">Veure</a>
                        <button class="btn-reviews" onclick="openReviewsModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                            Valoracions
                        </button>
                    </div>
                </article>
            @empty
                <p style="text-align: center; width: 100%;">No s'han trobat productes.</p>
            @endforelse
        </section>
        
        <br><br>

        <h2>Montaje</h2>
        <div style="text-align:center;">
             <video class="video-con-bordes" src="{{ asset('img/VideoOrdenadorInfinito.mp4') }}" autoplay muted loop playsinline>
                Tu navegador no soporta el elemento de video.
            </video>
        </div>
    </main>

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

    <!-- Reviews Modal -->
    <div id="reviews-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3 style="margin-top:0; color:#00A1FF;">Valoracions: <span id="modal-product-name"></span></h3>
            
            <div id="reviews-list"></div>

            @auth
                <form id="review-form" style="margin-top:20px; border-top:1px solid #3A4150; padding-top:15px;">
                    <h4 style="color:#EAEAEA;">Deixa la teva opinió</h4>
                    <select id="review-rating" required style="width:100%; padding:10px; margin-bottom:10px; background:#1A1D24; color:#fff; border:1px solid #3A4150;">
                        <option value="" disabled selected>Puntuació (1-5)</option>
                        <option value="5">★★★★★ (5)</option>
                        <option value="4">★★★★☆ (4)</option>
                        <option value="3">★★★☆☆ (3)</option>
                        <option value="2">★★☆☆☆ (2)</option>
                        <option value="1">★☆☆☆☆ (1)</option>
                    </select>
                    <textarea id="review-text" rows="3" placeholder="Escriu el teu comentari..." required style="width:100%; padding:10px; margin-bottom:10px; background:#1A1D24; color:#fff; border:1px solid #3A4150; box-sizing:border-box;"></textarea>
                    <div id="review-feedback" style="margin-bottom:10px;"></div>
                    <button type="submit" style="width:100%; padding:10px; background:#00A1FF; border:none; color:white; font-weight:bold; cursor:pointer;">Enviar Valoració</button>
                </form>
            @else
                <div style="margin-top:20px; padding-top:20px; border-top:1px solid #3A4150; text-align:center;">
                    <p>Inicia sessió per deixar un comentari.</p>
                    <a href="{{ route('login') }}" style="color:#00A1FF; text-decoration:none; font-weight:bold;">Login</a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/reviews.js') }}"></script>
    <script src="{{ asset('js/carousel.js') }}"></script>
    
    <!-- N8N Chatbot Script -->
    <script type="module">
        import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';
    
        createChat({
            webhookUrl: 'http://localhost:5678/webhook/1bf7c92c-ec5d-41dd-88f7-73d2bfdd4914/chat',
            initialMessages: [
                '¡Hola! 👋',
                '¿En qué puedo ayudarte hoy?'
            ],
            i18n: {
                en: {
                    title: '💻AlberoPerezTech BOT',
                    subtitle: 'Tu experto tecnológico 24/7 🚀',
                    footer: '',
                    getStarted: 'Empezar chat',
                    inputPlaceholder: 'Escribe tu pregunta...',
                },
            },
            style: {
                '--chat--message--user--color': '#FFFFFF',
                '--chat--message--user--background-color': '#00A1FF',
                '--chat--color--primary': '#00A1FF',
                '--chat--toggle--background-color': '#00A1FF',
                '--chat--toggle--hover--background-color': '#0088CC',
                '--chat--toggle--size': '64px',
            },
        });
    </script>
</body>
</html>
