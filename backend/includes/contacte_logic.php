<?php

$errors = [];
$nom = '';
$email = '';
$assumpte = '';
$missatge = '';
$enviat = false;

// Ruta al fitxer de base de dades JSON (igual que en importar_excel.php)
$jsonFilePath = '/var/www/data/db.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanear inputs
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $assumpte = trim($_POST['assumpte'] ?? '');
    $missatge = trim($_POST['missatge'] ?? '');

    // 2. Validaciones (Servidor)
    if ($nom === '') {
        $errors['nom'] = '⚠ El nombre es obligatorio.';
    }

    if ($email === '') {
        $errors['email'] = '⚠ El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = '⚠ Formato de email inválido.';
    }

    if ($assumpte === '') {
        $errors['assumpte'] = '⚠ El asunto es obligatorio.';
    }

    if ($missatge === '') {
        $errors['missatge'] = '⚠ El mensaje no puede estar vacío.';
    } elseif (strlen($missatge) < 10) {
        $errors['missatge'] = '⚠ Explícate un poco mejor (mínimo 10 caracteres).';
    }

    // 3. Si todo está bien, GUARDAMOS EN JSON
    if (empty($errors)) {
        
        // Llegir el contingut actual del db.json
        $currentData = [];
        if (file_exists($jsonFilePath)) {
            $jsonContent = file_get_contents($jsonFilePath);
            $currentData = json_decode($jsonContent, true);
            if (!$currentData) {
                $currentData = []; // Si està buit o corrupte, iniciem array buit
            }
        }

        // Assegurar que existeix la secció 'missatges'
        if (!isset($currentData['missatges'])) {
            $currentData['missatges'] = [];
        }

        // Crear el nou missatge
        $nouMissatge = [
            'id' => uniqid('msg_'), 
            'nom' => $nom,
            'email' => $email,
            'assumpte' => $assumpte,
            'missatge' => $missatge,
            'data' => date('c') 
        ];

        //Afegir-lo a l'array
        $currentData['missatges'][] = $nouMissatge;

        // Guardar el fitxer actualitzat
        if (file_put_contents($jsonFilePath, json_encode($currentData, JSON_PRETTY_PRINT))) {
            $enviat = true;
        } else {
            $errors['general'] = 'Error intern: No s\'ha pogut guardar el missatge. Revisa els permisos.';
        }
    }
}
?>