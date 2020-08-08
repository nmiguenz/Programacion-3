<?php
namespace App\Models\ORM;

use Slim\App;
use App\Models\ORM\pedido;
use App\Models\ORM\mesa;
use App\Models\ORM\factura;
use App\Models\ORM\cliente;
use App\Models\ORM\comida;


include_once __DIR__ . '/pedido.php';
include_once __DIR__ . '/mesa.php';
include_once __DIR__ . '/factura.php';
include_once __DIR__ . '/cliente.php';
include_once __DIR__ . '/comida.php';


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\AutentificadorJWT;
use \Exception;


class pedidoControler
{
    //Solo el MOZO puede hacerlo
    //Se agregan de a 1 las comidas
    public function CargarPedido($request, $response, $args)
    {
        $datos = $request->getParsedBody();
        $token = $request->getHeader('token');
        
        $token = $token[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);  
        $idEmpleado = $datosToken->id_usuario;

        $empleado = usuario::where('id_usuario', $idEmpleado)->first();
        
        if(isset($datos['nombreComida'], $datos['idMesa'], $datos['nombreCliente'], $datos['apellidoCliente']))
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
                    $mesa->estado = 'con cliente esperando pedido';
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

                usuarioControler::RegistrarOperacion($empleado, 'Cargar Pedido');

                $newResponse = $response->withJson("Pedido en preparacion. Id Mesa: $codigoMesa, Id Pedido: $codigoUnico", 200);//devuelvo el id de la mesa
        
            }
            else
            {
                $newResponse = $response->withJson("No existe la mesa", 200);  
            }
      
        }
        else
        {      
            $newResponse = $response->withJson("Falta dato", 200);            
        }

        return $newResponse;
    }

    //Cualquier usuario puede ver el pedido, pero solo ve lo que refiere a su area
    //Socio ve TODOS los pedidos con estado: 'pendiente'
    //
    public function VerPedidos($request, $response, $args)
    {
        $token = $request->getHeader('token');
        $token = $token[0];
        $datosToken = AutentificadorJWT::ObtenerData($token); 
        
        $idEmpleado = $datosToken->id_usuario;
        $empleado = usuario::where('id_usuario', $idEmpleado)->first();
    
        switch($empleado->tipo)
        {
            case 'bartender':
            $pedidos = comida::where([['tipo', 'bebida'],['estado', 'pendiente']])
                        ->join('pedidos', 'pedidos.id_comida', '=', 'comidas.id')
                        ->select('pedidos.id', 'comidas.nombre', 'pedidos.estado')
                        ->get();
            break;

            case 'cervecero':
            $pedidos = comida::where([['tipo', 'cerveza'],['estado', 'pendiente']])
                        ->join('pedidos', 'pedidos.id_comida', '=', 'comidas.id')
                        ->select('pedidos.id', 'comidas.nombre', 'pedidos.estado')
                        ->get();
            // $pedidos = pedido::where([
            //     ['tipo', 'cerveza'],
            //     ['estado', 'pendiente']])
            //     ->select('nombre', 'estado')
            //     ->get();
            break;

            case 'cocinero':
            $pedidos = comida::where([['tipo', 'comida'],['estado', 'pendiente']])
                        ->join('pedidos', 'pedidos.id_comida', '=', 'comidas.id')
                        ->select('pedidos.id', 'comidas.nombre', 'pedidos.estado')
                        ->get();
            break;

            //solucionar SOCIO
            case 'socio':
            $pedidos = pedido::all('nombre','tipo', 'estado',
                    'id_mesa', 'hora_creacion', 'tiempo_preparacion',
                    'hora_entrega');
            break;
        }

        usuarioControler::RegistrarOperacion($empleado, 'Ver Pedido');

        $newResponse = $response->withJson($pedidos, 200);
    
        return $newResponse;
    }

    //Cambia el estado del pedido a 'En preparacion'
    //Se le pasa tiempoEstimado en minutos
    //Puede hacerlo cualquier usuario
    public function PrepararPedido($request, $response, $args)
    {
        $token = $request->getHeader('token');
        $idPedido = $request->getAttribute('idPedido');
        $datos = $request->getParsedBody();

        $token = $token[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);
        
        $idEmpleado = $datosToken->id_usuario;
        $empleado = usuario::where('id_usuario', $idEmpleado)->first();

        if($idPedido != null)
        {
            //Tiempo estimado en minutos
            if(isset($datos['tiempoEstimado']))
            {

                $tiempoEstimado = $datos['tiempoEstimado'];

                $pedido = pedido::where('id', $idPedido)->first();
                if($pedido != null && strcasecmp($pedido->estado, 'pendiente') == 0)
                {
                    $pedido->tiempo_preparacion = $tiempoEstimado;
                    $pedido->estado = 'En preparación';
                    $pedido->save();
        
                    usuarioControler::RegistrarOperacion($empleado, 'Preparar pedido');
        
                    $newResponse = $response->withJson("Pedido $idPedido en preparación", 200);   
                }
                else
                {
                    $newResponse = $response->withJson("No encontró el pedido $idPedido", 200);       
                }              
            }
            else
            {
                $newResponse = $response->withJson("No se estableció el tiempo estimado", 200);   
            }
        }
        else
        {
            $newResponse = $response->withJson("Falta id del pedido", 200); 
        }

        return $newResponse;
    }

    //Cualquier usuario puede terminar un pedido
    //Solo se puede TERMINAR si antes estuvo 'en preparacion'
    public function TerminarPedido($request, $response, $args)
    {
        $token = $request->getHeader('token');
        $idPedido = $request->getAttribute('idPedido');

        $token = $token[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);     
        $idEmpleado = $datosToken->id_usuario;

        $empleado = usuario::where('id_usuario', $idEmpleado)->first();
        $pedido = pedido::where('id', $idPedido)->first();

        if($pedido != null && strcasecmp($pedido->estado, 'En preparación') == 0)
        {
            $pedido->estado = "Listo para servir";

            $pedido->save();

            usuarioControler::RegistrarOperacion($empleado, 'Terminar Pedido');
            
            $newResponse = $response->withJson("Pedido $idPedido listo para servir", 200);
        }
        else
        {
            $newResponse = $response->withJson('No se encontró el pedido', 200);
        }

        return $newResponse;
    }

    //Lo sirve el MOZO
    //Solo si el estado es 'Listo para servir'
    public function ServirPedido($request, $response, $args)
    {

        $token = $request->getHeader('token');
        $idPedido = $request->getAttribute('idPedido');

        $token = $token[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);     
        $idEmpleado = $datosToken->id_usuario;

        $empleado = usuario::where('id_usuario', $idEmpleado)->first();
        $pedido = pedido::where('id', $idPedido)->first();

        if($pedido != null && strcasecmp($pedido->estado, 'Listo para servir') == 0)
        {
            $horaEntrega = new \DateTime();//timestamp creacion del pedido
            $horaEntrega = $horaEntrega->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'));

            
            $pedido->hora_entrega = $horaEntrega;
            $pedido->estado = "Entregado";
            $pedido->save();

            $codigoUnico = $pedido->codigo_mesa;

            $mesa = mesa::where('codigo_unico', $codigoUnico)->first();
            $mesa->estado = 'Con clientes comiendo';
            $mesa->save();

            usuarioControler::RegistrarOperacion($empleado, 'Servir Pedido');
            
            $newResponse = $response->withJson("Pedido $idPedido entregado", 200);
        }
        else
        {
            $newResponse = $response->withJson("No se encontró el pedido $idPedido", 200);
        }

        return $newResponse;
    }

    //Le muestra al CLIENTE el tiempo que falta para que el pedido sea entregado
    public function MostrarTiempoRestante($request, $response, $args)
    {
        $codigoMesa = $request->getParam('codigoMesa');
        $codigoPedido = $request->getParam('codigoPedido');

        if(isset($codigoMesa, $codigoPedido))
        {

            //Busco los pedidos que coincidan            
            $pedidos = pedido::where([['codigo_mesa', $codigoMesa], 
                                     ['codigo_unico', $codigoPedido],
                                     ['estado', '!=', 'Cancelado']])->get();

            //Busco el que más tarde:
            $tiempoEstimado = 0;
            $pedido = null;

            foreach($pedidos as $auxPedido)
            {
                if($tiempoEstimado < $auxPedido->tiempo_preparacion)
                {
                    $pedido = $auxPedido;
                    $tiempoEstimado = $auxPedido->tiempo_preparacion;
                }
            }

            if($pedido != null)
            {
                $tiempoPreparacion = $pedido->tiempo_preparacion;

                $horaEntrega = new \DateTime($pedido->hora_creacion, new \DateTimeZone('America/Argentina/Buenos_Aires'));//timestamp creacion del pedido 
                $horaEntrega->modify("+$tiempoPreparacion minutes");//agrego los minutos
                
                $horaActual = new \DateTime();
                $horaActual = $horaActual->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'));
    
                
                if($horaEntrega > $horaActual)
                {
                    //Saco la diferencia
                    $tiempoRestante = $horaEntrega->diff($horaActual);

                    //Formateo para mostrar como string:
                    $tiempoRestante = $tiempoRestante->format('%i minuto(s)');
                    $horaEntrega = $horaEntrega->format("H:i:s");

                    $newResponse = $response->withJson("Falta(n) $tiempoRestante para su pedido. Hora de entrega: $horaEntrega", 200);
                }
                else//si está atrasado
                {
                    $newResponse = $response->withJson("Pedido atrasado. En breve le entregaremos el pedido", 200);
                }         
            }
            else
            {
                $newResponse = $response->withJson("No se encontró el pedido", 200);
            }
        }
        else
        {
            $newResponse = $response->withJson("Faltan datos", 200);
        }

        return $newResponse; 
    }

    //Solo el MOZO puede cancelar el pedido
    //Se realiza a traves del id
    public function CancelarPedido($request, $response, $args)
    {
        $token = $request->getHeader('token');
        $token = $token[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);     
        $idEmpleadoToken = $datosToken->id_usuario;
        $empleadoToken = usuario::where('id_usuario', $idEmpleadoToken)->first();

        $idPedido = $request->getAttribute('idPedido');

        $pedido = pedido::where('id', $idPedido)->first();

        if($pedido != null)
        {        
            $pedido->estado = 'Cancelado';

            $pedido->save();

            $estado = $pedido->estado;

            usuarioControler::RegistrarOperacion($empleadoToken, 'Cancelar Pedido');

            $newResponse = $response->withJson("Se cambió el estado a $estado", 200);       
        }
        else
        {
            $newResponse = $response->withJson("No se encontro al pedido $idPedido", 200); 
        }

        return $newResponse;
    }

    //Es el MOZO quien solicita el cobro del pedido
    //Se genera factura
    public function CobrarPedido($request, $response, $args)
    {
        $token = $request->getHeader('token');
        $codigoPedido = $request->getAttribute('codigoPedido');

        $token = $token[0];
        $datosToken = AutentificadorJWT::ObtenerData($token);     
        $idEmpleado = $datosToken->id_usuario;

        $empleado = usuario::where('id_usuario', $idEmpleado)->first();

        $pedidos = pedido::where('codigo_unico', $codigoPedido)->get();

        //Busco las comidas y el precio:
        $pedidos = pedido::where('codigo_unico', $codigoPedido)
                ->join('comidas', 'pedidos.id_comida', '=', 'comidas.id')
                ->get(['comidas.nombre', 'comidas.precio'])
                ->toArray();

        //Calculo el total:
        $montoTotal = 0;
        $cuenta = "Pedido:";
        foreach($pedidos as $pedido)
        {
            $montoTotal += $pedido["precio"];

            $cuenta = $cuenta . "<br>" . $pedido["nombre"] . "....." . $pedido["precio"];
        }

        //Modifico el estado de la mesa:
        $pedido = pedido::where('codigo_unico', $codigoPedido)->first();
        $codigoUnico = $pedido->codigo_mesa;
        $mesa = mesa::where('codigo_unico', $codigoUnico)->first();
        $mesa->estado = 'Con cliente pagando';
        $mesa->save();

        //facturación:
        $factura = new factura();
        $factura->id_mesa = $mesa->id;
        $factura->codigo_pedido = $codigoPedido;
        $factura->monto = $montoTotal;
        $factura->save();

        //Cambio el estado del pedido
        $pedidos = pedido::where('codigo_unico', $codigoPedido)->get();
        foreach($pedidos as $pedido)
        {
            $pedido->estado = "Cobrado";
            $pedido->save();
        }

        usuarioControler::RegistrarOperacion($empleado, 'Cobrar Pedido');

        $newResponse = $response->withJson($cuenta . "<br>Total a pagar: $montoTotal",200);

        return $newResponse;

    }

    //Consulta en la tabla pedido
    //Segun el parametro de $listado
    public function ConsultarPedidos($request, $response, $args)
    {
        $listado = $request->getParam('listado');
        $informacion = null;
        

        switch($listado)
        {
            case "mas_vendido":
                $informacion = pedido::where('estado', '!=', 'Cancelado')
                ->select('id_comida')
                ->groupBy('id_comida')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(1)
                ->get();
            break;
            case "menos_vendido":
                $informacion = pedido::where('estado', '!=', 'Cancelado')
                ->select('id_comida')
                ->groupBy('id_comida')
                ->orderByRaw('COUNT(*) ASC')
                ->limit(1)
                ->get();
            break;
            case "entragados_tarde":
                $pedidos = pedido::where('estado', '!=', 'Cancelado')
                ->selecet('id', 'id_comida', 'hora_creacion', 'tiempo_preparacion', 'hora_entrega');
                foreach($pedidos as $pedido)
                {
                    $tiempoPreparacion = $pedido->tiempo_preparacion;

                    $horaEstimada = new \DateTime($pedido->hora_creacion, new \DateTimeZone('America/Argentina/Buenos_Aires'));//timestamp creacion del pedido 
                    $horaEstimada->modify("+$tiempoPreparacion minutes");//agrego los minutos

                    if($pedido->hora_entrega > $horaEstimada)
                    {
                        
                        array_push($informacion, $pedido);
                    }
                }
                
            break;
            case "cancelados":
                $informacion = pedido::where('estado', 'Cancelado')
                ->select('id_comida')
                ->get();
                //$informacion = pedido::get(['id', 'id_comida', 'estado']);
            break;

            default:
            $informacion = pedido::get(['id', 'id_comida', 'estado']);
                
        }

        if($informacion == null)
        {
            $informacion = 'No hay pedidos';
        }

        $newResponse = $response->withJson($informacion, 200);

        return $newResponse;
    }


    
  
}