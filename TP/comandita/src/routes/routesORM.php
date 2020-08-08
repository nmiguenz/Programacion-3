<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\ORM\usuario;
use App\Models\ORM\usuarioControler;
use App\Models\ORM\mesa;
use App\Models\ORM\mesaControler;
use App\Models\ORM\comida;
use App\Models\ORM\comidaControler;
use App\Models\ORM\pedido;
use App\Models\ORM\pedidoControler;
use App\Models\ORM\cliente;
use App\Models\ORM\clienteControler;
use App\Models\ORM\Middleware;

include_once __DIR__ . '/../../src/app/modelORM/usuario.php';
include_once __DIR__ . '/../../src/app/modelORM/usuarioControler.php';
include_once __DIR__ . '/../../src/app/modelORM/mesa.php';
include_once __DIR__ . '/../../src/app/modelORM/mesaControler.php';
include_once __DIR__ . '/../../src/app/modelORM/comida.php';
include_once __DIR__ . '/../../src/app/modelORM/comidaControler.php';
include_once __DIR__ . '/../../src/app/modelORM/pedido.php';
include_once __DIR__ . '/../../src/app/modelORM/pedidoControler.php';
include_once __DIR__ . '/../../src/app/modelORM/cliente.php';
include_once __DIR__ . '/../../src/app/modelORM/clienteControler.php';
include_once __DIR__ . '/../../src/app/middleware/middlewareControler.php';


return function (App $app) {
    $container = $app->getContainer();

    //ABM USUARIOS
    $app->group('/comanditaORM/usuarios', function () {   
      
      $this->post('/loginUsuario', usuarioControler::class . ':loginUsuario'); 
      //solo los usuarios SOCIO y ADMIN, pueden borra y modificar un perfil
      $this->post('/crearUsuario', usuarioControler::class . ':CargarUno')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/borrarUsuario', usuarioControler::class . ':BorrarUno')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/modificarUsuario/{nombre_usuario}', usuarioControler::class . ':ModificarUno')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/suspenderEmpleado/{nombre_usuario}', usuarioControler::class . ':SuspenderEmpleado')->add(Middleware::class . ':ValidarSuperSU');
    });

    
    //ABM MESAS
    $app->group('/comanditaORM/mesas', function () {   
      
      //solo cargar el token de un ADMIN o SOCIO
      $this->post('/cargar', mesaControler::class . ':CargarUna')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/eliminar/{idMesa}', mesaControler::class . ':BorrarMesa')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/modificar/{idMesa}', mesaControler::class . ':ModificarMesa')->add(Middleware::class . ':ValidarSuperSU');
      $this->post('/cerrar/{idMesa}', mesaControler::class . ':CerrarMesa')->add(Middleware::class . ':validarUsuarioSocio');//socio
    });
    
    //comidas
    $app->group('/comanditaORM/menu', function () {   

      $this->post('/cargarComida', comidaControler::class . ':CargarComida')->add(Middleware::class . ':ValidarAdmin');//admin
      $this->post('/modificar/{idComida}', comidaControler::class . ':ModificarComida')->add(Middleware::class . ':ValidarAdmin');//admin
      $this->post('/eliminar/{idComida}', comidaControler::class . ':BorrarComida')->add(Middleware::class . ':ValidarAdmin');//admin

    })->add(Middleware::class . ':ValidarRuta');

  //pedidos
    $app->group('/comanditaORM/pedidos', function () {   

      $this->post('', pedidoControler::class . ':CargarPedido')->add(Middleware::class . ':ValidarMozo');
      $this->get('', pedidoControler::class . ':VerPedidos');//todos
      $this->post('/preparar/{idPedido}', pedidoControler::class . ':PrepararPedido');
      $this->post('/terminar/{idPedido}', pedidoControler::class . ':TerminarPedido');
      $this->post('/servir/{idPedido}', pedidoControler::class . ':ServirPedido')->add(Middleware::class . ':ValidarMozo');//mozo
      $this->post('/cancelar/{idPedido}', pedidoControler::class . ':CancelarPedido')->add(Middleware::class . ':ValidarMozo');
      $this->post('/cobrar/{codigoPedido}', pedidoControler::class . ':CobrarPedido')->add(Middleware::class . ':ValidarMozo');//mozo
      
    })->add(Middleware::class . ':ValidarRuta');

    //cliente
    $app->group('/comanditaORM/clientes', function () { 

      $this->get('/pedido', pedidoControler::class . ':MostrarTiempoRestante');
      $this->post('/encuesta', clienteControler::class . ':EncuestaCliente');
    });

    //admin
    $app->group('/comanditaORM/admin', function () { 

      $this->get('/operaciones', usuarioControler::class . ':ConsultarOperaciones');
      $this->get('/pedidos', pedidoControler::class . ':ConsultarPedidos');
      $this->get('/mesas', mesaControler::class . ':ConsultarMesas');
      })->add(Middleware::class . ':ValidarAdmin')->add(Middleware::class . ':ValidarRuta');

  };

