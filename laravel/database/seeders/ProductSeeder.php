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
                'eco_score' => 85,
                'has_eco_packaging' => true
            ],
            [
                'sku' => 'CPU-INT-13600K',
                'name' => 'Intel Core i5-13600K',
                'description' => 'Procesador Intel 13ª Gen 14 núcleos (6P+8E) hasta 5.1GHz',
                'price' => 319.95,
                'stock' => 45,
                'image' => 'img/productos/CPU-INT-13600K.png',
                'category' => 'Processadors',
                'eco_score' => 70,
                'is_refurbished' => true
            ],
            [
                'sku' => 'CPU-AMD-5800X3D',
                'name' => 'AMD Ryzen 7 5800X3D',
                'description' => 'El mejor procesador para gaming en plataforma AM4 con 3D V-Cache',
                'price' => 289.90,
                'stock' => 15,
                'image' => 'img/productos/ryzen.png', // Usar imagen genérica Ryzen disponible
                'category' => 'Processadors',
                'eco_score' => 90,
                'is_local_supplier' => true
            ],
            [
                'sku' => 'CPU-INT-14900K',
                'name' => 'Intel Core i9-14900K',
                'description' => 'Potencia extrema para creadores y gamers, hasta 6.0GHz',
                'price' => 599.99,
                'stock' => 10,
                'image' => 'img/productos/CPU-INT-14900K.png', // Imagen disponible localmente
                'category' => 'Processadors',
                'eco_score' => 60, // Nota baja para probar
                'carbon_footprint' => 12.5
            ],
            [
                'sku' => 'CPU-AMD-7600X',
                'name' => 'AMD Ryzen 5 7600X',
                'description' => 'Rendimiento excelente en gaming con la nueva arquitectura Zen 4',
                'price' => 229.50,
                'stock' => 50,
                'image' => 'img/productos/ryzen.png', // Usar imagen genérica Ryzen disponible
                'category' => 'Processadors',
                'eco_score' => 75
            ],
            [
                'sku' => 'GPU-NV-4070S',
                'name' => 'ASUS Dual GeForce RTX 4070 Super',
                'description' => 'Gráfica 12GB GDDR6X ideal para 1440p con Ray Tracing',
                'price' => 659.90,
                'stock' => 20,
                'image' => 'img/productos/GPU-NV-4070S.png',
                'category' => 'Targetes Gràfiques',
                'eco_score' => 75,
                'has_eco_packaging' => true
            ],
            [
                'sku' => 'GPU-NV-4090',
                'name' => 'Gigabyte GeForce RTX 4090 Gaming OC',
                'description' => 'La tarjeta gráfica más potente del mercado 24GB GDDR6X',
                'price' => 1899.00,
                'stock' => 5,
                'image' => 'img/productos/GPU-NV-4090.png',
                'category' => 'Targetes Gràfiques',
                'eco_score' => 65,
                'carbon_footprint' => 15.5
            ],
            [
                'sku' => 'GPU-AMD-7800XT',
                'name' => 'Sapphire Pulse AMD Radeon RX 7800 XT',
                'description' => 'Gráfica AMD 16GB GDDR6 rendimiento excelente en rasterización',
                'price' => 549.90,
                'stock' => 15,
                'image' => 'img/productos/GPU-AMD-7800XT.png',
                'category' => 'Targetes Gràfiques',
                'eco_score' => 82,
                'is_local_supplier' => true
            ],
            [
                'sku' => 'MB-ASUS-B650',
                'name' => 'ASUS TUF GAMING B650-PLUS WIFI',
                'description' => 'Placa base Socket AM5 DDR5 PCIe 5.0 para Ryzen 7000',
                'price' => 199.99,
                'stock' => 40,
                'image' => 'img/productos/MB-ASUS-B650.png',
                'category' => 'Plaques Base',
                'eco_score' => 78,
                'is_refurbished' => true
            ],
            [
                'sku' => 'MB-MSI-Z790',
                'name' => 'MSI MPG Z790 EDGE WIFI',
                'description' => 'Placa base Intel Z790 LGA 1700 DDR5 para 12ª/13ª/14ª Gen',
                'price' => 359.90,
                'stock' => 25,
                'image' => 'img/productos/MB-MSI-Z790.png',
                'category' => 'Plaques Base', // Añadido para tener relacionado
                'eco_score' => 70
            ],
            [
                'sku' => 'RAM-COR-32D5',
                'name' => 'Corsair Vengeance DDR5 32GB (2x16GB)',
                'description' => 'Kit de memoria RAM DDR5 6000MHz CL30 optimizado AMD/Intel',
                'price' => 124.99,
                'stock' => 60,
                'image' => 'img/productos/RAM-COR-32D5.png',
                'category' => 'Memòria RAM',
                'eco_score' => 88,
                'has_eco_packaging' => true
            ],
            [
                'sku' => 'RAM-KIN-16D4',
                'name' => 'Kingston FURY Beast DDR4 16GB (2x8GB)',
                'description' => 'Memoria RAM DDR4 3200MHz CL16 ideal para actualizaciones',
                'price' => 45.99,
                'stock' => 100,
                'image' => 'img/productos/RAM-KIN-16D4.png',
                'category' => 'Memòria RAM', // Añadido para tener relacionado
                'eco_score' => 90,
                'is_local_supplier' => true
            ],
            [
                'sku' => 'SSD-SAM-990',
                'name' => 'Samsung 990 PRO 2TB',
                'description' => 'SSD NVMe M.2 PCIe 4.0 velocidades de hasta 7450 MB/s',
                'price' => 179.99,
                'stock' => 55,
                'image' => 'img/productos/SSD-SAM-990.png',
                'category' => 'Emmagatzematge',
                'eco_score' => 85,
                'carbon_footprint' => 0.5
            ],
            [
                'sku' => 'SSD-WD-SN850X',
                'name' => 'WD_BLACK SN850X 1TB',
                'description' => 'SSD NVMe Gaming PCIe 4.0 con disipador térmico',
                'price' => 99.99,
                'stock' => 40,
                'image' => 'img/productos/SSD-WD-SN850X.png',
                'category' => 'Emmagatzematge', // Añadido para tener relacionado
                'eco_score' => 72
            ],
            [
                'sku' => 'PSU-COR-850',
                'name' => 'Corsair RM850x Shift 850W 80 Plus Gold',
                'description' => 'Fuente de alimentación modular con conectores laterales ATX 3.0',
                'price' => 159.90,
                'stock' => 25,
                'image' => 'img/productos/PSU-COR-850.png',
                'category' => 'Fonts Alimentació',
                'eco_score' => 92,
                'has_eco_packaging' => true
            ],
            [
                'sku' => 'PSU-MSI-1000',
                'name' => 'MSI MPG A1000G PCIE5 1000W 80 Plus Gold',
                'description' => 'Fuente 1000W modular preparada para RTX 40 Series',
                'price' => 189.90,
                'stock' => 15,
                'image' => 'img/productos/PSU-MSI-1000.png',
                'category' => 'Fonts Alimentació', // Añadido para tener relacionado
                'eco_score' => 80
            ],
            [
                'sku' => 'CASE-NZXT-H5',
                'name' => 'NZXT H5 Flow RGB Blanco',
                'description' => 'Semitorre ATX con alto flujo de aire y ventiladores RGB',
                'price' => 109.99,
                'stock' => 30,
                'image' => 'img/productos/CASE-NZXT-H5.png',
                'category' => 'Caixes',
                'eco_score' => 76,
                'is_refurbished' => true
            ],
            [
                'sku' => 'CASE-COR-4000D',
                'name' => 'Corsair 4000D Airflow Cristal Templado',
                'description' => 'Caja ATX semitorre con panel frontal de alto flujo de aire',
                'price' => 104.90,
                'stock' => 50,
                'image' => 'img/productos/CASE-COR-4000D.png',
                'category' => 'Caixes',
                'eco_score' => 88,
                'is_local_supplier' => true
            ],

            // ═══════════════════════════════════════════════════════════════
            // NOUS PRODUCTES — Expansió d'inventari (febrer 2026)
            // ═══════════════════════════════════════════════════════════════

            // --- Refrigeració: 3 nous productes líderes de mercat ---
            [
                'sku' => 'COOL-COR-H150I',
                'name' => 'Corsair iCUE H150i Elite Capellix XT 360mm',
                'description' => 'Refrigeración líquida AIO 360mm con tres ventiladores RGB QL120, cabezal con display LCD IPS, compatible AM5/LGA1700. Caudal máximo 98,8 L/h y control vía software iCUE.',
                'price' => 189.90,
                'stock' => 18,
                'image' => 'img/productos/COOL-COR-H150I.png',
                'category' => 'Refrigeració',
                'eco_score' => 72,
                'has_eco_packaging' => true,
            ],
            [
                'sku' => 'COOL-BEQ-DRP4',
                'name' => 'be quiet! Dark Rock Pro 4',
                'description' => 'Disipador de aire de doble torre 250W TDP, siete heatpipes de cobre niquelado, dos ventiladores Silent Wings 120/135mm, acabado negro mate premium, compatible AM5/LGA1700.',
                'price' => 89.90,
                'stock' => 25,
                'image' => 'img/productos/COOL-BEQ-DRP4.png',
                'category' => 'Refrigeració',
                'eco_score' => 85,
                'is_recyclable' => true,
            ],
            [
                'sku' => 'COOL-NOC-NFA12',
                'name' => 'Noctua NF-A12x25 PWM — Pack 3 unitats',
                'description' => 'Pack de 3 ventiladors premium 120mm PWM, 2000 RPM màxim, 60,1 CFM, 22,6 dBA. Tecnologia AAO Frame i motor SSO2. Inclou accessoris SecuFirm i adaptadors de soroll reduït.',
                'price' => 99.90,
                'stock' => 40,
                'image' => 'img/productos/COOL-NOC-NFA12.png',
                'category' => 'Refrigeració',
                'eco_score' => 91,
                'is_recyclable' => true,
                'is_local_supplier' => true,
            ],

            // --- +1 nou producte per a cada categoria existent ---
            [
                'sku' => 'CPU-AMD-7950X',
                'name' => 'AMD Ryzen 9 7950X',
                'description' => 'Procesador AMD 16 núcleos / 32 hilos, arquitectura Zen 4, boost hasta 5.7GHz, TDP configurable 65-170W, socket AM5, soporte DDR5 nativo y PCIe 5.0.',
                'price' => 549.90,
                'stock' => 12,
                'image' => 'img/productos/CPU-AMD-7950X.png',
                'category' => 'Processadors',
                'eco_score' => 76,
                'carbon_footprint' => 10.8,
            ],
            [
                'sku' => 'GPU-MSI-4070TIS',
                'name' => 'MSI GeForce RTX 4070 Ti SUPER Gaming X Trio',
                'description' => 'Tarjeta gráfica 16GB GDDR6X, Ada Lovelace, DLSS 3.5, ray tracing 2ª gen, triple ventilador TORX Fan 5.0, 285W TDP, ideal para 4K gaming y creación de contenido.',
                'price' => 849.00,
                'stock' => 10,
                'image' => 'img/productos/GPU-MSI-4070TIS.png',
                'category' => 'Targetes Gràfiques',
                'eco_score' => 70,
                'has_eco_packaging' => true,
            ],
            [
                'sku' => 'MB-ASUS-B760F',
                'name' => 'ASUS ROG STRIX B760-F Gaming WiFi',
                'description' => 'Placa base LGA1700 Intel 12ª/13ª/14ª Gen, DDR5 hasta 7800MHz OC, PCIe 5.0 M.2, WiFi 6E, 2.5G LAN, VRM 14+1 DrMOS, soporte Thunderbolt 4.',
                'price' => 289.90,
                'stock' => 22,
                'image' => 'img/productos/MB-ASUS-B760F.png',
                'category' => 'Plaques Base',
                'eco_score' => 74,
            ],
            [
                'sku' => 'RAM-GSK-64D5',
                'name' => 'G.Skill Trident Z5 RGB 64GB DDR5 (2x32GB)',
                'description' => 'Kit DDR5 6400MHz CL32, disipador de aluminio con RGB direccionable, optimizado Intel XMP 3.0 y AMD EXPO, ideal para estaciones de trabajo y gaming extremo.',
                'price' => 219.99,
                'stock' => 35,
                'image' => 'img/productos/RAM-GSK-64D5.png',
                'category' => 'Memòria RAM',
                'eco_score' => 80,
                'has_eco_packaging' => true,
            ],
            [
                'sku' => 'SSD-SEA-FC530',
                'name' => 'Seagate FireCuda 530 2TB NVMe Gen4',
                'description' => 'SSD NVMe M.2 PCIe Gen4 x4 hasta 7300/6900 MB/s lect/escr, disipador incluido, compatible PS5, 1800 TBW garantizados, 5 años de garantía.',
                'price' => 169.99,
                'stock' => 45,
                'image' => 'img/productos/SSD-SEA-FC530.png',
                'category' => 'Emmagatzematge',
                'eco_score' => 80,
                'carbon_footprint' => 0.6,
            ],
            [
                'sku' => 'PSU-SEA-TX1000',
                'name' => 'Seasonic PRIME TX-1000 1000W 80+ Titanium',
                'description' => 'Fuente totalmente modular 1000W, certificación 80 Plus Titanium (>94% eficiencia), ventilador híbrido PWM 135mm, protección OCP/OVP/SCP, 12 años de garantía.',
                'price' => 249.90,
                'stock' => 14,
                'image' => 'img/productos/PSU-SEA-TX1000.png',
                'category' => 'Fonts Alimentació',
                'eco_score' => 95,
                'is_recyclable' => true,
                'has_eco_packaging' => true,
            ],
            [
                'sku' => 'CASE-FRA-TORRENT',
                'name' => 'Fractal Design Torrent Compact RGB Negro',
                'description' => 'Semitorre ATX con panel frontal de malla abierta para máximo airflow, tres ventiladores RGB Aspect 140mm incluidos, vidrio templado lateral, soporte radiadores hasta 360mm.',
                'price' => 149.90,
                'stock' => 28,
                'image' => 'img/productos/CASE-FRA-TORRENT.png',
                'category' => 'Caixes',
                'eco_score' => 83,
                'is_recyclable' => true,
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
