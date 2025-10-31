<?php
// Asegúrate de que las rutas son correctas desde el working_dir del contenedor PHP (/var/www/html)

// 1. Incluir el autoload de Composer
// La carpeta 'vendor' está en /var/www/html/vendor
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

// --- CONFIGURACIÓN DE RUTAS ---
// Archivo Excel de origen (debe estar en el volumen /uploads/)
$excelFilePath = __DIR__ . '/../uploads/productes.xlsx';

// La ruta del volumen mapeado es /var/www/data/
$jsonFilePath = '/var/www/data/products.json';

// --- INICIO DEL PROCESO DE IMPORTACIÓN ---
echo "--- Iniciando Importación de Productos ---\n";

// 1. Verificar que el archivo Excel exista
if (!file_exists($excelFilePath)) {
    die("Error: El archivo Excel no se encontró en: $excelFilePath\n");
}

try {
    // 2. Determinar el tipo de lector (Xlsx, Csv, etc.)
    // IOFactory::createReaderForFile lo detecta automáticamente
    $reader = IOFactory::createReaderForFile($excelFilePath);
    
    // Solo cargamos los datos (sin formato) para mejor rendimiento
    $reader->setReadDataOnly(true);

    // 3. Cargar el Spreadsheet
    $spreadsheet = $reader->load($excelFilePath);
    
    // 4. Seleccionar la primera hoja
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();

    // 5. Preparar la estructura de datos
    $products = [];
    $errors = [];
    $importedCount = 0;
    $idCounter = 1;

    // Obtener la fila de encabezados (asumimos que están en la fila 1)
    $headerRow = $worksheet->rangeToArray('A1:Z1', NULL, TRUE, FALSE)[0];
    
    // Mapeo de columnas a claves JSON
    // **NOTA:** Ajusta estos nombres de columna para que coincidan con tu Excel
    $columnMap = [
        'SKU'           => 'sku',
        'NOM'           => 'nom',
        'DESCRIPCIO'    => 'descripcio',
        'IMG'           => 'img',
        'PREU'          => 'preu',
        'ESTOC'         => 'estoc'
    ];
    
    echo "Leyendo {$highestRow} filas...\n";

    // 6. Iterar sobre las filas (empezando por la fila 2 para saltar encabezados)
    for ($row = 2; $row <= $highestRow; ++$row) {
        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $worksheet->getHighestColumn() . $row, NULL, TRUE, FALSE)[0];

        // 7. Mapear y validar datos
        $product = ['id' => $idCounter];
        $isValid = true;
        
        // Iteramos sobre los encabezados para mapear los datos
        foreach ($headerRow as $colIndex => $headerName) {
            $headerName = strtoupper(trim($headerName ?? ''));
            if (isset($columnMap[$headerName])) {
                $jsonKey = $columnMap[$headerName];
                $value = $rowData[$colIndex];
                
                // Validación básica de datos
                if ($jsonKey === 'preu' && (!is_numeric($value) || $value < 0)) {
                    $errors[] = "Fila {$row}: El precio ('{$value}') no es válido.";
                    $isValid = false;
                    break;
                }
                if ($jsonKey === 'estoc' && (!is_numeric($value) || $value < 0)) {
                    $errors[] = "Fila {$row}: El stock ('{$value}') no es válido.";
                    $isValid = false;
                    break;
                }
                
                $product[$jsonKey] = $value;
            }
        }

        if ($isValid) {
            // Asegurarse de que todos los campos requeridos existen (ej. 'sku', 'nom')
            if (!empty($product['nom']) && !empty($product['preu'])) {
                $products[] = $product;
                $importedCount++;
                $idCounter++;
            } else {
                $errors[] = "Fila {$row}: Faltan campos requeridos (Nom o Preu).";
            }
        }
    }

    // --- GENERAR JSON ---
    // 8. Crear la estructura final del JSON
    $finalJson = json_encode(['productes' => $products], JSON_PRETTY_PRINT);
    
    if (file_put_contents($jsonFilePath, $finalJson) === FALSE) {
         die("Error: No se pudo escribir el archivo JSON en: $jsonFilePath\n");
    }

    echo "--- Proceso Finalizado ---\n";
    echo "✅ Productos importados con éxito: {$importedCount}\n";
    echo "📂 Archivo JSON generado en: {$jsonFilePath}\n";

    if (!empty($errors)) {
        echo "\n⚠️ Errores encontrados:\n";
        foreach ($errors as $error) {
            echo " - {$error}\n";
        }
    }

} catch (ReaderException $e) {
    die("Error al leer el archivo Excel: " . $e->getMessage() . "\n");
} catch (\Exception $e) {
    die("Error inesperado: " . $e->getMessage() . "\n");
}
?>