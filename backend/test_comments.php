<?php
require_once 'includes/json_connect.php';

echo "Testing GET /comentaris...\n";
$result = api_request('/comentaris');
if ($result === null) {
    echo "Result is NULL (Connection error or 404)\n";
} else {
    echo "Result is array (Success). Count: " . count($result) . "\n";
    print_r($result);
}
?>
