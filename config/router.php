<?php

$app->get('/migrate/{table}', function ($request, $response, $args) {
    $namespace = "App\\Database\\Migrations\\";
    $table = ucfirst($args['table']);
    $file = "Create%sTable";

    $class = $namespace . sprintf($file, $table);
    if (class_exists($class)) {
        $create = new $class();
        $create->up();

        $response->getBody()->write("$table, Up in database.");
    }
        
    return $response;
});

$app->get('/', function ($request, $response, $args) {
    return $this->get('view')->render($response, '/templates/index.phtml', [
        'title' => 'Home'
    ]);
});