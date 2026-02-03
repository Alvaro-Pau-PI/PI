<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productes - {{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Material Icons (usado en el CSS antiguo) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <style>
        /* Copia minimalista de frontend/styles.css adaptada */
        body { font-family: 'Roboto', sans-serif; background: #1A1D24; color: #EAEAEA; margin: 0; }
        
        /* Header simplificado */
        header { background: #242833; border-bottom: 2px solid #3A4150; padding: 15px 0; }
        .cabecera { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .logo { height: 50px; width: 50px; border-radius: 50%; object-fit: cover; }
        .nav-links a { color: #EAEAEA; text-decoration: none; margin-left: 20px; font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: #00A1FF; }

        /* Main Content */
        main { max-width: 1220px; margin: 40px auto; padding: 0 20px; }
        h2 { text-align: center; color: #00A1FF; font-family: 'Montserrat', sans-serif; font-size: 2.2em; margin-bottom: 10px; }
        p.subtitle { text-align: center; color: #9BA3B0; margin-bottom: 40px; }

        /* Grid Productos */
        .productos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .producto {
            background: #242833;
            border: 1px solid #3A4150;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: transform 0.25s, box-shadow 0.25s;
            display: flex; flex-direction: column;
        }
        .producto:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0, 161, 255, 0.15); }

        .producto img {
            width: 100%; max-width: 180px; height: 180px; object-fit: contain;
            margin: 0 auto 15px auto;
        }

        .precio {
            background: #FF6C00; color: #fff; font-weight: 700; font-family: 'Montserrat', sans-serif;
            padding: 6px 20px; border-radius: 20px; font-size: 1.25em; display: inline-block; margin-bottom: 15px;
        }

        .nombre {
            font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 1.2em; color: #EAEAEA;
            margin: 0 0 8px 0;
        }

        .detalle { color: #9BA3B0; font-size: 0.95em; }
        
        .stock-badge {
            display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; margin-top: 10px;
        }
        .stock-ok { background: rgba(46, 204, 113, 0.2); color: #2ecc71; border: 1px solid #2ecc71; }
        .stock-low { background: rgba(231, 76, 60, 0.2); color: #e74c3c; border: 1px solid #e74c3c; }

        /* Footer */
        footer { background: #242833; padding: 20px; text-align: center; margin-top: 50px; border-top: 1px solid #3A4150; color: #9BA3B0; }
    </style>
</head>
<body>

    <header>
        <div class="cabecera">
            <div class="logo-box">
                <!-- Placeholder Logo -->
               <span style="font-size: 2em;">💻</span>
            </div>
            <nav class="nav-links">
                <a href="{{ url('/') }}">Inici</a>
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Registre</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <h2>El Nostre Catàleg</h2>
        <p class="subtitle">Explora els millors components per al teu setup.</p>

        <div class="productos">
            @forelse($products as $product)
                <div class="producto">
                    <!-- Si tienes imágenes reales, usalas. Si no, un placeholder -->
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/200x200?text=Hardware" alt="No image">
                    @endif

                    <div>
                        <span class="precio">{{ number_format($product->price, 2) }} €</span>
                    </div>

                    <h3 class="nombre">{{ $product->name }}</h3>
                    
                    <span class="detalle">{{ $product->category ?? 'General' }}</span>
                    
                    <div>
                        @if($product->stock > 0)
                            <span class="stock-badge stock-ok">En estoc ({{ $product->stock }})</span>
                        @else
                            <span class="stock-badge stock-low">Esgotat</span>
                        @endif
                    </div>
                </div>
            @empty
                <p>No hi ha productes disponibles en aquest moment.</p>
            @endforelse
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tots els drets reservats.</p>
    </footer>

</body>
</html>
