<?php
// backend/auth/profile.php

// 1. Requisits: Incloure la connexió a JSON Server i iniciar la sessió
require_once '../includes/json_connect.php';
session_start();

$user_data = null;
$user_id = null;

// 2. Comprovació d'Autenticació
// Prioritzem la cookie, ja que és el mecanisme d'autenticació establert al login.
if (isset($_COOKIE['user_id']) && is_numeric($_COOKIE['user_id'])) {
    $user_id = (int)$_COOKIE['user_id'];
    
    // 3. Obtenir les dades de l'usuari del JSON Server (GET /usuaris/{id})
    $user_data = get_user_by_id($user_id);
    
    // Si la ID existeix a la cookie però no al servidor (eliminat, per exemple)
    if (!$user_data) {
        // Forçar tancament de sessió
        setcookie('user_id', '', time() - 3600, "/"); 
        $user_id = null;
    }
}

// Si la cookie no existeix o la verificació ha fallat (usuari = null), redirigir al login
if (!$user_id) {
    // Redirigir amb un missatge d'error
    header("Location: login.php?error=not_logged_in");
    exit;
}

// Si arribem aquí, $user_data conté la informació de l'usuari.

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Perfil d'Usuari</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .info-box { border: 1px solid #ccc; padding: 15px; border-radius: 5px; max-width: 400px; }
        .info-box p { margin: 5px 0; }
        .logout-link { margin-top: 20px; display: inline-block; padding: 8px 15px; background-color: #f44336; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>

    <h1>👤 Benvingut al teu Perfil, <?= htmlspecialchars($user_data['nom_usuari'] ?? 'Usuari') ?>!</h1>
    
    <p>Aquesta és una pàgina protegida. La teva autenticació és correcta.</p>

    <div class="info-box">
        <h2>Detalls de l'Usuari</h2>
        <p><strong>ID:</strong> <?= htmlspecialchars($user_data['id'] ?? 'N/A') ?></p>
        <p><strong>Nom d'Usuari:</strong> <?= htmlspecialchars($user_data['nom_usuari'] ?? 'N/A') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user_data['email'] ?? 'N/A') ?></p>
        <p><strong>Nom Complet:</strong> <?= htmlspecialchars(($user_data['nom'] ?? '') . ' ' . ($user_data['cognoms'] ?? '')) ?></p>
        <p><strong>Membre des de:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($user_data['data_registre'] ?? ''))) ?></p>
    </div>

    <a href="logout.php" class="logout-link">🚪 Tancar Sessió</a>

</body>
</html>