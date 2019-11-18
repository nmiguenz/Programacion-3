<?php
namespace App\Models\ORM;
use App\Models\ORM\usuario;
use App\Models\IApiControler;

include_once __DIR__ . '/usuario.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\AutentificadorJWT;


class usuarioControler implements IApiControler 
{
 	public function Beinvenida($request, $response, $args) {
      $response->getBody()->write("GET => Bienvenido!!! ,a UTN FRA SlimFramework");
    
    return $response;
    }
    
    public function TraerTodos($request, $response, $args) {
       	//return cd::all()->toJson();
        $todosLosCds=usuario::all();
        $newResponse = $response->withJson($todosLosCds, 200);  
        return $newResponse;
    }
    public function TraerUno($request, $response, $args) {
     	//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
    	return $newResponse;
    }
    public function CargarUno($request, $response, $args) {
     	 $datos = $request->getParsedBody();
        
        if(isset($datos['tipo'], $datos['email'], $datos['clave']))
        {
          $usuario = new usuario;
          if($usuario != null)
          {
            $usuario->email = $datos['email'];
            $usuario->clave = crypt($datos['clave'],'aaa');
            $tipo = $datos['tipo'];
            if(strcasecmp(strtolower($tipo),'alumno' ) == 0 || 
              strcasecmp(strtolower($tipo),'profesor' ) == 0 || 
              strcasecmp(strtolower($tipo), 'admin') == 0)
            {
              $usuario->tipo = strtolower($tipo);
              $usuario->save();
              $newResponse = $response->withJson("Se cargo el usuario correctamente", 200);
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
    public function LoginUsuario($request, $response, $args){
        
      $datos = $request->getParsedBody();

      if(isset($datos['legajo'], $datos['clave']))
      {

        $legajo = $datos['legajo'];
        $clave = $datos['clave'];

        $usuario = usuario::where('legajo', $legajo)->first();

        if($usuario !=null)
        {
          if(hash_equals($usuario->clave, crypt($clave, 'aaa')))
          {
            $datosUsuario = array(
              'legajo' => $usuario->legajo,
              'email'=> $usuario->email,
              'tipo' => $usuario->tipo
            );

            $token = AutentificadorJWT::CrearToken($datosUsuario);

            $newResponse  = $response->withJson($token, 200);
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
  		//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
      	return $newResponse;
    }
     
     public function ModificarUno($request, $response, $args) {
     	//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
		return 	$newResponse;
    }


  
}