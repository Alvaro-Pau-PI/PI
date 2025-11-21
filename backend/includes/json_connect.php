<?php

/**
 * URL base del JSON Server.
 * El nom 'jsonserver' ha de coincidir amb el nom del servei al docker-compose.yml.
 */
const JSON_SERVER_URL = 'http://jsonserver:8080';

/**
 * Funció genèrica per realitzar peticions HTTP al JSON Server.
 *
 * @param string $endpoint Ex: '/usuaris', '/usuaris/1'
 * @param string $method Ex: 'GET', 'POST', 'PATCH', 'DELETE'
 * @param array|null $data Dades a enviar per POST/PATCH
 * @return array|null Resposta descodificada com a array associatiu, o null en cas d'error
 */
function api_request(string $endpoint, string $method = 'GET', ?array $data = null): ?array {
    
    $url = JSON_SERVER_URL . $endpoint;
    $ch = curl_init($url);
    
    // Configuració bàsica de cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);

    // Configuració del mètode (POST, PATCH, GET)
    switch (strtoupper($method)) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'PATCH':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'GET':
            // No cal configuració addicional, és el mètode per defecte
            break;
        // Altres mètodes com DELETE es poden afegir si cal
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $http_code >= 400) {
        // En cas d'error, podem registrar-lo i retornar null
        error_log("API Error ({$method} {$url}): HTTP {$http_code} - " . ($response === false ? 'cURL Error' : $response));
        return null;
    }

    // Descodificar la resposta JSON
    return json_decode($response, true);
}

/**
 * Funcions d'accés ràpid per a endpoints d'usuaris
 */

/** Obté un usuari per ID (GET /usuaris/1) */
// --- LÍNIA CORREGIDA ---
function get_user_by_id(string $id): ?array {
    return api_request("/usuaris/{$id}");
}

/** Obté usuaris per camp (GET /usuaris?nom_usuari=...) */
function get_users_by_field(string $field, string $value): ?array {
    // Retorna un array amb tots els usuaris que coincideixen amb el filtre
    $response = api_request("/usuaris?{$field}=" . urlencode($value));
    return is_array($response) ? $response : null;
}

/** Afegeix un nou usuari (POST /usuaris) */
function create_user(array $userData): ?array {
    return api_request('/usuaris', 'POST', $userData);
}

/** Actualitza dades de l'usuari (PATCH /usuaris/1) */
// --- LÍNIA CORREGIDA (Per a futures edicions de perfil) ---
function update_user(string $id, array $updateData): ?array {
    return api_request("/usuaris/{$id}", 'PATCH', $updateData);
}

?>