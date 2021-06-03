<?php

$app->get('/migrations/{table}', function ($request, $response, $args) {
    $table = $args['table'] ?? '';
    $msg = ''; 

    try {
        $instance = \App\Factory\DabaseStructure::create('Migrations', $table);
        if ($instance) {
            $instance->up();

            $msg = "$table, Up in database.";
        }
    } catch (Exception $e) {
        $msg = $e->getMessage(); 
    }

    $response->getBody()->write($msg);
    return $response;
});

$app->get('/seeders/{table}', function ($request, $response, $args) {
    $table = $args['table'] ?? '';    
    $msg = '';

    try {
        $instance = \App\Factory\DabaseStructure::create('Seeders', $table);
        if ($instance) {
            $instance->run();

            $msg = "$table, inserted seeders.";
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
    }

    $response->getBody()->write($msg);
    return $response;
});

$app->get('/', function ($request, $response, $args) {
    $products = \App\Models\Product::all()->toArray();
    return $this->get('view')->render($response, '/templates/index.phtml', [
        'title' => 'Home',
        'products' => $products
    ]);
});