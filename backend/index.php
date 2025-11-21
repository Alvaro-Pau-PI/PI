<?php
// backend/index.php
// Aquest fitxer actua com a controlador per la pàgina principal.

require_once 'includes/json_connect.php'; 
session_start(); 

// 1. LÒGICA: Comprovació d'Autenticació
$is_logged_in = isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id']);

// Definim els valors dinàmics a injectar
$user_icon_href = $is_logged_in ? 'auth/profile.php' : 'auth/register.php';
$user_icon_title = $is_logged_in ? 'El meu perfil' : 'Registra\'t o Inicia sessió';

// 2. VISTA: Carregar el fitxer HTML estàtic del frontend
$html_path = '../frontend/index.html'; // Ruta relativa des de backend/ a frontend/index.html

if (!file_exists($html_path)) {
    die("Error 500: No es pot carregar el fitxer de disseny (frontend/index.html).");
}
$html_content = file_get_contents($html_path);

// 3. INJECCIÓ: Substituïm els placeholders del HTML
$html_content = str_replace('[[USER_ICON_HREF]]', $user_icon_href, $html_content);
$html_content = str_replace('[[USER_ICON_TITLE]]', $user_icon_title, $html_content);

// 4. OUTPUT: Enviem el contingut processat al navegador
echo $html_content;

?>