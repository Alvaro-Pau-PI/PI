<?php
// backend/index.php
require_once 'includes/json_connect.php';
session_start();

// 1. LÒGICA D'USUARI
$is_logged_in = isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id']);
$user_icon_href = $is_logged_in ? 'auth/profile.php' : 'auth/register.php';
$user_icon_title = $is_logged_in ? 'El meu perfil' : 'Registra\'t o Inicia sessió';

// 2. LÒGICA DE PRODUCTES (BUSCADOR)
$search_query = $_GET['q'] ?? ''; 
// Llamamos a la API filtrando por 'q' si existe
$products = get_products($search_query);

// 3. GENERAR HTML
$products_html = '';

if (empty($products)) {
    $products_html = '<p style="grid-column: 1 / -1; text-align: center; color: #9BA3B0;">No s\'han trobat productes.</p>';
} else {
    foreach ($products as $p) {
        $id = $p['id'] ?? 0;
        $nom = htmlspecialchars($p['nom'] ?? 'Sense nom');
        $preu = htmlspecialchars($p['preu'] ?? '0');
        $img = htmlspecialchars($p['img'] ?? 'img/placeholder.png'); 
        $desc = htmlspecialchars($p['descripcio'] ?? '');

        // Generamos la tarjeta con enlace al detalle
        $products_html .= "
        <article class='producto'>
            <a href='producte.php?id={$id}' style='text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;'>
                <img src='{$img}' alt='{$nom}'>
                <span class='precio'>{$preu}€</span>
                <h3 class='nombre'>{$nom}</h3>
                <p class='detalle'>{$desc}</p>
            </a>
        </article>";
    }
}

// 4. CARREGAR VISTA
$html_path = '../frontend/index.html';

if (!file_exists($html_path)) {
    die("Error: No es troba frontend/index.html");
}

$html_content = file_get_contents($html_path);

// 5. SUBSTITUCIONS
// Reemplazamos los placeholders por los datos reales
$html_content = str_replace('[[USER_ICON_HREF]]', $user_icon_href, $html_content);
$html_content = str_replace('[[USER_ICON_TITLE]]', $user_icon_title, $html_content);
$html_content = str_replace('[[PRODUCT_LIST]]', $products_html, $html_content);
$html_content = str_replace('[[SEARCH_QUERY]]', htmlspecialchars($search_query), $html_content);

echo $html_content;
?>