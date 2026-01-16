<?php

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$headers = ['sku', 'name', 'description', 'price', 'stock', 'image', 'category'];
$sheet->fromArray($headers, NULL, 'A1');

// Data
$data = [
    [
        'CPU-AMD-7800X3D',
        'AMD Ryzen 7 7800X3D',
        'Procesador Gaming de 8 núcleos y 16 hilos con tecnología 3D V-Cache.',
        449.99,
        50,
        'https://example.com/images/7800x3d.jpg',
        'CPU'
    ],
    [
        'GPU-NV-RTX4090',
        'NVIDIA GeForce RTX 4090',
        'Tarjeta gráfica de última generación para gaming 4K y renderizado profesional.',
        1899.00,
        10,
        'https://example.com/images/rtx4090.jpg',
        'GPU'
    ],
    [
        'RAM-COR-DDR5-32GB',
        'Corsair Vengeance 32GB DDR5 6000MHz',
        'Kit de memoria RAM DDR5 de alto rendimiento para entusiastas.',
        129.95,
        100,
        'https://example.com/images/corsair-ddr5.jpg',
        'RAM'
    ],
    [
        'MOBO-ASUS-B650',
        'ASUS ROG Strix B650-E Gaming WiFi',
        'Placa base AM5 con soporte PCIe 5.0 y WiFi 6E.',
        289.50,
        25,
        'https://example.com/images/asus-b650.jpg',
        'Motherboard'
    ],
    [
        'SSD-SAM-990PRO-2TB',
        'Samsung 990 PRO 2TB NVMe',
        'SSD NVMe PCIe 4.0 ultrarrápido con velocidades de hasta 7450 MB/s.',
        179.99,
        75,
        'https://example.com/images/990pro.jpg',
        'Storage'
    ],
    [
        'PSU-COR-RM1000X',
        'Corsair RM1000x Shift',
        'Fuente de alimentación modular de 1000W con certificación 80 Plus Gold.',
        189.90,
        40,
        'https://example.com/images/rm1000x.jpg',
        'Power Supply'
    ],
    [
        'CASE-NZXT-H9',
        'NZXT H9 Flow',
        'Caja ATX de doble cámara con excelente flujo de aire y paneles de vidrio.',
        159.99,
        30,
        'https://example.com/images/h9flow.jpg',
        'Case'
    ],
    [
        'COOL-NOC-NHD15',
        'Noctua NH-D15 chromax.black',
        'Disipador por aire de doble torre, rendimiento premium y silencioso.',
        119.90,
        60,
        'https://example.com/images/nhd15.jpg',
        'Cooling'
    ]
];

// Add data starting from row 2
$sheet->fromArray($data, NULL, 'A2');

// Auto-size columns
foreach (range('A', 'G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$outputPath = __DIR__ . '/../productos_importar.xlsx';
$writer->save($outputPath);

echo "Excel file created at: " . realpath($outputPath) . "\n";
