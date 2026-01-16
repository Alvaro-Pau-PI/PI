<?php
require_once 'includes/contacte_logic.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacto | AlberoPerez Tech</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
  <link rel="stylesheet" href="../styles.css">

  <style>
      .error-js { 
          color: #ff4d4d; /* Rojo neón */
          font-size: 0.85em; 
          display: none; /* Oculto por defecto */
          margin-top: 5px; 
          font-weight: 500;
      }
      /* Clase que JS añade al input cuando falla */
      .input-error { 
          border-color: #ff4d4d !important; 
          box-shadow: 0 0 8px rgba(255, 77, 77, 0.3) !important;
      }
  </style>
</head>

<body>
  <header>
    <div class="cabecera">
      <div class="logo-box">
        <a href="index.php" title="Ir al inicio AlberoPerez Tech">
          <img src="img/LOGO AlberoPerezTech.png" alt="Logo AlberoPerez Tech" class="logo" />
        </a>
      </div>

      <nav class="nav-box">
        <div class="dropdown">
          <button type="button" class="categoria-link" aria-label="Abrir menú">
            <span class="material-icons">menu</span>
          </button>
          
          <div class="dropdown-content">
            <a href="contacte.php">Contacto</a>
            <a href="formulari.php">Admin / Importar Excel</a>
            <a href="#">Componentes</a>
            <a href="#">Ordenadores</a>
          </div>
        </div>
        
        <div class="buscador-box">
          <form action="#" method="get" role="search">
            <input type="text" class="buscador" name="q" placeholder="Buscar componente, PC...">
          </form>
        </div>
      </nav>

      <div class="iconos-box">
        <a href="auth/login.php" title="Mi cuenta" class="icon-user">
          <span class="material-icons">person</span>
        </a>
        <a href="#" title="Carrito de compras" class="icon-cart">
          <span class="material-icons">shopping_cart</span>
        </a>
      </div>
    </div>
  </header>

  <main>
    <h2>Contacta con Nosotros</h2>
    <p>¿Tienes dudas sobre tu montaje o algún componente? Estamos aquí para ayudarte.</p>

    <div class="contact-container">
        
        <?php if ($enviat): ?>
            <div class="success-message">
                <span class="material-icons" style="font-size: 48px; margin-bottom: 10px;">check_circle</span>
                <h3>¡Mensaje Enviado!</h3>
                <p>Gracias <strong><?= htmlspecialchars($nom) ?></strong>. Hemos recibido tu consulta sobre "<em><?= htmlspecialchars($assumpte) ?></em>".</p>
                <p>Te responderemos a <strong><?= htmlspecialchars($email) ?></strong> lo antes posible.</p>
                <a href="contacte.php" class="btn-back">Enviar otro mensaje</a>
            </div>

        <?php else: ?>
            <form id="contactForm" method="post" action="" class="contact-form">
                
                <div class="form-group">
                    <label for="nom">Nombre</label>
                    <input type="text" id="nom" name="nom" class="form-input" 
                           placeholder="Ej: Pau Albero" 
                           value="<?= htmlspecialchars($nom) ?>" required>
                    
                    <span id="error-nom" class="error-js"></span>
                    
                    <?php if (isset($errors['nom'])): ?>
                        <span class="error-msg"><?= $errors['nom'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="text" id="email" name="email" class="form-input" 
                           placeholder="Ej: nombre@correo.com" 
                           value="<?= htmlspecialchars($email) ?>" required>
                    
                    <span id="error-email" class="error-js"></span>
                    
                    <?php if (isset($errors['email'])): ?>
                        <span class="error-msg"><?= $errors['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="assumpte">Asunto</label>
                    <input type="text" id="assumpte" name="assumpte" class="form-input" 
                           placeholder="Ej: Duda sobre RTX 4070" 
                           value="<?= htmlspecialchars($assumpte) ?>" required>
                    
                    <span id="error-assumpte" class="error-js"></span>
                    
                    <?php if (isset($errors['assumpte'])): ?>
                        <span class="error-msg"><?= $errors['assumpte'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="missatge">Mensaje</label>
                    <textarea id="missatge" name="missatge" class="form-textarea" 
                              placeholder="Escribe aquí tu consulta..." required minlength="10"><?= htmlspecialchars($missatge) ?></textarea>
                    
                    <span id="error-missatge" class="error-js"></span>
                    
                    <?php if (isset($errors['missatge'])): ?>
                        <span class="error-msg"><?= $errors['missatge'] ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-submit">Enviar Mensaje</button>
            </form>
        <?php endif; ?>

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
          <li><a href="contacte.php" style="color: #00A1FF;">Contacto</a></li>
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

  <script src="validacio.js"></script>
</body>
</html>