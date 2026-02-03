<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} | AlberoPerez Tech</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        /* Estilos específicos para el detalle de producto */
        body { background-color: #1A1D24; color: #EAEAEA; }
        
        .product-detail-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .p-image-box {
            background: #242833;
            border-radius: 12px;
            padding: 30px;
            border: 1px solid #3A4150;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .p-image-box img {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
        }

        .p-info-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .p-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.4em;
            color: #EAEAEA;
            margin: 0 0 10px 0;
        }

        .p-ref {
            color: #9BA3B0;
            font-family: monospace;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        .p-price {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.8em;
            color: #00A1FF;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .stock-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 4px;
            font-weight: 600;
            margin-bottom: 25px;
        }
        .stock-in {
            background: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }
        .stock-out {
            background: rgba(255, 77, 77, 0.15);
            color: #ff4d4d;
            border: 1px solid #ff4d4d;
        }

        .p-desc {
            border-top: 1px solid #3A4150;
            padding-top: 20px;
            margin-top: 10px;
            color: #EAEAEA;
            line-height: 1.6;
            font-size: 1.1em;
        }

        .btn-buy-large {
            background: linear-gradient(135deg, #FF6C00 0%, #ff8c00 100%);
            color: white;
            border: none;
            padding: 18px 30px;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.2em;
            cursor: pointer;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-buy-large:hover {
            transform: scale(1.02);
            background: linear-gradient(135deg, #ff8c00 0%, #e67e22 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .product-detail-container {
                grid-template-columns: 1fr;
            }
        }
        
        /* Modal Styles Reuse */
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
        <div class="product-detail-container">
            
            <!-- Image Box -->
            <div class="p-image-box">
                <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset($product->image ?? 'img/placeholder.png') }}" 
                     alt="{{ $product->name }}" 
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/400x400?text=No+Image';">
            </div>
            
            <!-- Info Box -->
            <div class="p-info-box">
                <h1 class="p-title">{{ $product->name }}</h1>
                
                <div class="p-ref">
                    REF: {{ $product->sku ?? 'N/A' }}
                </div>
                
                <div class="p-price">
                    {{ number_format($product->price, 2) }} €
                </div>
                
                <div>
                    @if($product->stock > 0)
                        <span class="stock-badge stock-in">
                            En Estoc ({{ $product->stock }} u.)
                        </span>
                    @else
                        <span class="stock-badge stock-out">
                            Esgotat
                        </span>
                    @endif
                </div>

                <div class="p-desc">
                    {!! nl2br(e($product->description)) !!}
                </div>
                
                <button class="btn-buy-large">
                    Afegir al Carret <span class="material-icons">shopping_cart</span>
                </button>
                
                <button class="btn-reviews" onclick="openReviewsModal({{ $product->id }}, '{{ addslashes($product->name) }}')" style="margin-top: 15px; background: transparent; border: 1px solid #3A4150; color: #9BA3B0; padding: 10px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px;">
                    <span class="material-icons" style="font-size: 18px;">star_rate</span> Veure Valoracions
                </button>
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

</body>
</html>
