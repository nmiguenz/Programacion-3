<?php
namespace App\Models\ORM;
use Slim\App;
use App\Models\ORM\mesa;
use App\Models\ORM\usuario;
use App\Models\AutentificadorJWT;

include_once __DIR__ . '/mesa.php';
include_once __DIR__ . '/usuario.php';
include_once __DIR__ . '../../modelAPI/AutentificadorJWT.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class mesaControler
{
    public function CargarUna($request, $response, $args){
        
        $datos = $request->getParsedBody();
        $token = $request->getHeader('token');
        
        $datosToken = AutentificadorJWT::ObtenerData($token[0]);     
        $idEmpleado = $datosToken->id_empleado;
    
        $empleado = usuario::where('id_empleado', $idEmpleado)->first();
        if(isset($datos['codigo_mesa']))
        {

            if($empleado != null)
            {
                if(strlen($datos['codigo_mesa']) == 5)
                {
                    $mesa = new mesa();
                    
                    $mesa->codigo_unico = $datos['codigo_mesa'];
                    $mesa->estado = 'Cerrada';
                    $mesa->save();
                    
                    usuarioControler::RegistrarOperacion($empleado, 'Mesa cargada');
                    $newResponse = $response->withJson("Mesa cargada con ID: $mesa->id", 200); 
                }
                else
                {
                    $newResponse = $response->withJson("El codigo de la mesa debe tener 5 caracteres alfanumericos", 200);
                }
            }
            else
            {
                $newResponse = $response->withJson("No existe o no se pudo cargar el usuario", 200);
            }
        }
        else
        {
            $newResponse = $response->withJson("Falta agregar el codigo de la mesa", 200);
        }
        
        return $newResponse;
    }
    
    public function BorrarMesa($request, $response, $args){
        
        $token = $request->getHeader('token');
        $idMesa = $request->getAttribute('idMesa');
        
        $datosToken = AutentificadorJWT::ObtenerData($token[0]);     
        $idEmpleado = $datosToken->id_empleado;
        
        $empleado = usuario::where('id_empleado', $idEmpleado)->first();
        
        $mesa = mesa::where('id', $idMesa)->first();
        
        if($mesa != null)
        {
            $mesa->delete();
            usuarioControler::RegistrarOperacion($empleado, 'Mesa borrada');

            $newResponse = $response->withJson("Mesa $idMesa eliminada", 200); 
        }
        else
        {
            $newResponse = $response->withJson("No se encontro la mesa $idMesa", 200); 
        }
        
        return $newResponse;
    }
    
    public function ModificarMesa($request, $response, $args){
        
        $token = $request->getHeader('token');
        $idMesa = $request->getAttribute('idMesa');
        $datos = $request->getParsedBody();
    
        $datosToken = AutentificadorJWT::ObtenerData($token[0]);   
        $idEmpleado = $datosToken->id_empleado;
        $empleado = usuario::where('id_empleado', $idEmpleado)->first();
        
        $mesa = mesa::where('id', $idMesa)->first();
        
        if($mesa != null)
        {
            if(isset($datos["estado"]))
            {
                $mesa->estado = $datos["estado"];    
            }
            if(isset($datos["codigo_Unico"]))
            {    
                $mesa->codigo_unico = $datos["codigo_Unico"];           
            }
          
            $mesa->save();

            usuarioControler::RegistrarOperacion($empleado, 'Mesa modificada');
            
            $newResponse = $response->withJson("Mesa modificada", 200);
        }
        else
        {
            $newResponse = $response->withJson("No se encontró la mesa $idMesa", 200);
        }         
        return $newResponse;
    }

    public function CerrarMesa($request, $response, $args){
        $token = $request->getHeader('token');
        $idMesa = $request->getAttribute('idMesa');
        //$idPedido = $request->getAttribute('idPedido');
       
        $datosToken = AutentificadorJWT::ObtenerData($token[0]);     
        $idEmpleado = $datosToken->id_empleado;
        $empleado = usuario::where('id_empleado', $idEmpleado)->first();
        
        $mesa = mesa::where('id', $idMesa)->first();
        $estado = strtolower($mesa->estado);

        if($mesa != null)
        {
            if($estado != 'cerrada')
            {
                $mesa->estado = 'Cerrada';
                $mesa->codigo_unico = null;
                $mesa->save();
    
                usuarioControler::RegistrarOperacion($empleado, 'Mesa cerrada');
    
                $newResponse = $response->withJson("Mesa $idMesa cerrada", 200); 
            }
            else
            {
                $newResponse = $response->withJson("No se pudo cerrar porque la mesa $idMesa ya estaba cerrada", 200); 
            }
        }
        else
        {
            $newResponse = $response->withJson("No se encontro la mesa $idMesa", 200); 
        }
        return $newResponse;
    }

    public function ConsultarMesas($request, $response, $args)
    {
        $listado = $request->getParam('listado');
        $idMesa = $request->getParam('idMesa');
        $fechaInicio = $request->getParam('fechaInicio');
        $fechaFin = $request->getParam('fechaFin');
        $informacion = null;
        
        switch($listado)
        {
            case "mas_usada":
                $informacion = factura::select('id_mesa')
                ->groupBy('id_mesa')
                ->orderByRaw('COUNT(*) DESC')
                ->selectRaw("COUNT(*) as veces_usada")
                ->limit(1)
                ->get();
            break;
            case "menos_usada":
                $informacion = factura::select('id_mesa')
                ->groupBy('id_mesa')
                ->orderByRaw('COUNT(*) ASC')
                ->selectRaw("COUNT(*) as veces_usada")
                ->limit(1)
                ->get();
            break;
            case "mas_facturo":
                $informacion = factura::select('id_mesa')
                ->groupBy('id_mesa')
                ->orderByRaw('SUM(monto) desc')
                ->selectRaw("SUM(monto) as monto_total")
                ->limit(1)
                ->get();
            break;
            case "menos_facturo":
                $informacion = factura::select('id_mesa')
                ->groupBy('id_mesa')
                ->orderByRaw('SUM(monto) asc')
                ->selectRaw("SUM(monto) as monto_total")
                ->limit(1)
                ->get();
            break;
            case "mayor_importe":
                $informacion = factura::orderBy('monto', 'desc')
                ->select('id_mesa', 'monto')
                ->first();
            break;
            case "menor_importe":
                $informacion = factura::orderBy('monto', 'asc')
                ->select('id_mesa', 'monto')
                ->first();
            break; 
            case "entre_dos_fechas":
                if($idMesa != null)
                {
                    $informacion = factura::where('id_mesa', $idMesa)
                    ->where('hora', '>=', $fechaInicio)
                    ->where('hora', '<=', $fechaFin)
                    ->get();
                }
            break;               
            case "mejores_comentarios":
                $informacion = encuesta::select('id_cliente', 'texto_experiencia')
                ->selectRaw("(puntaje_mesa + puntaje_restaurante + puntaje_mozo + puntaje_cocinero) as puntaje_total")
                ->orderByRaw('(puntaje_mesa + puntaje_restaurante + puntaje_mozo + puntaje_cocinero) desc')
                ->limit(3)
                ->get();
            break; 
            case "peores_comentarios":
                $informacion = encuesta::select('id_cliente', 'texto_experiencia')
                ->selectRaw("(puntaje_mesa + puntaje_restaurante + puntaje_mozo + puntaje_cocinero) as puntaje_total")
                ->orderByRaw('(puntaje_mesa + puntaje_restaurante + puntaje_mozo + puntaje_cocinero) asc')
                ->limit(3)
                ->get();
            break; 
        }
        if($informacion == null)
        {
            $informacion = 'No hay pedidos';
        }

        $newResponse = $response->withJson($informacion, 200);
        
        return $newResponse;
    }
  
}