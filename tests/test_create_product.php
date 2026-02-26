<?php
$user = App\Models\User::where('role', 'admin')->first();
$request = Request::create('/api/products', 'POST', [
    'name' => 'Test GPU',
    'description' => 'Test Desc',
    'price' => 500.00,
    'stock' => 10,
    'category' => 'Targetes Gràfiques'
]);
$request->setUserResolver(function() use ($user) { return $user; });

$controller = app(App\Http\Controllers\ProductController::class);
$response = $controller->store($request);
echo $response->getContent();
