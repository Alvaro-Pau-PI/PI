<?php
// backend/auth/login.php

// 1. Requisits previs: Incloure la funció de connexió a JSON Server i iniciar la sessió
require_once '../includes/json_connect.php';
session_start();

// Redirigir si l'usuari ja està autenticat (basat en la cookie)
if (isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
    header("Location: profile.php");
    exit;
}

$errors = [];
$nom_usuari = '';
$login_success = false;

// 2. Processament de l'inici de sessió (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Neteja i obtenció de dades
    $nom_usuari = trim($_POST['nom_usuari'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';

    // Validació bàsica
    if (empty($nom_usuari) || empty($contrasenya)) {
        $errors[] = "Si us plau, introdueix el nom d'usuari i la contrasenya.";
    }

    if (empty($errors)) {
        
        // 3. Cerca de l'usuari al JSON Server (GET /usuaris?nom_usuari=...)
        $users_found = get_users_by_field('nom_usuari', $nom_usuari);
        
        // Comprovar si hi ha usuaris trobats i prendre el primer (JSON Server pot retornar un array)
        $user = (!empty($users_found) && is_array($users_found)) ? $users_found[0] : null;

        if ($user) {
            
            // 4. Validar la contrasenya xifrada amb password_verify()
            if (password_verify($contrasenya, $user['contrasenya'])) {
                
                // --- Autenticació reeixida ---
                
                // **Bona Pràctica de Seguretat:** Regenerar l'ID de sessió per prevenir Session Fixation
                session_regenerate_id(true); 

                // 5. Crear la Cookie d'identificació i la sessió
                $user_id = $user['id'];
                
                // Configuració de la Cookie: només ID, vàlida per 1 hora (3600s), en tot el domini (/)
                // Idealment, s'haurien d'afegir flags com HttpOnly i Secure per a producció
                setcookie('user_id', $user_id, time() + (3600 * 24 * 7), "/", "", false, true); // Vigent per 1 setmana
                
                // Guardar dades de sessió addicionals si cal
                $_SESSION['user_id'] = $user_id; 

                // 6. Redirigir a la pàgina de perfil (o a la pàgina protegida)
                header("Location: profile.php");
                exit;

            } else {
                // Contrasenya incorrecta
                $errors[] = "Nom d'usuari o contrasenya incorrectes.";
            }

        } else {
            // Usuari no trobat
            $errors[] = "Nom d'usuari o contrasenya incorrectes.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Inici de Sessió</title>
    <style>
        .error { color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; }
        .success { color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="email"], input[type="password"] { width: 300px; padding: 8px; margin-top: 5px; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>

    <h1>🔑 Inici de Sessió</h1>

    <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
        <div class="success">
            <p>✅ Registre completat amb èxit! Ja pots iniciar sessió.</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <p>Error en iniciar sessió:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        
        <label for="nom_usuari">Nom d'Usuari:</label>
        <input type="text" id="nom_usuari" name="nom_usuari" value="<?= htmlspecialchars($nom_usuari) ?>" required>
        
        <label for="contrasenya">Contrasenya:</label>
        <input type="password" id="contrasenya" name="contrasenya" required>
        <br>
        
        <button type="submit" style="margin-top: 20px;">Iniciar Sessió</button>
    </form>
    
    <p style="margin-top: 30px;">Encara no tens un compte? <a href="register.php">Registra't aquí</a>.</p>

</body>
</html>