<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\ORM\usuario;
use App\Models\ORM\usuarioControler;
use App\Models\ORM\materia;
use App\Models\ORM\materiaControler;
use App\Models\ORM\middleware;


include_once __DIR__ . '/../../src/app/modelORM/usuario.php';
include_once __DIR__ . '/../../src/app/modelORM/usuarioControler.php';
include_once __DIR__ . '/../../src/app/middleware/middlewareControler.php';
include_once __DIR__ . '/../../src/app/modelORM/materia.php';
include_once __DIR__ . '/../../src/app/modelORM/materiaControler.php';


return function (App $app) {
    $container = $app->getContainer();

     $app->group('/usuarioORM', function () {   

        $this->post('/crearUsuario', usuarioControler::class . ':cargarUno');

        $this->post('/loginUsuario', usuarioControler::class . ':LoginUsuario');

        $this->post('/cargarMateria', materiaControler::class . ':CargarUno')->add(Middleware::class . ':validarUsuario');
         
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