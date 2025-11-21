<?php
// backend/auth/logout.php

// 1. Iniciar la sessió
session_start();

// 2. Eliminar la Cookie 'user_id' (o qualsevol cookie que utilitzis per a l'autenticació)
if (isset($_COOKIE['user_id'])) {
    // El camí ('/') ha de coincidir amb el camí utilitzat en setcookie() de login.php
    setcookie('user_id', '', time() - 3600, "/"); 
}

// 3. Netejar les variables de sessió
$_SESSION = array();

// 4. Destruir la sessió
session_destroy();

// 5. Redirigir a la pàgina d'inici de sessió i afegir un paràmetre d'èxit de logout
header("Location: login.php?logged_out=1");
exit;

?>