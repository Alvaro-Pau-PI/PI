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
        $img = htmlspecialchars($p['img'] ?? 'img/LOGO AlberoPerezTech.png'); 
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

// FIX: Adjust paths for CSS and Images because we are in backend/ but serving frontend/index.html
$html_content = str_replace('href="styles.css"', 'href="../styles.css"', $html_content);
$html_content = str_replace('src="img/', 'src="../img/', $html_content);

// Inject n8n Chatbot
$n8n_css = '<link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />
<style>
  :root {
    --chat--color--primary: #e67e22 !important;
    --chat--toggle--background-color: #e67e22 !important;
    --chat--toggle--hover--background-color: #d35400 !important;
    --chat--header--background-color: #e67e22 !important;
    --chat--header--color: #FFFFFF !important;
  }
  .n8n-chat-widget-toggle {
    background-color: #e67e22 !important;
    width: 64px !important;
    height: 64px !important; 
  }
  .n8n-chat-widget-header,
  .chat-header,
  .n8n-chat-header {
    background-color: #e67e22 !important;
    color: #FFFFFF !important;
  }
  .n8n-chat-widget-header *,
  .chat-header *,
  .n8n-chat-header * {
    color: #FFFFFF !important;
  }
  /* Force user message colors */
  :root {
    --chat--message--user--background-color: #e67e22 !important;
    --chat--message--user--color: #FFFFFF !important;
  }
  .chat-message-user,
  .n8n-chat-message-user {
    background-color: #e67e22 !important;
    color: #FFFFFF !important;
  }
  /* Ajuste para móviles */
  @media (max-width: 480px) {
    :root {
      --chat--window--width: 100vw !important;
      --chat--window--height: 100vh !important;
    }
    .n8n-chat-widget {
      width: 100vw !important;
      height: 100vh !important;
      max-width: 100vw !important;
      max-height: 100vh !important;
      bottom: 0 !important;
      right: 0 !important;
      border-radius: 0 !important;
    }
  }
</style>';
$n8n_script = "
<script type='module'>
    import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

    createChat({
        webhookUrl: 'http://localhost:5678/webhook/1bf7c92c-ec5d-41dd-88f7-73d2bfdd4914/chat',
        initialMessages: [
            '¡Hola! 👋',
            '¿En qué puedo ayudarte hoy?'
        ],
        i18n: {
            en: {
                title: '💻AlberoPerezTech BOT',
                subtitle: 'Tu experto tecnológico 24/7 🚀',
                footer: '',
                getStarted: 'Empezar chat',
                inputPlaceholder: 'Escribe tu pregunta...',
            },
        },
        style: {
            '--chat--message--user--color': '#FFFFFF',
            '--chat--message--user--background-color': '#d35400',
            '--chat--color--primary': '#e67e22',
            '--chat--toggle--background-color': '#e67e22ff',
            '--chat--toggle--hover--background-color': '#d35400',
            '--chat--toggle--size': '64px',
        },
    });
</script>";

$html_content = str_replace('</head>', $n8n_css . '</head>', $html_content);
$html_content = str_replace('</body>', $n8n_script . '</body>', $html_content);

echo $html_content;
?>