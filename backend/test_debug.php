<?php
require_once 'includes/json_connect.php';

function debug_request($endpoint) {
    echo "Testing $endpoint...\n";
    $url = JSON_SERVER_URL . $endpoint;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "HTTP Code: $http_code\n";
    if ($error) echo "Curl Error: $error\n";
    echo "Response: " . substr($response, 0, 100) . "...\n\n";
}

debug_request('/productes');
debug_request('/comentaris');
?>
