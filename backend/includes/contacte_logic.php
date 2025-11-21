<?php
// backend/includes/contacte_logic.php

$errors = [];
$nom = '';
$email = '';
$assumpte = '';
$missatge = '';
$enviat = false;

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

    // 3. Si todo está bien
    if (empty($errors)) {
        $enviat = true;
        // Aquí iría el código para enviar el mail o guardar en BBDD
    }
}
?>