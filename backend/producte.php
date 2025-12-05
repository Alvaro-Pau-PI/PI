<?php
// backend/producte.php
require_once 'includes/json_connect.php';
session_start();

// 1. Obtenir ID de la URL
$id = $_GET['id'] ?? null;
$product = null;

if ($id) {
    $product = get_product_by_id($id);
}

// Si no hi ha producte, mostrem error
if (!$product || empty($product)) {
    die("
    <body style='background-color: #1A1D24; color: #EAEAEA; font-family: sans-serif; text-align: center; padding-top: 50px;'>
        <h1>Producte no trobat 😕</h1>
        <a href='index.php' style='color: #00A1FF;'>Tornar a l'inici</a>
    </body>");
}

// Dades d'usuari per la capçalera
$is_logged_in = isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id']);
$user_icon_href = $is_logged_in ? 'auth/profile.php' : 'auth/register.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['nom']) ?> | AlberoPerez Tech</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="styles.css">
    
    <style>
        /* Estils específics per a la fitxa de producte (Tema Tech) */
        .product-detail-container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Dos columnes: Img | Info */
            gap: 40px;
            max-width: 1100px;
            margin: 40px auto;
            background: #242833; /* Superfície fosca */
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #3A4150;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .p-image-box {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1A1D24; /* Fons més fosc per resaltar la img */
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #3A4150;
        }
        .p-image-box img {
            max-width: 100%;
            max-height: 400px;
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
            margin: 0 0 15px 0;
            line-height: 1.2;
        }

        .p-ref {
            color: #9BA3B0;
            font-size: 0.9em;
            margin-bottom: 20px;
            font-family: monospace;
        }

        .p-price {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5em;
            color: #00A1FF; /* Blau elèctric */
            font-weight: 700;
            margin-bottom: 20px;
        }

        .p-desc {
            color: #EAEAEA;
            font-size: 1.1em;
            line-height: 1.8;
            margin-bottom: 30px;
            border-top: 1px solid #3A4150;
            padding-top: 20px;
        }

        .stock-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: 600;
            background: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
            border: 1px solid #2ecc71;
            margin-bottom: 20px;
        }

        .btn-buy-large {
            background: linear-gradient(135deg, #FF6C00 0%, #ff8c00 100%); /* Taronja */
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
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-buy-large:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(255, 108, 0, 0.4);
        }

        /* Responsive mòbil */
        @media (max-width: 768px) {
            .product-detail-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

  <header>
    <div class="cabecera">
      <div class="logo-box">
        <a href="index.php"><img src="img/LOGO AlberoPerezTech.png" alt="Logo" class="logo" /></a>
      </div>
      <nav class="nav-box">
          <a href="index.php" style="color: #9BA3B0; text-decoration: none; display: flex; align-items: center; gap: 5px;">
              <span class="material-icons">arrow_back</span> Tornar al catàleg
          </a>
      </nav>
      <div class="iconos-box">
        <a href="<?= $user_icon_href ?>" class="icon-user"><span class="material-icons">person</span></a>
        <a href="#" class="icon-cart"><span class="material-icons">shopping_cart</span></a>
      </div>
    </div>
  </header>

  <main>
    <div class="product-detail-container">
        <div class="p-image-box">
            <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['nom']) ?>">
        </div>
        
        <div class="p-info-box">
            <h1 class="p-title"><?= htmlspecialchars($product['nom']) ?></h1>
            
            <div class="p-ref">
                REF/SKU: <?= htmlspecialchars($product['sku'] ?? 'N/A') ?>
            </div>

            <div class="p-price"><?= htmlspecialchars($product['preu']) ?>€</div>
            
            <div>
                <?php if (($product['estoc'] ?? 0) > 0): ?>
                    <span class="stock-badge">En Estoc (<?= $product['estoc'] ?> u.)</span>
                <?php else: ?>
                    <span class="stock-badge" style="color: #ff4d4d; border-color: #ff4d4d; background: rgba(255, 77, 77, 0.15);">Esgotat</span>
                <?php endif; ?>
            </div>

            <div class="p-desc">
                <?= nl2br(htmlspecialchars($product['descripcio'])) ?>
            </div>
            
            <button class="btn-buy-large">
                Afegir al Carret 
                <span class="material-icons">shopping_cart</span>
            </button>
        </div>
    </div>
  </main>

  <footer>
    <div class="footer">
      <div class="footerEspacio">
        <img src="img/LOGO AlberoPerezTech.png" alt="Logo AlberoPerez Tech pie">
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
          <li><a href="contacte.php">Contacto</a></li>
          <li><a href="formulari.php">Admin / Importar Excel</a></li>
          <li><a href="#">Guía de montaje de PCs</a></li>
          <li><a href="#">FAQ</a></li>
        </ul>
      </div>
      <div class="footerEspacio">
        <strong>Legal</strong>
        <ul>
          <li><a href="#">Política de Privacidad</a></li>
          <li><a href="#">Términos y Condiciones</a></li>
          <li><a href="#">Política de Cookies</a></li>
        </ul>
      </div>
    </div>
    <span class="copyright">&copy; 2025 AlberoPerez Tech. Todos los derechos reservados.</span>
  </footer>
</body>
</html>