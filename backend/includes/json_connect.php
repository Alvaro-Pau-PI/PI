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
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) return null; // Error de conexión
    
    return json_decode($response, true);
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
?>