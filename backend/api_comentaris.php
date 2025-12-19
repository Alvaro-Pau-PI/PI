<?php
// backend/api_comentaris.php
require_once 'includes/json_connect.php';
session_start();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// Helper para enviar respuesta JSON y salir
function json_response($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// Verificar autenticación
function get_current_user_id() {
    return $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
}

if ($method === 'GET') {
    // --- OBTENER COMENTARIOS ---
    $product_id = $_GET['product_id'] ?? null;
    
    if (!$product_id) {
        json_response(['error' => 'Falta product_id'], 400);
    }

    $comments = get_comments_by_product($product_id);
    
    // Enriquecer comentarios con nombres de usuario y permisos
    foreach ($comments as &$c) {
        $user = !empty($c['user_id']) ? get_user_by_id($c['user_id']) : null;
        $c['user_name'] = $user['nom_usuari'] ?? 'Usuari desconegut';
        
        $current_user_id = get_current_user_id();
        $is_owner = $current_user_id === $c['user_id'];
        
        $is_admin = false;
        if ($current_user_id) {
            $current_user = get_user_by_id($current_user_id);
            $is_admin = ($current_user['rol'] ?? '') === 'admin';
        }
        
        $c['can_delete'] = $is_owner || $is_admin;
    }

    json_response($comments);

} elseif ($method === 'POST') {
    // --- PUBLICAR NUEVO COMENTARIO ---
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        json_response(['error' => 'No autenticat'], 401);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    
    $product_id = $input['product_id'] ?? null;
    $text = trim($input['text'] ?? '');
    $rating = (int)($input['rating'] ?? 0);

    if (!$product_id || empty($text) || $rating < 1 || $rating > 5) {
        json_response(['error' => 'Dades invàlides'], 400);
    }

    $new_comment = [
        'id' => uniqid('com_'),
        'product_id' => $product_id,
        'user_id' => $user_id,
        'text' => htmlspecialchars($text),
        'rating' => $rating,
        'data' => date('c') // Fecha actual ISO 8601
    ];

    // Guardar en JSON
    $saved = add_comment($new_comment);
    
    if ($saved) {
        // Preparar respuesta para el frontend
        $response_data = $new_comment;
        
        // Obtener nombre del usuario actual para mostrarlo ya
        $user = get_user_by_id($user_id);
        $response_data['user_name'] = $user['nom_usuari'] ?? 'Jo';
        $response_data['can_delete'] = true;
        
        json_response($response_data, 201);
    } else {
        // WORKAROUND: Si falla (probablemente EBUSY), comprobamos si realmente se guardó
        // Esperamos un poco para que el sistema de archivos se asiente
        usleep(100000); // 100ms
        
        $current_comments = get_comments_by_product($product_id);
        $found = false;
        foreach ($current_comments as $c) {
            if ($c['id'] === $new_comment['id']) {
                $found = true;
                break;
            }
        }
        
        if ($found) {
             // Se guardó a pesar del error
            $response_data = $new_comment;
            $user = get_user_by_id($user_id);
            $response_data['user_name'] = $user['nom_usuari'] ?? 'Jo';
            $response_data['can_delete'] = true;
            
            json_response($response_data, 201);
        } else {
            json_response(['error' => 'Error al guardar en la base de datos'], 500);
        }
    }

} elseif ($method === 'DELETE') {
    // --- BORRAR COMENTARIO ---
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        json_response(['error' => 'No autenticat'], 401);
    }

    $comment_id = $_GET['id'] ?? null;

    if (!$comment_id) {
        json_response(['error' => 'Falta id del comentario'], 400);
    }

    // Buscar el comentario para verificar permisos
    $target_comment = get_comment_by_id($comment_id);
    
    if (!$target_comment) {
        json_response(['error' => 'Comentari no trobat'], 404);
    }

    $current_user = get_user_by_id($user_id);
    $is_admin = ($current_user['rol'] ?? '') === 'admin';
    $is_owner = $target_comment['user_id'] === $user_id;

    if (!$is_owner && !$is_admin) {
        json_response(['error' => 'No tens permís'], 403);
    }

    $result = delete_comment($comment_id);
    
    if ($result) {
        json_response(['success' => true]);
    } else {
        // WORKAROUND: Verificar si se borró realmente (EBUSY)
        usleep(100000); // 100ms
        $check = get_comment_by_id($comment_id);
        
        if (!$check) {
            // Ya no existe, éxito
            json_response(['success' => true]);
        } else {
            json_response(['error' => 'Error al esborrar'], 500);
        }
    }

} else {
    json_response(['error' => 'Mètode no permès'], 405);
}