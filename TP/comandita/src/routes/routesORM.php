<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\ORM\usuario;
use App\Models\ORM\usuarioControler;
use App\Models\ORM\mesa;
use App\Models\ORM\mesaControler;
use App\Models\ORM\Middleware;

include_once __DIR__ . '/../../src/app/modelORM/usuario.php';
include_once __DIR__ . '/../../src/app/modelORM/usuarioControler.php';
include_once __DIR__ . '/../../src/app/modelORM/mesa.php';
include_once __DIR__ . '/../../src/app/modelORM/mesaControler.php';
include_once __DIR__ . '/../../src/app/middleware/middlewareControler.php';

return function (App $app) {
    $container = $app->getContainer();

    //ABM USUARIOS
    $app->group('/comanditaORM/usuarios', function () {   
      
      $this->post('/crearUsuario', usuarioControler::class . ':CargarUno');
      $this->post('/loginUsuario', usuarioControler::class . ':loginUsuario'); 
      //solo los usuarios SOCIO y ADMIN, puede modificar y modificar un perfil
      $this->post('/borrarUsuario', usuarioControler::class . ':BorrarUno')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/modificarUsuario/{nombre_usuario}', usuarioControler::class . ':ModificarUno')->add(Middleware::class . ':ValidarSuperSU');
    });

    
    //ABM MESAS
    $app->group('/comanditaORM/mesas', function () {   
      
      //solo cargar el token de un ADMIN o SOCIO
      $this->post('/cargar', mesaControler::class . ':CargarUna')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/eliminar/{idMesa}', mesaControler::class . ':BorrarMesa')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/modificar/{idMesa}', mesaControler::class . ':ModificarMesa')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/cerrar/{idMesa}', mesaControler::class . ':CerrarMesa')->add(Middleware::class . ':validarUsuarioSocio');//socio
    });
    
    // AMB PEDIDOS
    $app->group('/comanditaORM/pedidos', function () {
  
      $this->post('/cargar', pedidoControler::class . ':CargarUno')->add(Middleware::class . ':ValidarMozo');
  
    });   
  };