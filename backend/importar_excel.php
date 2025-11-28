<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

// --- CONFIGURACIÓN ---
$excelFilePath = __DIR__ . '/../uploads/productes.xlsx';
$jsonFilePath = '/var/www/data/db.json';

echo "--- Iniciando Importación ---\n";

if (!file_exists($excelFilePath)) {
    die("Error: No se encuentra el archivo Excel en: $excelFilePath\n");
}

try {
    //CARGAR DATOS EXISTENTES 
    $currentData = ['productes' => [], 'usuaris' => []];
    $existingSkus = []; // Array auxiliar para buscar duplicados rápido

    if (file_exists($jsonFilePath)) {
        $jsonContent = file_get_contents($jsonFilePath);
        $decoded = json_decode($jsonContent, true);
        if ($decoded) {
            $currentData = $decoded;
        }
    }

    // Asegurar estructura
    if (!isset($currentData['productes'])) $currentData['productes'] = [];
    if (!isset($currentData['usuaris'])) $currentData['usuaris'] = [];

    // Llenar lista de SKUs existentes para comparar
    foreach ($currentData['productes'] as $p) {
        if (isset($p['sku'])) {
            $existingSkus[strtoupper($p['sku'])] = true;
        }
    }

    //LEER EXCEL
    $reader = IOFactory::createReaderForFile($excelFilePath);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($excelFilePath);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    
    // Asumimos fila 1 como encabezados
    $headerRow = $worksheet->rangeToArray('A1:Z1', NULL, TRUE, FALSE)[0];
    
    // Mapeo Excel -> JSON
    $columnMap = [
        'SKU'           => 'sku',
        'NOM'           => 'nom',
        'DESCRIPCIO'    => 'descripcio',
        'IMG'           => 'img',
        'PREU'          => 'preu',
        'ESTOC'         => 'estoc'
    ];

    $newProductsCount = 0;
    $skippedCount = 0;
    $errors = [];
    $skippedLog = [];

    // PROCESAR FILAS
    echo "Procesando {$highestRow} filas...\n";

    for ($row = 2; $row <= $highestRow; ++$row) {
        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $worksheet->getHighestColumn() . $row, NULL, TRUE, FALSE)[0];
        
        if (empty(array_filter($rowData))) continue;

        $product = [];
        $skuFound = null;
        $isValid = true;
        
        // Mapear columnas
        foreach ($headerRow as $colIndex => $headerName) {
            $headerName = strtoupper(trim($headerName ?? ''));
            if (isset($columnMap[$headerName])) {
                $jsonKey = $columnMap[$headerName];
                $value = $rowData[$colIndex];
                
                // Validaciones
                if ($jsonKey === 'preu' && (!is_numeric($value) || $value < 0)) {
                    $errors[] = "Fila {$row}: Precio inválido.";
                    $isValid = false;
                }
                if ($jsonKey === 'estoc' && (!is_numeric($value) || $value < 0)) {
                    $errors[] = "Fila {$row}: Stock inválido.";
                    $isValid = false;
                }
                
                // Capturar SKU para chequeo de duplicados
                if ($jsonKey === 'sku') {
                    $skuFound = strtoupper(trim($value));
                }

                $product[$jsonKey] = $value;
            }
        }

        if ($isValid) {
            // Validar campos obligatorios
            if (empty($product['nom']) || empty($product['preu']) || empty($skuFound)) {
                $errors[] = "Fila {$row}: Falta Nombre, Precio o SKU.";
                continue;
            }

            // --- CONTROL DE DUPLICADOS ---
            if (isset($existingSkus[$skuFound])) {
                $skippedCount++;
                $skippedLog[] = "SKU: {$skuFound} ({$product['nom']})";
                continue; // Saltamos esta iteración, no añadimos el producto
            }

            // Si es nuevo: Asignar ID y añadir
            $product['id'] = uniqid('prod_'); // O usar un contador si prefieres
            
            // Añadir al array principal de productos
            $currentData['productes'][] = $product;
            
            // Registrar SKU para no duplicarlo dentro del mismo Excel
            $existingSkus[$skuFound] = true;
            $newProductsCount++;
        }
    }

    // GUARDAR TODO (Antiguos + Nuevos)
    $finalJson = json_encode($currentData, JSON_PRETTY_PRINT);
    
    if (file_put_contents($jsonFilePath, $finalJson) === FALSE) {
         die("Error: No se pudo escribir en: {$jsonFilePath}\n");
    }

    // 5. INFORME FINAL
    echo "\n--- RESUMEN ---\n";
    echo "✅ Nuevos añadidos: {$newProductsCount}\n";
    echo "⏭️ Omitidos (Ya existían): {$skippedCount}\n";
    
    if ($skippedCount > 0) {
        echo "\n[Lista de Omitidos]:\n";
        foreach($skippedLog as $log) echo " - $log\n";
    }

    if (!empty($errors)) {
        echo "\n⚠️ Errores de datos:\n";
        foreach ($errors as $error) echo " - {$error}\n";
    }

} catch (\Exception $e) {
    die("Excepción: " . $e->getMessage() . "\n");
}
?>