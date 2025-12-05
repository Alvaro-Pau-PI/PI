<?php
// backend/api_comentaris.php
require_once 'includes/json_connect.php';
session_start();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// Helper para enviar respuesta JSON
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
    // Obtener comentarios de un producto
    $product_id = $_GET['product_id'] ?? null;
    
    if (!$product_id) {
        json_response(['error' => 'Falta product_id'], 400);
    }

    $comments = get_comments_by_product($product_id);
    
    // Enriquecer comentarios con nombres de usuario
    // Esto es ineficiente (N+1), pero aceptable para prototipo. 
    // Idealmente json-server soporta _expand=user, pero nuestra estructura es plana.
    foreach ($comments as &$c) {
        $user = get_user_by_id($c['user_id']);
        $c['user_name'] = $user['nom_usuari'] ?? 'Usuari desconegut';
        
        // Determinar si el usuario actual puede borrar este comentario
        $current_user_id = get_current_user_id();
        $is_owner = $current_user_id === $c['user_id'];
        
        // Verificar si es admin
        $is_admin = false;
        if ($current_user_id) {
            $current_user = get_user_by_id($current_user_id);
            $is_admin = ($current_user['rol'] ?? '') === 'admin';
        }
        
        $c['can_delete'] = $is_owner || $is_admin;
    }

    json_response($comments);

} elseif ($method === 'POST') {
    // Crear nuevo comentario
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
        'data' => date('c') // ISO 8601
    ];

    $result = add_comment($new_comment);
    
    if ($result) {
        // Devolver el comentario creado con datos extra para el frontend
        $user = get_user_by_id($user_id);
        $result['user_name'] = $user['nom_usuari'] ?? 'Jo';
        $result['can_delete'] = true;
        json_response($result, 201);
    } else {
        json_response(['error' => 'Error al guardar'], 500);
    }

} elseif ($method === 'DELETE') {
    // Borrar comentario
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        json_response(['error' => 'No autenticat'], 401);
    }

    // Obtener ID del path o query string. 
    // Como no usamos router, lo pasamos por query string ?id=... o body.
    // Usemos query string para DELETE: api_comentaris.php?id=...
    $comment_id = $_GET['id'] ?? null;

    if (!$comment_id) {
        json_response(['error' => 'Falta id del comentario'], 400);
    }

    // Verificar permisos: obtener comentario para ver de quién es
    // json-server no tiene endpoint directo para "get comment by id" fácil si no es root, 
    // pero asumimos /comentaris/id funciona.
    $comment = api_request("/comentaris/{$comment_id}");
    
    if (!$comment) {
        json_response(['error' => 'Comentari no trobat'], 404);
    }

    $current_user = get_user_by_id($user_id);
    $is_admin = ($current_user['rol'] ?? '') === 'admin';
    $is_owner = $comment['user_id'] === $user_id;

    if (!$is_owner && !$is_admin) {
        json_response(['error' => 'No tens permís'], 403);
    }

    $result = delete_comment($comment_id);
    json_response(['success' => true]);

} else {
    json_response(['error' => 'Mètode no permès'], 405);
}
?>
