<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class SustainableProductsSeeder extends Seeder
{
    /**
     * Seed de productos sostenibles con diversas etiquetas eco
     * 
     * Este seeder crea productos de ejemplo que demuestran
     * los diferentes criterios de sostenibilidad ASG.
     */
    public function run(): void
    {
        $sustainableProducts = [
            // Producto 1: Reacondicionado de alta gama
            [
                'sku' => 'GPU-NVIDIA-3080-RECON',
                'name' => 'NVIDIA RTX 3080 Reacondicionada',
                'description' => 'Tarjeta gráfica RTX 3080 profesionalmente reacondicionada. Garantía de 1 año. Ahorro de 150kg CO2 vs nueva.',
                'price' => 599.99,
                'stock' => 5,
                'image' => '/img/products/gpu-3080-refurb.webp',
                'category' => 'Targetes Gràfiques',
                'eco_score' => 85,
                'is_refurbished' => true,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => false,
                'carbon_footprint' => 2.5,
            ],
            
            // Producto 2: Procesador local con embalaje eco
            [
                'sku' => 'CPU-AMD-7600X-ECO',
                'name' => 'AMD Ryzen 5 7600X - Embalaje Sostenible',
                'description' => 'Procesador AMD de última generación. Embalaje 100% reciclado y reciclable. Distribuidor local.',
                'price' => 279.99,
                'stock' => 12,
                'image' => '/img/products/cpu-amd-7600x.webp',
                'category' => 'Processadors',
                'eco_score' => 75,
                'is_refurbished' => false,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => true,
                'carbon_footprint' => 3.2,
            ],
            
            // Producto 3: SSD con bajo impacto
            [
                'sku' => 'SSD-SAMSUNG-980-ECO',
                'name' => 'Samsung 980 PRO 1TB - Eco Edition',
                'description' => 'SSD NVMe de bajo consumo energético. Certificado Energy Star. Embalaje minimalista reciclable.',
                'price' => 129.99,
                'stock' => 20,
                'image' => '/img/products/ssd-samsung-980.webp',
                'category' => 'Emmagatzematge',
                'eco_score' => 70,
                'is_refurbished' => false,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => false,
                'carbon_footprint' => 1.8,
            ],
            
            // Producto 4: Placa base reacondicionada
            [
                'sku' => 'MB-ASUS-B550-RECON',
                'name' => 'ASUS ROG B550 Reacondicionada',
                'description' => 'Placa base ATX reacondicionada y verificada. Segunda vida para reducir residuos electrónicos.',
                'price' => 149.99,
                'stock' => 8,
                'image' => '/img/products/mb-asus-b550.webp',
                'category' => 'Plaques Base',
                'eco_score' => 80,
                'is_refurbished' => true,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => true,
                'carbon_footprint' => 4.5,
            ],
            
            // Producto 5: RAM con certificación ambiental
            [
                'sku' => 'RAM-CRUCIAL-32GB-ECO',
                'name' => 'Crucial 32GB DDR4 - Green Edition',
                'description' => 'Memoria RAM fabricada con procesos de bajo impacto. Certificación RoHS y REACH.',
                'price' => 89.99,
                'stock' => 25,
                'image' => '/img/products/ram-crucial-32gb.webp',
                'category' => 'Memòria RAM',
                'eco_score' => 65,
                'is_refurbished' => false,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => false,
                'carbon_footprint' => 2.1,
            ],
            
            // Producto 6: Fuente modular eficiente
            [
                'sku' => 'PSU-CORSAIR-850-80PLUS',
                'name' => 'Corsair RM850 80+ Gold - Eficiencia Energética',
                'description' => 'Fuente modular con certificación 80+ Gold (90% eficiencia). Ahorro energético significativo.',
                'price' => 119.99,
                'stock' => 15,
                'image' => '/img/products/psu-corsair-850.webp',
                'category' => 'Fonts Alimentació',
                'eco_score' => 72,
                'is_refurbished' => false,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => true,
                'carbon_footprint' => 6.8,
            ],
            
            // Producto 7: Torre con materiales reciclados
            [
                'sku' => 'CASE-NZXT-H510-RECYCLE',
                'name' => 'NZXT H510 - Aluminio Reciclado',
                'description' => 'Torre ATX fabricada con 60% de aluminio reciclado. Diseño atemporal para larga vida útil.',
                'price' => 89.99,
                'stock' => 10,
                'image' => '/img/products/case-nzxt-h510.webp',
                'category' => 'Caixes',
                'eco_score' => 78,
                'is_refurbished' => false,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => false,
                'carbon_footprint' => 12.5,
            ],
            
            // Producto 8: Refrigeración reacondicionada
            [
                'sku' => 'COOL-NOCTUA-NH-D15-RECON',
                'name' => 'Noctua NH-D15 Reacondicionado',
                'description' => 'Disipador de aire premium reacondicionado. Rendimiento sin concesiones, sostenibilidad garantizada.',
                'price' => 69.99,
                'stock' => 6,
                'image' => '/img/products/cool-noctua-d15.webp',
                'category' => 'Refrigeració',
                'eco_score' => 82,
                'is_refurbished' => true,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => true,
                'carbon_footprint' => 3.7,
            ],
            
            // Producto 9: GPU entrada sostenible
            [
                'sku' => 'GPU-AMD-6600-ECO',
                'name' => 'AMD Radeon RX 6600 - Bajo Consumo',
                'description' => 'Tarjeta gráfica eficiente energéticamente. TDP reducido de 132W. Embalaje eco.',
                'price' => 249.99,
                'stock' => 18,
                'image' => '/img/products/gpu-amd-6600.webp',
                'category' => 'Targetes Gràfiques',
                'eco_score' => 68,
                'is_refurbished' => false,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => false,
                'carbon_footprint' => 4.2,
            ],
            
            // Producto 10: Kit completo reacondicionado
            [
                'sku' => 'CPU-INTEL-12600K-RECON',
                'name' => 'Intel Core i5-12600K Reacondicionado',
                'description' => 'Procesador Intel 12ª Gen reacondicionado. Pruebas exhaustivas, garantía extendida.',
                'price' => 219.99,
                'stock' => 7,
                'image' => '/img/products/cpu-intel-12600k.webp',
                'category' => 'Processadors',
                'eco_score' => 88,
                'is_refurbished' => true,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
                'is_local_supplier' => true,
                'carbon_footprint' => 2.9,
            ],
        ];

        // Insertar productos sostenibles
        foreach ($sustainableProducts as $productData) {
            Product::create($productData);
        }

        $this->command->info('✅ Se crearon ' . count($sustainableProducts) . ' productos sostenibles con etiquetas eco.');
    }
}
