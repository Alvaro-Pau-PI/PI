<?php
const JSON_SERVER_URL = 'http://jsonserver:3000';

function api_request(string $endpoint, string $method = 'GET', ?array $data = null): ?array {
    $url = JSON_SERVER_URL . $endpoint;
    $ch = curl_init($url);
    
    // Configuración para que funcione dentro de Docker
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    // Fix para DNS si fuera necesario
    // curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'PATCH') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        file_put_contents('debug_log.txt', date('Y-m-d H:i:s') . " - CURL ERROR: $curl_error\n", FILE_APPEND);
        return null; 
    }
    
    $decoded = json_decode($response, true);
    if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
        file_put_contents('debug_log.txt', date('Y-m-d H:i:s') . " - JSON DECODE ERROR: " . json_last_error_msg() . "\nResponse: $response\n", FILE_APPEND);
    }

    return $decoded;
}

// --- USUARIOS ---
function get_users_by_field(string $field, string $value): ?array {
    return api_request("/usuaris?{$field}=" . urlencode($value));
}
function create_user(array $userData): ?array {
    return api_request('/usuaris', 'POST', $userData);
}
function get_user_by_id(string $id): ?array {
    return api_request("/usuaris/{$id}");
}

// --- PRODUCTOS (NUEVO) ---

/** Obtener productos (con buscador opcional) */
function get_products(?string $query = null): array {
    $endpoint = '/productes';
    if ($query) {
        // 'q' es el filtro global de json-server
        $endpoint .= '?q=' . urlencode($query);
    }
    $result = api_request($endpoint);
    return is_array($result) ? $result : [];
}

/** Obtener un producto por ID */
function get_product_by_id(string $id): ?array {
    return api_request("/productes/{$id}");
}

// --- COMENTARIOS ---

function get_comments_by_product(string $product_id): array {
    // Filtramos por product_id y ordenamos por fecha descendente (si json-server lo soporta, sino en PHP)
    // json-server: ?product_id=...&_sort=data&_order=desc
    return api_request("/comentaris?product_id=" . urlencode($product_id) . "&_sort=data&_order=desc") ?? [];
}

function get_comment_by_id(string $id): ?array {
    return api_request("/comentaris/{$id}");
}

function add_comment(array $data): ?array {
    return api_request('/comentaris', 'POST', $data);
}

function delete_comment(string $id): ?array {
    return api_request("/comentaris/{$id}", 'DELETE'); // DELETE method needs implementation in api_request if not present
}

// Helper para borrar (json-server usa DELETE)
function api_delete(string $endpoint): ?array {
     $url = JSON_SERVER_URL . $endpoint;
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $response = curl_exec($ch);
     curl_close($ch);
     return json_decode($response, true);
}