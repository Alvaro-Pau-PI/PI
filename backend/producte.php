<?php
// backend/producte.php
require_once 'includes/json_connect.php';
session_start();

$id = $_GET['id'] ?? null;
$product = null;
if ($id) {
    $product = get_product_by_id($id);
}

if (!$product || empty($product)) {
    die("
    <body style='background-color: #1A1D24; color: #EAEAEA; font-family: sans-serif; text-align: center; padding-top: 50px;'>
        <h1>Producte no trobat 😕</h1>
        <a href='index.php' style='color: #00A1FF;'>Tornar</a>
    </body>");
}

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
    <link rel="stylesheet" href="../styles.css">
    
    <style>
        /* Estilos INLINE específicos para ajustar el Grid de producto */
        .p-image-box {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1A1D24;
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
        .p-price {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5em;
            color: #00A1FF;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .stock-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-weight: 600;
            background: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
            border: 1px solid #2ecc71;
            margin-bottom: 20px;
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
            margin-top: 20px;
        }
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
              <span class="material-icons">arrow_back</span> Tornar
          </a>
      </nav>
      <div class="iconos-box">
        <a href="<?= $user_icon_href ?>" class="icon-user"><span class="material-icons">person</span></a>
        <a href="#" class="icon-cart"><span class="material-icons">shopping_cart</span></a>
      </div>
    </div>
  </header>

  <main>
    <div class="single-card-container">
        
        <div class="product-detail-container">
            <div class="p-image-box">
                <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['nom']) ?>">
            </div>
            
            <div class="p-info-box">
                <h1 class="p-title"><?= htmlspecialchars($product['nom']) ?></h1>
                <div style="color: #9BA3B0; font-family: monospace; margin-bottom: 20px;">
                    REF: <?= htmlspecialchars($product['sku'] ?? 'N/A') ?>
                </div>
                <div class="p-price"><?= htmlspecialchars($product['preu']) ?>€</div>
                
                <div>
                    <?php if (($product['estoc'] ?? 0) > 0): ?>
                        <span class="stock-badge">En Estoc (<?= $product['estoc'] ?> u.)</span>
                    <?php else: ?>
                        <span class="stock-badge" style="color: #ff4d4d; border-color: #ff4d4d; background: rgba(255, 77, 77, 0.15);">Esgotat</span>
                    <?php endif; ?>
                </div>

                <div class="p-desc" style="border-top: 1px solid #3A4150; padding-top: 20px; margin-top: 10px; color: #EAEAEA;">
                    <?= nl2br(htmlspecialchars($product['descripcio'])) ?>
                </div>
                
                <button class="btn-buy-large">
                    Afegir al Carret <span class="material-icons">shopping_cart</span>
                </button>
            </div>
        </div>

        <section class="comments-section">
            <hr style="border: 0; border-top: 1px solid #3A4150; margin-bottom: 30px;">
            
            <h3>Opinions de clients</h3>
            
            <div id="comment-form-container" class="comment-form" style="display: none;">
                <h4>Deixa la teva opinió</h4>
                
                <div id="msg-feedback"></div>

                <form id="commentForm">
                    <input type="hidden" id="productId" value="<?= htmlspecialchars($product['id']) ?>">
                    
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required /><label for="star5">★</label>
                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4">★</label>
                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3">★</label>
                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2">★</label>
                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1">★</label>
                    </div>

                    <div class="form-group">
                        <textarea id="commentText" rows="4" class="form-input" placeholder="Què en penses?" required style="width: 100%; padding: 15px; margin-top: 10px; background: #1A1D24; color: #EAEAEA; border: 1px solid #3A4150; border-radius: 8px;"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit" style="width: 100%; margin-top: 15px;">Publicar</button>
                </form>
            </div>
            
            <div id="login-prompt" style="display: none; text-align: center; padding: 20px; background: #1A1D24; border-radius: 8px; border: 1px solid #3A4150;">
                <p>Per a deixar un comentari, <a href="auth/login.php" style="color: #00A1FF; font-weight: bold;">inicia sessió</a>.</p>
            </div>

            <div id="comments-list" class="comment-list">
                <p style="text-align: center; color: #999;">Carregant comentaris...</p>
            </div>
        </section>

    </div> </main>

  <script>
      const IS_LOGGED_IN = <?= $is_logged_in ? 'true' : 'false' ?>;
      const PRODUCT_ID = "<?= $product['id'] ?>";
  </script>
  <script src="js/comments.js?v=<?= time() ?>"></script>

  <footer>
    <div class="footer">
        <div class="footerEspacio"><p>&copy; 2025 AlberoPerez Tech</p></div>
    </div>
  </footer>
</body>
</html>