<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\ORM\usuario;
use App\Models\ORM\usuarioControler;


include_once __DIR__ . '/../../src/app/modelORM/usuario.php';
include_once __DIR__ . '/../../src/app/modelORM/usuarioControler.php';
include_once __DIR__ . '/../../src/middleware/middlewareRoutes.php';

return function (App $app) {
    $container = $app->getContainer();

     $app->group('/usuarioORM', function () {   
         
        //para pegarle al postman hay que [toda la ruta]/public/usuarioORM/registroUsuario
        $this->post('/registroUsuario', usuarioControler::class . ':cargarUno');

        $this->post('/login', usuarioControler::class. ':loginUsuario')->add(Middleware::class. ':validarUsuario');

        $this->get('/', function ($request, $response, $args) {
          //return cd::all()->toJson();
          $todosLosCds=usuario::all();
          $newResponse = $response->withJson($todosLosCds, 200);  
          return $newResponse;
        });
    });


     $app->group('/cdORM2', function () {   

        $this->get('/',cdApi::class . ':traerTodos');
   
    });

};