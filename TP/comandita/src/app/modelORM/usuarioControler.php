<?php
namespace App\Models\ORM;
use App\Models\ORM\usuario;
// use App\Models\ORM\pedido;
use App\Models\ORM\registros;
use App\Models\IApiControler;
use App\Models\AutentificadorJWT;

include_once __DIR__ . '/usuario.php';
// include_once __DIR__ . '/pedido.php';
include_once __DIR__ . '/registros.php';
include_once __DIR__ . '../../modelAPI/AutentificadorJWT.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class usuarioControler implements IApiControler 
{
 	public function Beinvenida($request, $response, $args) {
      $response->getBody()->write("GET => Bienvenido!!! ,a UTN FRA SlimFramework");
    
    return $response;
    }
    
    public function TraerTodos($request, $response, $args) {}
    public function TraerUno($request, $response, $args) {}
   
    public function CargarUno($request, $response, $args) {
        
        $datos = $request->getParsedBody();
        $archivos = $request->getUploadedFiles();
        
        //falta validar que no este repetido el nombre y apellido
        if(isset($datos['tipo'], $datos['nombre'], $datos['apellido'], $datos['clave']))
        {
          $usuario = new usuario;
          if($usuario != null)
          {
            $usuario->nombre = $datos['nombre'];
            $usuario->apellido = $datos['apellido'];
            $usuario->nombre_usuario = $datos['nombre_usuario'];
            $usuario->clave = crypt($datos['clave'],'claveSecreta');
            $tipo = $datos['tipo'];
            if(strcasecmp(strtolower($tipo),'socio' ) == 0 ||
                strcasecmp(strtolower($tipo),'admin' ) == 0 || 
                strcasecmp(strtolower($tipo),'bartender' ) == 0 || 
                strcasecmp(strtolower($tipo), 'cervecero') == 0 || 
                strcasecmp(strtolower($tipo), 'mozo') == 0 ||
                strcasecmp(strtolower($tipo), 'cocinero') == 0)
            {
              $usuario->tipo = strtolower($tipo);
              if($archivos != null)
                {
                    $usuario->foto = usuarioControler::GuardarArchivoTemporal($archivos['foto'], __DIR__ . "../../../../img/usuarios/",
                    $usuario->id_empleado.$usuario->tipo);    
                }
              $usuario->save();

              usuarioControler::RegistrarOperacion($usuario, 'Empleado cargado');
              $newResponse = $response->withJson("Se cargó un usuario de tipo $usuario->tipo", 200);
            }
            
            else
            {
              $newResponse = $response->withJson("No se guardo el usuario porque el tipo es distinto de los permitidos", 200);      
            }
          }
          else
          {
            $newResponse = $response->withJson("No se pudo crear el usuario", 200);
          }
        }
        else
        {
          $newResponse = $response->withJson("Faltan completar campos del body", 200);  
        }
         
        return $newResponse;
    }

    public function loginUsuario($request, $response, $args){
        $datos = $request->getParsedBody();
        if(isset($datos['nombreUsuario'], $datos['clave']))
        {
            $nombreUsuario = strtolower($datos['nombreUsuario']);
            $clave = $datos['clave'];
            $usuario = usuario::where('nombre_usuario', $nombreUsuario)->first();

            if($usuario !=null)
            {
                if(hash_equals($usuario->clave, crypt($clave, 'claveSecreta')))
                {
                    $datosUsuario = array(
                    'id_empleado' => $usuario->id_empleado,
                    'nombre_usuario' => $usuario->nombre_usuario,
                    'nombre'=> $usuario->nombre,
                    'apellido'=> $usuario->apellido,
                    'tipo' => $usuario->tipo
                    );

                    $token = AutentificadorJWT::CrearToken($datosUsuario);

                    usuarioControler::RegistrarOperacion($usuario, 'Login');
                    $newResponse  = $response->withJson('Bienvenido '.$nombreUsuario.', su token es: '.$token, 200);
                }
                else
                {
                    $newResponse  = $response->withJson('La clave ingresada es incorrecta', 200);
                }
            }
            else
            {
            $newResponse  = $response->withJson('No se encontro el usuario', 200);
            }
        }
        else
        {
            $newResponse  = $response->withJson('Faltan ingresar datos en el body', 200);
        }
        
        return $newResponse;
    }
    
    public function BorrarUno($request, $response, $args) {
        
        $datos = $request->getParsedBody();
        
        if(isset($datos['nombreUsuario'], $datos['clave']))
        {
            $nombreUsuario = strtolower($datos['nombreUsuario']);

            $usuarioABorrar = usuario::where('nombre_usuario', $nombreUsuario)->first();

            usuarioControler::RegistrarOperacion($usuarioABorrar, 'Empleado eliminado');
            
            $usuarioABorrar->delete();

            $newResponse = $response->withJson('Se borro el usuario '.$nombreUsuario, 200);
            
        }
        else
        {
            $newResponse  = $response->withJson('Faltan ingresar datos en el body', 200);
        }
        
        return $newResponse;
    }
     
    public function ModificarUno($request, $response, $args) {
        
        $usuarioAModificar = null;
        $seGuardoUsuario=false;
        
        $datosModificados = $request->getParsedBody();
        $archivos = $request->getUploadedFiles();

        // $legajo = $request->getParam('legajo'); //legajo que le paso por param
        //$legajo = $request->getAttribute('legajo');//si se lo paso por url

        $nombreUsuario = $args['nombre_usuario'];

        $token = $request->getHeader('token');
        
        $datosToken = AutentificadorJWT::ObtenerData($token[0]);
        $usuarioAModificar= usuario::where('nombre_usuario', $nombreUsuario)->first();
        
        
        if($usuarioAModificar != null){ 
            
            if(isset($datosModificados['nombre']))
            {
                $usuarioAModificar->nombre = $datosModificados['nombre'];
            }
            else if(isset($datosModificados['apellido']))
            {
                $usuarioAModificar->apellido = $datosModificados['apellido'];
            }
            else if(isset($datosModificados['nombre_usuario']))
            {
                $usuarioAModificar->nombre_usuario = $datosModificados['nombre_usuario'];
            }
            else if(isset($datosModificados['tipo']))
            {
                $usuarioAModificar->apellido = $datosModificados['tipo'];
            }
            else if(isset($datosModificados['clave']))
            {
                $usuarioAModificar->clave = $datosModificados['clave'];
            }
            else if(isset($archivos['foto']))
            {
                //usuarioControler::HacerBackup(__DIR__ . "../../../../img/", $usuarioAModificar);
                $usuarioAModificar->foto = usuarioControler::GuardarArchivoTemporal($archivos['foto'], __DIR__ . "../../../../img/usuarios/",
                                                                                    $usuarioAModificar->id_empleado.$usuarioAModificar->tipo);
            }
            
            $usuarioAModificar->save();
            $seGuardoUsuario=true;
            
        }         
        else
        {
            return $response->withJson("Error, El id no corresponde a un usuario", 200);
        }

        if($seGuardoUsuario){

            usuarioControler::RegistrarOperacion($usuarioAModificar, 'Empleado modificado');            
            return $response->withJson("Usuario modificado correctamente", 200);
        }

		return 	$response->withJson("Error, el usuario no pudo guardarse, verifique el tipo de usuario", 200);
    }

    public static function GuardarArchivoTemporal($archivo, $destino, $nombre){
        $origen = $archivo->getClientFileName();
        
        $fecha = new \DateTime();
        $fecha = $fecha->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'));
        $fecha = $fecha->format("d-m-Y-His");
        $extension = pathinfo($archivo->getClientFileName(), PATHINFO_EXTENSION);
        $destino = "$destino$nombre-$fecha.$extension";
        $archivo->moveTo($destino);
        return $destino;
    }

    //Registra todas las operaciones que se realizan 
    //con los usuarios que pertenecen a la empresa
    public static function RegistrarOperacion($empleado, $operacion){
        $hora = new \DateTime();
        $hora = $hora->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'));
        $registroOperacion = new registros();
        $registroOperacion->hora = $hora;
        $registroOperacion->id_empleado = $empleado->id_empleado;
        $registroOperacion->operacion = $operacion;
        
        $registroOperacion->save();
    }

    // public function ConsultarOperaciones($request, $response, $args)
    // {
    //     $listado = $request->getParam('listado');
    //     $idEmpleado = $request->getParam('idEmpleado');
        
    //     switch($listado)
    //     {
    //         case "logins":
    //             $informacion = registros::where('operacion', 'Login')
    //                 ->get(['id', 'operacion', 'id_empleado', 'hora']);
    //         break;
    //         case "operaciones_por_sector":
    //             $informacion = registros::join('empleados', 'operaciones_registros.id_empleado', '=', 'empleados.id')
    //                 ->orderBy('empleados.tipo', 'asc')
    //                 ->get(['operaciones_registros.id', 'operacion', 'id_empleado', 'empleados.tipo', 'hora']);
    //         break;
    //         case "operaciones_por_sector_por_empleado":
    //             $informacion = registros::join('empleados', 'operaciones_registros.id_empleado', '=', 'empleados.id')
    //                 ->orderBy('empleados.tipo', 'asc')
    //                 ->orderBy('empleados.id', 'asc')
    //                 ->get(['operaciones_registros.id', 'operacion', 'id_empleado', 'empleados.tipo', 'hora']);
    //         break;
    //         case "operaciones_del_empleado":
    //             if($idEmpleado != null)
    //             {
    //                 $informacion = registros::where('id_empleado', $idEmpleado)
    //                 ->join('empleados', 'operaciones_registros.id_empleado', '=', 'empleados.id')
    //                 ->orderBy('empleados.tipo', 'asc')
    //                 ->orderBy('empleados.id', 'asc')
    //                 ->get(['operaciones_registros.id', 'operacion', 'id_empleado', 'empleados.tipo', 'hora']);
    //             }
    //             else
    //             {
    //                 $informacion = "Falta id empleado";            
    //             }
    //         break;
    //         default:
    //         $informacion = registos::get();
                
    //     }
    //     $newResponse = $response->withJson($informacion, 200);
    //     return $newResponse;
    // }
  
}