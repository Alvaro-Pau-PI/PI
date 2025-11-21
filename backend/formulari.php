<?php
// backend/formulari.php (Gestió de la pujada d'Excel)

$message = '';
$message_type = '';

// Verificar si s'ha enviat el formulari
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    
    $file = $_FILES['excel_file'];

    // 1. Verificar errors de subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = "Error en la subida: código {$file['error']}.";
        $message_type = 'error';
    } else {
        // 2. Verificar la extensión del archivo
        $allowed_extensions = ['xlsx', 'xls', 'csv'];
        $file_info = pathinfo($file['name']);
        $file_ext = strtolower($file_info['extension']);

        if (!in_array($file_ext, $allowed_extensions)) {
            $message = "Error: Formato no permitido. Solo se aceptan .xlsx, .xls o .csv.";
            $message_type = 'error';
        } else {
            // 3. Definir la ruta de guardado (usa /var/www/uploads/)
            $upload_path = '/var/www/uploads/productes.xlsx';
            
            // 4. Mover el archivo subido a la carpeta /uploads/
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                
                // 5. Ejecutar el script de importación
                $import_script = '/var/www/html/importar_excel.php';
                
                // Ejecutamos el script de importación de forma síncrona
                $output = shell_exec("php {$import_script}");

                if ($output === null) {
                    $message = "Advertencia: El script de importación debe ejecutarse manualmente: php {$import_script}";
                    $message_type = 'warning';
                } else {
                    $message = "Archivo subido con éxito y proceso de importación completado. Verifique el JSON Server.";
                    $message_type = 'success';
                    // Mostrar el resultado del script de importación
                    $message .= "<pre>{$output}</pre>";
                }

            } else {
                $message = "Error al guardar el archivo. Verifique los permisos de la carpeta 'uploads/' (Debe tener permisos de escritura para el usuario de Docker).";
                $message_type = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Importación de Catálogo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
  <header>
    <div class="cabecera">
      <div class="logo-box">
        <a href="index.html" title="Ir al inicio AlberoPerez Tech">
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
    <div class="contact-container" style="max-width: 800px;">
        <h2>📥 Administración: Importación de Productos</h2>
        <p style="color: #9BA3B0; text-align: center;">Carga el catálogo inicial desde un archivo Excel/CSV.</p>
        
        <?php if ($message): 
            // Adaptar los mensajes a las clases de estilo tech
            $box_class = '';
            if ($message_type == 'success') {
                $box_class = 'success-message';
            } elseif ($message_type == 'error') {
                $box_class = 'error-msg';
            } elseif ($message_type == 'warning') {
                $box_class = 'error-msg'; // Usamos la misma clase roja para advertencias
            }
        ?>
            <div class="<?= $box_class ?>" style="text-align: left; padding: 25px;">
                <p><strong><?= $message ?></strong></p>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" style="margin-top: 30px;">
            <div class="form-group">
                <label for="excel_file">Selecciona el archivo Excel/CSV:</label>
                <input type="file" name="excel_file" id="excel_file" class="form-input" accept=".xlsx,.xls,.csv" required style="border: none;">
            </div>
            
            <button type="submit" class="btn-submit" style="width: 100%;">Cargar e Importar Catálogo</button>
        </form>

        <hr style="border-color: #3A4150; margin: 30px 0;">
        <p style="color: #00A1FF; text-align: center;">Verificar estado de la API: 
            <a href="http://localhost:3000/productes" target="_blank" style="color: #00A1FF; text-decoration: none;">
                http://localhost:3000/productes
            </a>
        </p>
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
          <li><a href="formulari.php" style="color: #00A1FF;">Admin / Importar Excel</a></li>
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