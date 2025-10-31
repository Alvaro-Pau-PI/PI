<?php
// backend/auth/register.php

// 1. Requisit previ: Incloure la funció de connexió a JSON Server i iniciar la sessió
require_once '../includes/json_connect.php'; 
session_start();

$errors = [];
$nom_usuari = $email = $nom = $cognoms = ''; 
// Les variables $nom_usuari, $email, etc., es guarden per repoblar el formulari en cas d'error.

// 2. Processament del formulari (Només si s'ha enviat via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Neteja i obtenció de dades
    $nom_usuari = trim($_POST['nom_usuari'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';
    $contrasenya_confirm = $_POST['contrasenya_confirm'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $cognoms = trim($_POST['cognoms'] ?? '');

    // 3. Validació bàsica de dades
    if (empty($nom_usuari) || empty($email) || empty($contrasenya) || empty($contrasenya_confirm)) {
        $errors[] = "Tots els camps amb * són obligatoris.";
    }

    if ($contrasenya !== $contrasenya_confirm) {
        $errors[] = "Les contrasenyes no coincideixen.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El format de l'email no és vàlid.";
    }
    
    if (strlen($contrasenya) < 6) {
        $errors[] = "La contrasenya ha de tenir almenys 6 caràcters.";
    }


    // 4. Validació de duplicats al JSON Server
    if (empty($errors)) {
        
        // Comprovar nom d'usuari duplicat (GET /usuaris?nom_usuari=...)
        $existing_user_by_name = get_users_by_field('nom_usuari', $nom_usuari);
        if ($existing_user_by_name !== null && !empty($existing_user_by_name)) {
            $errors[] = "El nom d'usuari '{$nom_usuari}' ja està registrat.";
        }

        // Comprovar email duplicat (GET /usuaris?email=...)
        $existing_user_by_email = get_users_by_field('email', $email);
        if ($existing_user_by_email !== null && !empty($existing_user_by_email)) {
            $errors[] = "L'adreça de correu electrònic '{$email}' ja està registrada.";
        }
    }

    // 5. Creació i enviament si no hi ha errors
    if (empty($errors)) {
        
        // Xifrat de la contrasenya amb password_hash()
        $hashed_password = password_hash($contrasenya, PASSWORD_DEFAULT);
        
        // Dades a enviar al JSON Server
        $user_data = [
            "nom_usuari" => $nom_usuari,
            "contrasenya" => $hashed_password, // S'emmagatzema el hash, NO el text pla
            "email" => $email,
            "nom" => $nom,
            "cognoms" => $cognoms,
            "data_registre" => date('c') // Format ISO 8601 (Ex: 2025-10-31T10:00:00+01:00)
        ];

        // Petició POST al JSON Server (create_user utilitza api_request POST)
        $new_user = create_user($user_data);

        if ($new_user) {
            // Si el registre és correcte, redirigim a la pàgina d'inici de sessió
            // El paràmetre 'registered=1' servirà per mostrar un missatge d'èxit.
            header("Location: login.php?registered=1");
            exit;
        } else {
            // Error de comunicació amb el servidor o un error de JSON Server
            $errors[] = "Error al connectar o registrar l'usuari al servidor de dades. Si us plau, revisa que el JSON Server estigui actiu i la connexió PHP sigui correcta.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Registre d'Usuari</title>
    <style>
        .error { color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; }
        .success { color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="email"], input[type="password"] { width: 300px; padding: 8px; margin-top: 5px; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>

    <h1>📝 Registre d'Usuari</h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <p>S'han trobat els següents errors:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        
        <label for="nom_usuari">Nom d'Usuari *:</label>
        <input type="text" id="nom_usuari" name="nom_usuari" value="<?= htmlspecialchars($nom_usuari) ?>" required>

        <label for="email">Email *:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        
        <label for="contrasenya">Contrasenya *:</label>
        <input type="password" id="contrasenya" name="contrasenya" required>

        <label for="contrasenya_confirm">Confirma Contrasenya *:</label>
        <input type="password" id="contrasenya_confirm" name="contrasenya_confirm" required>
        
        <hr>

        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>">

        <label for="cognoms">Cognoms:</label>
        <input type="text" id="cognoms" name="cognoms" value="<?= htmlspecialchars($cognoms) ?>">
        <br>
        
        <button type="submit" style="margin-top: 20px;">Registrar-se</button>
    </form>
    
    <p style="margin-top: 30px;">Ja tens un compte? <a href="login.php">Inicia sessió aquí</a>.</p>

</body>
</html>