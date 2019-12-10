<?php
namespace App\Models\ORM;
use App\Models\ORM\usuario;//ruta completa de la clase
use App\Models\AutentificadorJWT;

include_once __DIR__ . '../../modelORM/usuario.php';
include_once __DIR__ . '../../modelAPI/AutentificadorJWT.php';

class Middleware
{
    public static function validarUsuarioSocio($request, $response, $next){
        $token = $request->getHeader('token');
        $datos = AutentificadorJWT::ObtenerData($token[0]);
        $id = $datos->id_empleado;
        
        $usuario = usuario::where('id_empleado', $id)->first();
        if($usuario != null)
        {
            //Compruebo la clave:
            if(strcasecmp($usuario->tipo, 'socio') == 0)
            {
               
                try
                {
                  AutentificadorJWT::VerificarToken($token[0]);
                  $esValido = true;
                }
                catch(Exception $e)
                {
                  $newResponse = $response->withJson($e->getMessage(), 200);
                }
                if($esValido)
                {
                    $newResponse = $next($request, $response); 
                }
                
            }
            else
            {
                $newResponse = $response->withJson("No es un usuario del tipo socio", 200);
            }
        }
        else
        {
            $newResponse = $response->withJson("No existe el usuario", 200);
        }
       
        return $newResponse;
    }

    public function ValidarMozo($request, $response, $next){
        $token = $request->getHeader('token');
        $datos = AutentificadorJWT::ObtenerData($token[0]);
        $id = $datos->id_empleado;
        
        $usuario = usuario::where('id_empleado', $id)->first();
        if($usuario != null)
        {
            //Compruebo la clave:
            if(strcasecmp($usuario->tipo, 'mozo') == 0)
            {
               
                try
                {
                  AutentificadorJWT::VerificarToken($token[0]);
                  $esValido = true;
                }
                catch(Exception $e)
                {
                  $newResponse = $response->withJson($e->getMessage(), 200);
                }
                if($esValido)
                {
                    $newResponse = $next($request, $response); 
                }
                
            }
            else
            {
                $newResponse = $response->withJson("No es un usuario del tipo mozo", 200);
            }
        }
        else
        {
            $newResponse = $response->withJson("No existe el usuario", 200);
        }
       
        return $newResponse;
    }

    public function ValidarAdmin($request, $response, $next){
        $token = $request->getHeader('token');
        $datos = AutentificadorJWT::ObtenerData($token[0]);
        $id = $datos->id_empleado;
        
        $usuario = usuario::where('id_empleado', $id)->first();
        if($usuario != null)
        {
            //Compruebo la clave:
            if(strcasecmp($usuario->tipo, 'admin') == 0)
            {
               
                try
                {
                  AutentificadorJWT::VerificarToken($token[0]);
                  $esValido = true;
                }
                catch(Exception $e)
                {
                  $newResponse = $response->withJson($e->getMessage(), 200);
                }
                if($esValido)
                {
                    $newResponse = $next($request, $response); 
                }
                
            }
            else
            {
                $newResponse = $response->withJson("No es un usuario del tipo admin", 200);
            }
        }
        else
        {
            $newResponse = $response->withJson("No existe el usuario", 200);
        }
       
        return $newResponse;
    }

    public function ValidarSuperSU($request, $response, $next){
        $token = $request->getHeader('token');
        $datos = AutentificadorJWT::ObtenerData($token[0]);
        $id = $datos->id_empleado;
        
        $usuario = usuario::where('id_empleado', $id)->first();
        if($usuario != null)
        {
            //Compruebo la clave:
            if(strcasecmp($usuario->tipo, 'socio') == 0 || strcasecmp($usuario->tipo, 'admin') == 0)
            {
               
                try
                {
                  AutentificadorJWT::VerificarToken($token[0]);
                  $esValido = true;
                }
                catch(Exception $e)
                {
                  $newResponse = $response->withJson($e->getMessage(), 200);
                }
                if($esValido)
                {
                    $newResponse = $next($request, $response); 
                }
                
            }
            else
            {
                $newResponse = $response->withJson("No cuenta con las credenciales necesarias para proceder", 200);
            }
        }
        else
        {
            $newResponse = $response->withJson("No existe el usuario", 200);
        }
       
        return $newResponse;
    }

    public static function validarRuta($request, $response, $next){
        $token = $request->getHeader('token');

        try{
            AutentificadorJWT::VerificarToken($token[0]);
            $newResponse = $next($request, $response);
            
        }catch(Exception $e){
            $newResponse = $response->withJson($e->getMessage(), 200);
        }
        return $newResponse;
    }
}
    
?>