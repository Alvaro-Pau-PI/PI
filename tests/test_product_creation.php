<?php

// Script para probar la creación de un producto con imagen
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\Product;

// Crear una petición simulada para crear producto
echo "=== Prueba de Creación de Producto con Imagen ===\n";

// Verificar que el directorio de imágenes existe y tiene permisos
$imgDir = storage_path('app/public/img/productos');
echo "Verificando directorio: $imgDir\n";
echo "Existe: " . (is_dir($imgDir) ? 'SÍ' : 'NO') . "\n";
echo "Permisos: " . (is_dir($imgDir) ? substr(sprintf('%o', fileperms($imgDir)), -4) : 'N/A') . "\n";

// Verificar enlace simbólico
$linkPath = public_path('storage');
echo "Enlace simbólico: $linkPath\n";
echo "Existe: " . (is_link($linkPath) ? 'SÍ' : 'NO') . "\n";
if (is_link($linkPath)) {
    echo "Apunta a: " . readlink($linkPath) . "\n";
    echo "El destino existe: " . (file_exists(readlink($linkPath)) ? 'SÍ' : 'NO') . "\n";
}

// Listar algunas imágenes existentes
if (is_dir($imgDir)) {
    $files = scandir($imgDir);
    $imageFiles = array_diff($files, ['.', '..']);
    echo "\nImágenes existentes (" . count($imageFiles) . "):\n";
    $i = 0;
    foreach ($imageFiles as $file) {
        if ($i >= 3) break;
        echo "  - $file\n";
        $i++;
    }
}

// Probar URL generation
echo "\n=== Prueba de Generación de URLs ===\n";
$testPaths = [
    'img/productos/CPU-AMD-7800X3D.png',
    '/img/productos/CPU-AMD-7800X3D.png',
    'storage/img/productos/CPU-AMD-7800X3D.png'
];

foreach ($testPaths as $path) {
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($path, '/'));
    echo "Path: $path -> URL: $url\n";
    
    // Verificar si el archivo existe
    $fullPath = storage_path('app/public/' . ltrim($path, '/'));
    echo "  Archivo existe: " . (file_exists($fullPath) ? 'SÍ' : 'NO') . "\n";
    
    // Verificar si es accesible vía web
    $webPath = public_path('storage/' . ltrim($path, '/'));
    echo "  Web path existe: " . (file_exists($webPath) ? 'SÍ' : 'NO') . "\n";
}

// Probar configuración de Nginx (simulada)
echo "\n=== Configuración de Proxy ===\n";
echo "Frontend URL: http://localhost:5173\n";
echo "Backend URL: http://localhost:8000\n";
echo "Storage URL: http://localhost:8000/storage/\n";

// Probar acceso a imágenes vía curl (simulado)
echo "\n=== Prueba de Acceso HTTP ===\n";
$testImage = 'img/productos/CPU-AMD-7800X3D.png';
$urls = [
    "http://localhost:8000/storage/$testImage",
    "http://localhost:5173/$testImage",
    "http://localhost:5173/storage/$testImage"
];

foreach ($urls as $url) {
    echo "Probando: $url\n";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($result && $httpCode === 200) {
        echo "  ✅ OK (HTTP $httpCode)\n";
    } else {
        echo "  ❌ ERROR (HTTP $httpCode)\n";
    }
}

echo "\n=== Diagnóstico Completado ===\n";
