<?php
/**
 * Script para re-enlazar imágenes adicionales que ahora existen físicamente.
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$publicPath = public_path();
$relinked = 0;

$knownExtraImages = [
  "AMD Ryzen 5 7600X-2.webp", "AMD Ryzen 5 7600X-3.webp",
  "ASUS Dual GeForce RTX 4070 Super-2.webp", "ASUS Dual GeForce RTX 4070 Super-3.webp", "ASUS Dual GeForce RTX 4070 Super-4.webp", "ASUS Dual GeForce RTX 4070 Super-5.webp",
  "ASUS TUF GAMING B650-PLUS WIFI-2.jpg", "ASUS TUF GAMING B650-PLUS WIFI-3.jpg", "ASUS TUF GAMING B650-PLUS WIFI-4.jpg",
  "CPU-AMD-7800X3D-2.webp", "Corsair 4000D Airflow Cristal Templado-2.webp", "Corsair 4000D Airflow Cristal Templado-3.jpg",
  "Corsair Vengeance DDR5 32GB (2x16GB)-2.webp", "Corsair Vengeance DDR5 32GB (2x16GB)-3.webp",
  "Intel Core i5-13600K-2.webp", "Intel Core i9-14900K-2.webp",
  "Kingston FURY Beast DDR4 16GB (2x8GB)-2.webp", "Kingston FURY Beast DDR4 16GB (2x8GB)-3.webp",
  "MSI MPG A1000G PCIE5 1000W 80 Plus Gold-2.webp", "MSI MPG A1000G PCIE5 1000W 80 Plus Gold-3.webp",
  "Samsung 990 PRO 2TB-2.webp", "Samsung 990 PRO 2TB-3.webp", "Samsung 990 PRO 2TB-4.webp",
  "WD_BLACK SN850X 1TB-2.webp", "WD_BLACK SN850X 1TB-3.webp"
];

$products = Product::all();

foreach ($products as $product) {
    if (!$product->name) continue;
    
    $images = is_array($product->images) ? $product->images : [];
    $modified = false;

    foreach ($knownExtraImages as $imgName) {
        if (str_starts_with($imgName, $product->name . '-')) {
            $path = '/img/productos/' . $imgName;
            if (!in_array($path, $images)) {
                $images[] = $path;
                $modified = true;
            }
        }
    }
    
    // Caso especial Ryzen 7
    if (($product->sku === 'CPU-AMD-7800X3D' || str_contains($product->name, '7800X3D')) && !in_array('/img/productos/CPU-AMD-7800X3D-2.webp', $images)) {
        $images[] = '/img/productos/CPU-AMD-7800X3D-2.webp';
        $modified = true;
    }

    if ($modified) {
        $product->images = array_unique($images);
        $product->save();
        echo "Producto #{$product->id} ({$product->name}): re-enlazadas imágenes adicionales.\n";
        $relinked++;
    }
}

echo "\n✅ Productos restaurados: {$relinked}\n";
