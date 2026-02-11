<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Productes de prova basats en el db.json del legacy (productes reals).
     */
    public function run(): void
    {
        $products = [
            [
                'sku' => 'CPU-AMD-7800X3D',
                'name' => 'AMD Ryzen 7 7800X3D',
                'description' => 'Procesador Gaming con tecnología 3D V-Cache 8 núcleos',
                'price' => 399.90,
                'stock' => 30,
                'image' => 'img/productos/CPU-AMD-7800X3D.png',
                'category' => 'Processadors',
            ],
            [
                'sku' => 'CPU-INT-13600K',
                'name' => 'Intel Core i5-13600K',
                'description' => 'Procesador Intel 13ª Gen 14 núcleos (6P+8E) hasta 5.1GHz',
                'price' => 319.95,
                'stock' => 45,
                'image' => 'img/productos/CPU-INT-13600K.png',
                'category' => 'Processadors',
            ],
            [
                'sku' => 'GPU-NV-4070S',
                'name' => 'ASUS Dual GeForce RTX 4070 Super',
                'description' => 'Gráfica 12GB GDDR6X ideal para 1440p con Ray Tracing',
                'price' => 659.90,
                'stock' => 20,
                'image' => 'img/productos/GPU-NV-4070S.png',
                'category' => 'Targetes Gràfiques',
            ],
            [
                'sku' => 'GPU-NV-4090',
                'name' => 'Gigabyte GeForce RTX 4090 Gaming OC',
                'description' => 'La tarjeta gráfica más potente del mercado 24GB GDDR6X',
                'price' => 1899.00,
                'stock' => 5,
                'image' => 'img/productos/GPU-NV-4090.png',
                'category' => 'Targetes Gràfiques',
            ],
            [
                'sku' => 'GPU-AMD-7800XT',
                'name' => 'Sapphire Pulse AMD Radeon RX 7800 XT',
                'description' => 'Gráfica AMD 16GB GDDR6 rendimiento excelente en rasterización',
                'price' => 549.90,
                'stock' => 15,
                'image' => 'img/productos/GPU-AMD-7800XT.png',
                'category' => 'Targetes Gràfiques',
            ],
            [
                'sku' => 'MB-ASUS-B650',
                'name' => 'ASUS TUF GAMING B650-PLUS WIFI',
                'description' => 'Placa base Socket AM5 DDR5 PCIe 5.0 para Ryzen 7000',
                'price' => 199.99,
                'stock' => 40,
                'image' => 'img/productos/MB-ASUS-B650.png',
                'category' => 'Plaques Base',
            ],
            [
                'sku' => 'RAM-COR-32D5',
                'name' => 'Corsair Vengeance DDR5 32GB (2x16GB)',
                'description' => 'Kit de memoria RAM DDR5 6000MHz CL30 optimizado AMD/Intel',
                'price' => 124.99,
                'stock' => 60,
                'image' => 'img/productos/RAM-COR-32D5.png',
                'category' => 'Memòria RAM',
            ],
            [
                'sku' => 'SSD-SAM-990',
                'name' => 'Samsung 990 PRO 2TB',
                'description' => 'SSD NVMe M.2 PCIe 4.0 velocidades de hasta 7450 MB/s',
                'price' => 179.99,
                'stock' => 55,
                'image' => 'img/productos/SSD-SAM-990.png',
                'category' => 'Emmagatzematge',
            ],
            [
                'sku' => 'PSU-COR-850',
                'name' => 'Corsair RM850x Shift 850W 80 Plus Gold',
                'description' => 'Fuente de alimentación modular con conectores laterales ATX 3.0',
                'price' => 159.90,
                'stock' => 25,
                'image' => 'img/productos/PSU-COR-850.png',
                'category' => 'Fonts Alimentació',
            ],
            [
                'sku' => 'CASE-NZXT-H5',
                'name' => 'NZXT H5 Flow RGB Blanco',
                'description' => 'Semitorre ATX con alto flujo de aire y ventiladores RGB',
                'price' => 109.99,
                'stock' => 30,
                'image' => 'img/productos/CASE-NZXT-H5.png',
                'category' => 'Caixes',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']], // Buscar per SKU
                $product                     // Dades a insertar/actualitzar
            );
        }

        $this->command->info('✅ S\'han insertat ' . count($products) . ' productes de prova.');
    }
}
