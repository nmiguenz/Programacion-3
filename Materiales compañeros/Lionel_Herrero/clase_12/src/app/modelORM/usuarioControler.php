<?php
namespace App\Models\ORM;

use Slim\App;
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
 
  public function TraerTodos($request, $response, $args) {
        
    $todosLosUsuarios = usuario::all();
    
    $newResponse = $response->withJson($todosLosUsuarios, 200);  

    return $newResponse;
  }

  public function TraerUno($request, $response, $args) {
      //complete el codigo
    $newResponse = $response->withJson("sin completar", 200);  
    return $newResponse;
  }
    
  public function CargarUno($request, $response, $args) {
        
    $datos = $request->getParsedBody();
    
    //Agrego a la base de datos:
    $usuario = new usuario;
    $usuario->clave = crypt($datos["clave"], "aaa");//generar salt para que coincidan
    $usuario->email = $datos["email"];
    $usuario->perfil = $datos["perfil"];
    $usuario->save();

    $newResponse = $response->withJson("Usuario registrado", 200);  

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

  public function loginUsuario($request, $response, $args)
  {
    $esValido = false;
    $datos = $request->getParsedBody();

    $token = $datos['token'];

    //Verifico que el token sea confiable
    try
    {
      AutentificadorJWT::VerificarToken($token);
      $esValido = true;
    }
    catch(Exception $e)
    {
      $newResponse = $response->withJson($e->getMessage(), 200);
    }

    if($esValido)
    {
      //Obtengo los datos
      $datos = AutentificadorJWT::ObtenerData($token);

      $email = $datos->email;
      $perfil = $datos->perfil;

      if($perfil == 'admin')
      {
        $newResponse = $response->withJson("Bienvenido admin", 200);
      }
      else
      {
        $newResponse = $response->withJson("Bienvenido $email", 200);
      }
      
    }
    

    

    return $newResponse;

  }


  
}