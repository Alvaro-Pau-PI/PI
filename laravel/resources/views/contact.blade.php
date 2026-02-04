<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contacte - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Roboto', sans-serif; background: #1A1D24; color: #EAEAEA; margin: 0; }
        
        header { background: #242833; border-bottom: 2px solid #3A4150; padding: 15px 0; }
        .cabecera { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .nav-links a { color: #EAEAEA; text-decoration: none; margin-left: 20px; font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: #00A1FF; }

        main { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h2 { text-align: center; color: #00A1FF; font-family: 'Montserrat', sans-serif; font-size: 2.2em; margin-bottom: 30px; }

        .form-box {
            background: #242833; padding: 30px; border-radius: 12px; border: 1px solid #3A4150;
        }

        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; }
        input, textarea {
            width: 100%; padding: 12px; background: #1A1D24; border: 1px solid #3A4150; 
            color: #fff; border-radius: 6px; box-sizing: border-box; font-family: inherit; font-size: 1em;
        }
        input:focus, textarea:focus { border-color: #00A1FF; outline: none; }
        
        .error-msg {
            color: #e74c3c; font-size: 0.9em; margin-top: 5px; display: none;
        }
        .input-error { border-color: #e74c3c; }

        button {
            background: #00A1FF; color: white; padding: 12px 24px; border: none; border-radius: 6px; 
            cursor: pointer; font-weight: bold; width: 100%; font-size: 1.1em;
        }
        button:hover { background: #0088CC; }

        footer { background: #242833; padding: 20px; text-align: center; margin-top: 50px; border-top: 1px solid #3A4150; color: #9BA3B0; }
    </style>
</head>
<body>

    <header>
        <div class="cabecera">
            <div class="logo-box">
               <span style="font-size: 2em;">💻</span>
            </div>
            <nav class="nav-links">
                <a href="{{ url('/') }}">Inici</a>
                <a href="{{ route('products.index') }}">Productes</a>
            </nav>
        </div>
    </header>

    <main>
        <h2>Contacta amb nosaltres</h2>
        
        <div class="form-box">
            <form id="contactForm" action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required minlength="2" placeholder="El teu nom">
                    <span class="error-msg" id="error-nom"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="nom@exemple.com">
                    <span class="error-msg" id="error-email"></span>
                </div>

                <div class="form-group">
                    <label for="assumpte">Assumpte</label>
                    <input type="text" id="assumpte" name="assumpte" required minlength="5" placeholder="Motiu de la consulta">
                    <span class="error-msg" id="error-assumpte"></span>
                </div>

                <div class="form-group">
                    <label for="missatge">Missatge</label>
                    <textarea id="missatge" name="missatge" rows="5" required minlength="10" placeholder="Escriu aquí..."></textarea>
                    <span class="error-msg" id="error-missatge"></span>
                </div>

                <button type="submit">Enviar Missatge</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tots els drets reservats.</p>
    </footer>

    <!-- Script de Validació -->
    <script src="{{ asset('js/validacio.js') }}"></script>

</body>
</html>
