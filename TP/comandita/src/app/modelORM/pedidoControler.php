<?php
namespace App\Models\ORM;
use App\Models\ORM\usuario;
use App\Models\IApiControler;
use App\Models\AutentificadorJWT;

include_once __DIR__ . '/usuario.php';
include_once __DIR__ . '../../modelAPI/AutentificadorJWT.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class pedidoControler implements IApiControler 
{
 	public function Bienvenida($request, $response, $args) {
      $response->getBody()->write("GET => Bienvenido!!! ,a UTN FRA SlimFramework");
    
    return $response;
    }
    
    public function TraerTodos($request, $response, $args) {}
    public function TraerUno($request, $response, $args) {}
   
    //modificar para que se asigne a cada [area] segun lo que se pida
    public function CargarUno($request, $response, $args) {
      $datos = $request->getParsedBody();
      $token = $request->getHeader('token');
      
      $token = $token[0];
      
      $datosToken = AutentificadorJWT::ObtenerData($token);  
      $idEmpleado = $datosToken->id;
      $empleado = usuario::where('id', $idEmpleado)->first();
      
      if(isset($datos['nombreComida'], $datos['idMesa'], $datos['nombre'], $datos['apellido']))
      {
          $mesa = mesa::where('id', $datos['idMesa'])->first();
          if($mesa != null)
          {
              
              $horaCreacion = new \DateTime();//timestamp creacion del pedido
              $horaCreacion = $horaCreacion->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'));
              
              if(isset($datos['codigoMesa']))//Si una mesa quiere pedir algo más
              {
                  $codigoMesa = $datos['codigoMesa'];
              }
              else
              {
                  //creo id random para la mesa:
                  $codigoMesa = substr(md5(uniqid(rand(), true)), 0, 5);
                  //guardo el código de la mesa:
                  $mesa->codigo_unico = $codigoMesa;
                  $mesa->estado = 'Con cliente esperando pedido';
                  $mesa->save(); 
              }
              //codigo único del pedido:
              $codigoUnico = substr(md5(uniqid(rand(), true)), 0, 5);
          
              if(is_array($datos['nombreComida']))//si pide más de 1 cosa
              {
                  $length = count($datos['nombreComida']);
                  for($i = 0; $i < $length; $i++)
                  {
                      $pedido = new pedido();
                      $pedido->codigo_mesa =  $codigoMesa; //asigno el id
                      $pedido->codigo_unico = $codigoUnico;
                      $idComida = comida::where('nombre', $datos['nombreComida'][$i])
                                  ->select('id')
                                  ->first();
                      $pedido->id_comida = $idComida->id;
                      
                      $pedido->hora_creacion = $horaCreacion;
                  
                      $pedido->save();
                  }
              }
              else
              {
                  $pedido = new pedido();
                  $pedido->codigo_mesa =  $codigoMesa; //asigno el id
                  $pedido->codigo_unico = $codigoUnico;
                  $idComida = comida::where('nombre', $datos['nombreComida'])
                                  ->select('id')
                                  ->first();
                  $pedido->id_comida = $idComida->id;                
                  
                  $pedido->hora_creacion = $horaCreacion;
                  $pedido->save();
              }
              //cargo los datos del cliente
              $cliente = new cliente();
              $cliente->nombre = $datos["nombreCliente"];
              $cliente->apellido = $datos["apellidoCliente"];
              $cliente->codigo_mesa = $codigoMesa;
              $cliente->codigo_pedido = $codigoUnico;
              $cliente->save();
              empleadoControler::RegistrarOperacion($empleado, 'Cargar Pedido');
              $newResponse = $response->withJson("Pedido en preparacion. Id Mesa: $codigoMesa, Id Pedido: $codigoUnico", 200);//devuelvo el id de la mesa
      
          }
          else
          {
              $newResponse = $response->withJson("No existe la mesa", 200);  
          }
    
      }
      else
      {      
          $newResponse = $response->withJson("Faltan datos", 200);            
      }
      return $newResponse;
    }

    public function BorrarUno($request, $response, $args) {} 
    public function ModificarUno($request, $response, $args) {}
  
}