<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\ORM\usuario;
use App\Models\ORM\usuarioControler;


include_once __DIR__ . '/../../src/app/modelORM/usuario.php';
include_once __DIR__ . '/../../src/app/modelORM/usuarioControler.php';
include_once __DIR__ . '/../../src/middlewares/middlewaresRoutes.php';


return function (App $app) {
    $container = $app->getContainer();

     $app->group('/usuarioORM', function () {   

        $this->get('/',usuarioControler::class . ':traerTodos');

        $this->post('/registroUsuario', usuarioControler::class . ':cargarUno');
        
        $this->post('/login', usuarioControler::class . ':loginUsuario')->add(Middleware::class . ':validarUsuario');
     
    });

};