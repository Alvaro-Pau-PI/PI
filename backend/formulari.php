<?php
// backend/formulari.php

// NOTA: Asegúrate de que el archivo importar_excel.php está en el mismo directorio.

$message = '';
$message_type = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    
    $file = $_FILES['excel_file'];

    // 1. Verificar errores de subida
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
            // 3. Definir la ruta de guardado
            // La ruta /var/www/uploads/ mapea a tu carpeta local PI/uploads/
            $upload_path = '/var/www/uploads/productes.xlsx';
            
            // 4. Mover el archivo subido a la carpeta /uploads/
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                
                // 5. Ejecutar el script de importación
                // Usamos la ruta del script de importación dentro del contenedor
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
    <title>Importación de Catálogo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; margin-bottom: 15px; }
        .error { background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 15; }
        .warning { background-color: #fff3cd; color: #856404; padding: 10px; border: 1px solid #ffeeba; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📥 Importación de Productos (Excel → JSON Server)</h2>
        
        <?php if ($message): ?>
            <div class="<?= $message_type ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="excel_file">Selecciona el archivo Excel/CSV:</label><br><br>
            <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required><br><br>
            <button type="submit">Cargar e Importar Catálogo</button>
        </form>

        <hr>
        <p>Verificar productos importados: <a href="http://localhost:3000/productes" target="_blank">http://localhost:3000/productes</a></p>
    </div>
</body>
</html>