<?php
// backend/auth/register.php

// 1. Requisit previ: Incloure la funció de connexió a JSON Server i iniciar la sessió
require_once '../includes/json_connect.php'; 
session_start();

// Redirigir si l'usuari ja està autenticat
if (isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
    header("Location: profile.php");
    exit;
}

$errors = [];
$nom_usuari = $email = $nom = $cognoms = ''; 

// 2. Processament del formulari (Només si s'ha enviat via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Neteja i obtenció de dades
    $nom_usuari = trim($_POST['nom_usuari'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';
    $contrasenya_confirm = $_POST['contrasenya_confirm'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $cognoms = trim($_POST['cognoms'] ?? '');

    // 3. Validació bàsica de dades
    if (empty($nom_usuari) || empty($email) || empty($contrasenya) || empty($contrasenya_confirm)) {
        $errors[] = "Tots els camps amb * són obligatoris.";
    }

    if ($contrasenya !== $contrasenya_confirm) {
        $errors[] = "Les contrasenyes no coincideixen.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El format de l'email no és vàlid.";
    }
    
    if (strlen($contrasenya) < 6) {
        $errors[] = "La contrasenya ha de tenir almenys 6 caràcters.";
    }


    // 4. Validació de duplicats al JSON Server
    if (empty($errors)) {
        
        // Comprovar nom d'usuari duplicat
        $existing_user_by_name = get_users_by_field('nom_usuari', $nom_usuari);
        if ($existing_user_by_name !== null && !empty($existing_user_by_name)) {
            $errors[] = "El nom d'usuari '{$nom_usuari}' ja està registrat.";
        }

        // Comprovar email duplicat
        $existing_user_by_email = get_users_by_field('email', $email);
        if ($existing_user_by_email !== null && !empty($existing_user_by_email)) {
            $errors[] = "L'adreça de correu electrònic '{$email}' ja està registrada.";
        }
    }

    // 5. Creació i enviament si no hi ha errors
    if (empty($errors)) {
        
        $hashed_password = password_hash($contrasenya, PASSWORD_DEFAULT);
        
        $user_data = [
            "nom_usuari" => $nom_usuari,
            "contrasenya" => $hashed_password, 
            "email" => $email,
            "nom" => $nom,
            "cognoms" => $cognoms,
            "data_registre" => date('c')
        ];

        $new_user = create_user($user_data);

        if ($new_user) {
            header("Location: login.php?registered=1");
            exit;
        } else {
            $errors[] = "Error al connectar o registrar l'usuari al servidor de dades. Si us plau, revisa que el JSON Server estigui actiu i la connexió PHP sigui correcta.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre d'Usuari | AlberoPerez Tech</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Ruta al CSS del frontend -->
    <link rel="stylesheet" href="../../styles.css">
</head>

<body>
  <!-- CABECERA COPIADA DEL INDEX -->
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
  <!-- FIN CABECERA -->

  <main>
    <div class="contact-container" style="max-width: 550px;">

        <h2>📝 Registre d'Usuari</h2>
        
        <!-- Mensajes de error de login -->
        <?php if (!empty($errors)): ?>
            <div class="error-msg" style="background: rgba(255, 0, 0, 0.1); padding: 10px; border-radius: 5px; border: 1px solid #ff4d4d; margin-bottom: 20px;">
                <p>S'han trobat els següents errors:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulario de Registro -->
        <form action="register.php" method="POST" class="contact-form" style="gap: 15px;">
            
            <div class="form-group">
                <label for="nom_usuari">Nom d'Usuari *:</label>
                <input type="text" id="nom_usuari" name="nom_usuari" class="form-input" value="<?= htmlspecialchars($nom_usuari) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email *:</label>
                <input type="email" id="email" name="email" class="form-input" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="contrasenya">Contrasenya *:</label>
                <input type="password" id="contrasenya" name="contrasenya" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="contrasenya_confirm">Confirma Contrasenya *:</label>
                <input type="password" id="contrasenya_confirm" name="contrasenya_confirm" class="form-input" required>
            </div>
            
            <hr style="border-color: #3A4150;">

            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" class="form-input" value="<?= htmlspecialchars($nom) ?>">
            </div>

            <div class="form-group">
                <label for="cognoms">Cognoms:</label>
                <input type="text" id="cognoms" name="cognoms" class="form-input" value="<?= htmlspecialchars($cognoms) ?>">
            </div>
            
            <button type="submit" class="btn-submit">Registrar-se</button>
        </form>
        
        <p style="text-align: center; margin-top: 30px; color: #9BA3B0;">
            Ja tens un compte? <a href="login.php" style="color: #00A1FF;">Inicia sessió aquí</a>.
        </p>

    </div>
  </main>

  <!-- FOOTER COPIADO DEL INDEX -->
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