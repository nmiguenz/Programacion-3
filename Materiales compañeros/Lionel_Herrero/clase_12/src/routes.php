<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\usuario;
use App\Models\usuarioControler;



return function (App $app) {
    $container = $app->getContainer();

    // Rutas ORM
    $routes = require __DIR__ . '/../src/routes/routesORM.php';
    $routes($app);

};
