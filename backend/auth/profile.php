<?php
// backend/auth/profile.php

// 1. Requisits: Incloure la connexió a JSON Server i iniciar la sessió
require_once '../includes/json_connect.php';
session_start();

$user_data = null;
$user_id = null;

// 2. Comprovació d'Autenticació (Acceptant IDs de text/string)
if (isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
    
    // Assignem directament l'ID de la cookie (que pot ser un string com "472b")
    $user_id = $_COOKIE['user_id'];
    
    // 3. Obtenir les dades de l'usuari del JSON Server (GET /usuaris/{id})
    $user_data = get_user_by_id($user_id);
    
    // Si la ID existeix a la cookie però no al servidor (eliminat, per exemple)
    if (!$user_data) {
        // Forçar tancament de sessió
        setcookie('user_id', '', time() - 3600, "/"); 
        $user_id = null;
    }
}

// Si la cookie no existeix o la verificació ha fallat (usuari = null), redirigir al login
if (!$user_id) {
    // Redirigir amb un missatge d'error
    header("Location: login.php?error=not_logged_in");
    exit;
}

// Si arribem aquí, $user_data conté la informació de l'usuari.

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?= htmlspecialchars($user_data['nom_usuari'] ?? 'Usuari') ?> | AlberoPerez Tech</title>
    
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
    <div class="contact-container" style="max-width: 600px; text-align: left;">

        <h2>👤 Perfil d'Usuari</h2>
        <p>Aquesta és la pàgina protegida de l'usuari.</p>

        <div class="info-box" style="background: #1A1D24; padding: 20px; border-radius: 8px;">
            <p><strong>Nom d'Usuari:</strong> <span style="color: #00A1FF;"><?= htmlspecialchars($user_data['nom_usuari'] ?? 'N/A') ?></span></p>
            <p><strong>ID de Sessió:</strong> <span style="color: #9BA3B0; font-family: monospace;"><?= htmlspecialchars($user_data['id'] ?? 'N/A') ?></span></p>
            <hr style="border-color: #3A4150;">
            <p><strong>Email:</strong> <?= htmlspecialchars($user_data['email'] ?? 'N/A') ?></p>
            <p><strong>Nom Complet:</strong> <?= htmlspecialchars(($user_data['nom'] ?? '') . ' ' . ($user_data['cognoms'] ?? '')) ?></p>
            <p><strong>Membre des de:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($user_data['data_registre'] ?? ''))) ?></p>
        </div>

        <a href="logout.php" class="btn-submit" style="background: #FF6C00; text-align: center; margin-top: 30px;">🚪 Tancar Sessió</a>

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