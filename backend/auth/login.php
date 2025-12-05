<?php
// backend/auth/login.php

// 1. Requisits previs: Incloure la funció de connexió a JSON Server i iniciar la sessió
require_once '../includes/json_connect.php';
session_start();

// Redirigir si l'usuari ja està autenticat (basat en la cookie)
if (isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
    header("Location: profile.php");
    exit;
}

$errors = [];
$nom_usuari = '';
$login_success = false;

// 2. Processament de l'inici de sessió (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Neteja i obtenció de dades
    $nom_usuari = trim($_POST['nom_usuari'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';

    // Validació bàsica
    if (empty($nom_usuari) || empty($contrasenya)) {
        $errors[] = "Si us plau, introdueix el nom d'usuari i la contrasenya.";
    }

    if (empty($errors)) {
        
        // 3. Cerca de l'usuari al JSON Server (GET /usuaris?nom_usuari=...)
        $users_found = get_users_by_field('nom_usuari', $nom_usuari);
        
        $user = (!empty($users_found) && is_array($users_found)) ? $users_found[0] : null;

        if ($user) {
            
            // 4. Validar la contrasenya xifrada amb password_verify()
            if (password_verify($contrasenya, $user['contrasenya'])) {
                
                // --- Autenticació reeixida ---
                session_regenerate_id(true); 

                // 5. Crear la Cookie d'identificació i la sessió
                $user_id = $user['id'];
                
                setcookie('user_id', $user_id, time() + (3600 * 24 * 7), "/", "", false, true); // Vigent per 1 setmana
                $_SESSION['user_id'] = $user_id; 

                // 6. Redirigir a la pàgina de perfil (o a la pàgina protegida)
                header("Location: profile.php");
                exit;

            } else {
                $errors[] = "Nom d'usuari o contrasenya incorrectes.";
            }

        } else {
            $errors[] = "Nom d'usuari o contrasenya incorrectes.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sessió | AlberoPerez Tech</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <header>
    <div class="cabecera">
      <div class="logo-box">
        <a href="../index.php" title="Ir al inicio AlberoPerez Tech">
          <img src="../img/LOGO AlberoPerezTech.png" alt="Logo AlberoPerez Tech" class="logo" />
        </a>
      </div>

      <nav class="nav-box">
        <div class="dropdown">
          <button type="button" class="categoria-link" aria-label="Abrir menú">
            <span class="material-icons">menu</span>
          </button>
          
          <div class="dropdown-content">
            <a href="../contacte.php">Contacto</a>
            <a href="../formulari.php">Admin / Importar Excel</a>
            <a href="#">Componentes</a>
            <a href="#">Ordenadores</a>
          </div>
        </div>
        
        <div class="buscador-box">
          <form action="#" method="get" role="search">
            <input type="text" class="buscador" name="q" placeholder="Buscar...">
          </form>
        </div>
      </nav>

      <div class="iconos-box">
        <a href="login.php" title="Mi cuenta" class="icon-user">
          <span class="material-icons">person</span>
        </a>
        <a href="#" title="Carrito de compras" class="icon-cart">
          <span class="material-icons">shopping_cart</span>
        </a>
      </div>
    </div>
  </header>
  <main>
    <div class="contact-container" style="max-width: 450px;">

        <h2>🔑 Inici de Sessió</h2>
        
        <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
            <div class="success-message" style="color: #2ecc71;">
                <span class="material-icons">check_circle</span>
                <p>✅ Registre completat amb èxit! Ja pots iniciar sessió.</p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logged_out']) && $_GET['logged_out'] == 1): ?>
            <div class="success-message" style="color: #2ecc71;">
                <span class="material-icons">check_circle</span>
                <p>🚪 Tancament de sessió correcte. Fins aviat!</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error-msg" style="background: rgba(255, 0, 0, 0.1); padding: 10px; border-radius: 5px; border: 1px solid #ff4d4d; margin-bottom: 20px;">
                <p>Error en iniciar sessió:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="contact-form" style="gap: 15px;">
            
            <div class="form-group">
                <label for="nom_usuari">Nom d'Usuari:</label>
                <input type="text" id="nom_usuari" name="nom_usuari" class="form-input" value="<?= htmlspecialchars($nom_usuari) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="contrasenya">Contrasenya:</label>
                <input type="password" id="contrasenya" name="contrasenya" class="form-input" required>
            </div>
            
            <button type="submit" class="btn-submit">Iniciar Sessió</button>
        </form>
        
        <p style="text-align: center; margin-top: 30px; color: #9BA3B0;">
            Encara no tens un compte? <a href="register.php" style="color: #00A1FF;">Registra't aquí</a>.
        </p>

    </div>
  </main>

  <footer>
    <div class="footer">
      <div class="footerEspacio">
        <img src="../img/LOGO AlberoPerezTech.png" alt="Logo AlberoPerez Tech pie">
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
          <li><a href="../contacte.php">Contacto</a></li>
          <li><a href="../formulari.php">Admin / Importar Excel</a></li>
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