<?php

// Script para probar la subida de imágenes
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\Product;

// Simular una petición con imagen
$request = new Request();
$request->merge([
    'name' => 'Producto de Prueba',
    'description' => 'Descripción del producto de prueba',
    'price' => 99.99,
    'stock' => 10,
    'category' => 'Processadors'
]);

// Verificar configuración de almacenamiento
echo "=== Configuración de Almacenamiento ===\n";
$disk = \Illuminate\Support\Facades\Storage::disk('public');
echo "Disk root: " . $disk->path('') . "\n";
echo "URL base: " . \Illuminate\Support\Facades\Storage::disk('public')->url('') . "\n";

// Verificar enlace simbólico
echo "\n=== Verificación de Enlace Simbólico ===\n";
$linkPath = public_path('storage');
$targetPath = storage_path('app/public');
echo "Enlace: $linkPath -> $targetPath\n";
echo "Existe enlace: " . (is_link($linkPath) ? 'SÍ' : 'NO') . "\n";
echo "Apunta a: " . (is_link($linkPath) ? readlink($linkPath) : 'N/A') . "\n";

// Verificar directorio de imágenes
echo "\n=== Directorio de Imágenes ===\n";
$imgDir = storage_path('app/public/img/productos');
echo "Directorio: $imgDir\n";
echo "Existe: " . (is_dir($imgDir) ? 'SÍ' : 'NO') . "\n";
echo "Permisos: " . (is_dir($imgDir) ? substr(sprintf('%o', fileperms($imgDir)), -4) : 'N/A') . "\n";

if (is_dir($imgDir)) {
    $files = scandir($imgDir);
    $imageFiles = array_diff($files, ['.', '..']);
    echo "Número de imágenes: " . count($imageFiles) . "\n";
    if (count($imageFiles) > 0) {
        echo "Primeras 5 imágenes:\n";
        $i = 0;
        foreach ($imageFiles as $file) {
            if ($i >= 5) break;
            echo "  - $file\n";
            $i++;
        }
    }
}

// Probar URL de una imagen existente
echo "\n=== Prueba de URLs ===\n";
$testImage = 'img/productos/CPU-AMD-7800X3D.png';
$fullPath = storage_path('app/public/' . $testImage);
echo "Imagen de prueba: $testImage\n";
echo "Ruta completa: $fullPath\n";
echo "Existe archivo: " . (file_exists($fullPath) ? 'SÍ' : 'NO') . "\n";

if (file_exists($fullPath)) {
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($testImage);
    echo "URL generada: $url\n";
    echo "URL accesible: " . (file_exists(public_path('storage/' . $testImage)) ? 'SÍ' : 'NO') . "\n";
}

// Verificar productos en BD con imágenes
echo "\n=== Productos en Base de Datos ===\n";
try {
    $products = \App\Models\Product::take(5)->get(['id', 'name', 'image', 'images']);
    echo "Número total de productos: " . \App\Models\Product::count() . "\n";
    echo "Productos con imagen principal: " . \App\Models\Product::whereNotNull('image')->count() . "\n";
    echo "Productos con galería: " . \App\Models\Product::whereNotNull('images')->count() . "\n";
    
    foreach ($products as $product) {
        echo "\nProducto #{$product->id}: {$product->name}\n";
        echo "  Imagen principal: " . ($product->image ?: 'NULL') . "\n";
        echo "  Galería: " . ($product->images ? count($product->images) . ' imágenes' : 'NULL') . "\n";
    }
} catch (Exception $e) {
    echo "Error al consultar productos: " . $e->getMessage() . "\n";
}

echo "\n=== Diagnóstico Completado ===\n";
