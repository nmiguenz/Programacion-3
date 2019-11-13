<?php
namespace App\Models\ORM;
use App\Models\ORM\usuario;
use App\Models\IApiControler;

include_once __DIR__ . '/usuario.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\AutentificadorJWT;
use \Exception;


class usuarioControler implements IApiControler 
{
 	public function Beinvenida($request, $response, $args) {
      $response->getBody()->write("GET => Bienvenido!!! ,a UTN FRA SlimFramework");
    
    return $response;
    }
    
     public function TraerTodos($request, $response, $args) {
       	//return cd::all()->toJson();
        $todosLosUsuarios=usuario::all();
        $newResponse = $response->withJson($todosLosUsuarios, 200);  
        return $newResponse;
    }
    public function TraerUno($request, $response, $args) {
     	//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
    	return $newResponse;
    }
   
      public function CargarUno($request, $response, $args) {
        //complete el codigo
        $datos = $request->getParsedBody();

        $usuario = new usuario;
        $usuario->email = $datos['email'];
        $usuario->clave = crypt($datos['clave'],'aaa');
        $usuario->tipo = $datos['tipo'];
        $usuario->save();

        $newResponse = $response->withJson("sin completar", 200);
         
        return $response;
    }

    //los datos provienen del request de middlewareRoute
    public function LoginUsuario($request, $response, $args){
        //Recibe legajo y nombre y si son correctos devuelve un JWT, 
        //de lo contrario informar lo sucedido.
        $esValido = false;
        $datos = $request->getParsedBody();
        $token = $datos['token'];

        //Verifico que el token sea confiable
        try
        {
          AutentificadorJWT::VerificarToken($token);
          $esValido = true;

          if($esValido)
          {
            
            $datos = AutentificadorJWT::ObtenerData($token);
            $email = $datos->email;
            $tipo = $datos->tipo;
            if($tipo == 'admin')
            {
              $newResponse = $response->withJson("Bienvenido admin", 200);
            }
            else
            {
              $newResponse = $response->withJson("Bienvenido $email", 200);
            }    
          }
        }
        catch(Exception $e)
        {
          $newResponse = $response->withJson($e->getMessage(), 200);
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